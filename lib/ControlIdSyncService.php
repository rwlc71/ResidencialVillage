<?php
/**
 * Serviço de sincronização cadastral ResidencialVillage <-> Control iD
 * Mantém base comum de proprietários e dependentes no equipamento biométrico.
 */

require_once dirname(__FILE__) . '/ControlIdClient.php';
require_once dirname(__FILE__) . '/IDSecureClient.php';
require_once dirname(__FILE__) . '/WampIdSecureClient.php';

class ControlIdSyncService
{
    private $client;
    private $config;
    private $con;

    public function __construct($con, array $config = null)
    {
        $this->con = $con ? $con : (isset($GLOBALS['con']) ? $GLOBALS['con'] : null);
        if ($config === null) {
            $config = include dirname(__FILE__) . '/../config/controlid.php';
        }
        $this->config = self::resolverHost($config);
        $tipo = isset($this->config['tipo']) ? $this->config['tipo'] : 'idsecure';
        $destino = isset($this->config['destino']) ? $this->config['destino'] : 'remoto';

        if ($tipo === 'idsecure') {
            $this->client = new IDSecureClient($this->config);
        } else {
            $this->client = new ControlIdClient($this->config);
        }
        $this->ensureTables();
    }

    /**
     * Destino remoto: IPv4 público da máquina do IDCONTROL (sem path ResidencialVillage).
     */
    public static function resolverHost(array $config)
    {
        $publico = isset($config['host_publico']) && $config['host_publico'] !== ''
            ? $config['host_publico']
            : '45.71.177.247';
        $local = isset($config['host_local']) && $config['host_local'] !== ''
            ? $config['host_local']
            : '127.0.0.1';
        $lan = isset($config['host_lan']) ? trim($config['host_lan']) : '';
        $destino = isset($config['destino']) ? $config['destino'] : 'remoto';

        if ($destino === 'local') {
            $config['host'] = $local;
            $config['host_resolvido_origem'] = 'local';
        } elseif ($destino === 'lan' && $lan !== '') {
            $config['host'] = $lan;
            $config['host_resolvido_origem'] = 'lan';
        } else {
            $config['host'] = $publico;
            $config['host_resolvido_origem'] = 'publico';
            $destino = 'remoto';
        }

        $config['destino'] = $destino;
        $config['host_publico'] = $publico;
        $config['host_local'] = $local;
        $config['host_lan'] = $lan;
        if (empty($config['port'])) {
            $config['port'] = 30443;
        }
        $config['use_https'] = !isset($config['use_https']) ? true : (bool) $config['use_https'];
        return $config;
    }

    private function q($sql)
    {
        if ($this->con) {
            return mysql_query($sql, $this->con);
        }
        return mysql_query($sql);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function ensureTables()
    {
        $this->q("CREATE TABLE IF NOT EXISTS controlid_vinculo (
            id INT NOT NULL AUTO_INCREMENT,
            tipo_origem VARCHAR(20) NOT NULL,
            id_origem INT NOT NULL,
            documento VARCHAR(20) NOT NULL,
            nome VARCHAR(150) NOT NULL,
            controlid_user_id BIGINT NULL,
            registration VARCHAR(40) NOT NULL,
            status_sync VARCHAR(20) NOT NULL DEFAULT 'pendente',
            msg_sync TEXT NULL,
            biometria_cadastrada TINYINT(1) NOT NULL DEFAULT 0,
            dthr_sync DATETIME NULL,
            dthr_criacao DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uk_origem (tipo_origem, id_origem),
            KEY idx_documento (documento),
            KEY idx_registration (registration)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->q("CREATE TABLE IF NOT EXISTS controlid_sync_log (
            id INT NOT NULL AUTO_INCREMENT,
            acao VARCHAR(40) NOT NULL,
            tipo_origem VARCHAR(20) NULL,
            id_origem INT NULL,
            registration VARCHAR(40) NULL,
            sucesso TINYINT(1) NOT NULL DEFAULT 0,
            mensagem TEXT NULL,
            detalhe TEXT NULL,
            usuario_sistema VARCHAR(40) NULL,
            dthr DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY idx_dthr (dthr)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    }

    public function testConnection()
    {
        $result = $this->client->testConnection();
        if (!is_array($result)) {
            $result = array('success' => false, 'message' => 'Falha inesperada no teste.');
        }
        $result['destino'] = isset($this->config['destino']) ? $this->config['destino'] : 'remoto';
        $result['host_publico'] = isset($this->config['host_publico']) ? $this->config['host_publico'] : '';
        $this->log('teste_conexao', null, null, null, !empty($result['success']), isset($result['message']) ? $result['message'] : '', json_encode($result));
        return $result;
    }

    public function syncAll($usuarioSistema = '')
    {
        $stats = array(
            'proprietarios_ok' => 0,
            'proprietarios_erro' => 0,
            'dependentes_ok' => 0,
            'dependentes_erro' => 0,
            'erros' => array()
        );

        $teste = $this->client->testConnection();
        if (empty($teste['success'])) {
            $stats['erros'][] = $teste['message'];
            $this->log('sync_all', null, null, null, false, $teste['message'], null, $usuarioSistema);
            return $stats;
        }

        $rs = $this->q("SELECT id_proprietario, CPF, nome FROM proprietario ORDER BY nome");
        while ($rs && ($row = mysql_fetch_assoc($rs))) {
            $r = $this->syncProprietario($row['id_proprietario'], $usuarioSistema);
            if (!empty($r['success'])) {
                $stats['proprietarios_ok']++;
            } else {
                $stats['proprietarios_erro']++;
                $stats['erros'][] = 'Prop ' . $row['nome'] . ': ' . $r['message'];
            }
        }

        $rs2 = $this->q("SELECT id_dependente, id_proprietario, nome_dependente, doc_indentificacao_dependente
                            FROM dependente ORDER BY nome_dependente");
        while ($rs2 && ($row = mysql_fetch_assoc($rs2))) {
            $r = $this->syncDependente($row['id_dependente'], $usuarioSistema);
            if (!empty($r['success'])) {
                $stats['dependentes_ok']++;
            } else {
                $stats['dependentes_erro']++;
                $stats['erros'][] = 'Dep ' . $row['nome_dependente'] . ': ' . $r['message'];
            }
        }

        $ok = ($stats['proprietarios_erro'] + $stats['dependentes_erro']) === 0;
        $msg = sprintf(
            'Sync concluído. Proprietários OK:%d Erro:%d | Dependentes OK:%d Erro:%d',
            $stats['proprietarios_ok'],
            $stats['proprietarios_erro'],
            $stats['dependentes_ok'],
            $stats['dependentes_erro']
        );
        $this->log('sync_all', null, null, null, $ok, $msg, json_encode($stats), $usuarioSistema);
        $stats['message'] = $msg;
        $stats['success'] = $ok;
        return $stats;
    }

    public function syncProprietario($idProprietario, $usuarioSistema = '')
    {
        $idProprietario = (int) $idProprietario;
        $rs = $this->q("SELECT id_proprietario, CPF, nome FROM proprietario WHERE id_proprietario = " . $idProprietario);
        $row = $rs ? mysql_fetch_assoc($rs) : null;
        if (!$row) {
            return array('success' => false, 'message' => 'Proprietário não encontrado.');
        }
        $doc = preg_replace('/\D/', '', $row['CPF']);
        return $this->upsertUser('proprietario', $idProprietario, $doc, $row['nome'], $usuarioSistema);
    }

    public function syncDependente($idDependente, $usuarioSistema = '')
    {
        $idDependente = (int) $idDependente;
        $rs = $this->q("SELECT id_dependente, nome_dependente, doc_indentificacao_dependente
                           FROM dependente WHERE id_dependente = " . $idDependente);
        $row = $rs ? mysql_fetch_assoc($rs) : null;
        if (!$row) {
            return array('success' => false, 'message' => 'Dependente não encontrado.');
        }
        $doc = preg_replace('/\D/', '', $row['doc_indentificacao_dependente']);
        if ($doc === '') {
            $doc = 'D' . $idDependente;
        }
        return $this->upsertUser('dependente', $idDependente, $doc, $row['nome_dependente'], $usuarioSistema);
    }

    private function upsertUser($tipo, $idOrigem, $documento, $nome, $usuarioSistema)
    {
        $prefix = isset($this->config['registration_prefix']) ? $this->config['registration_prefix'] : '';
        $registration = $prefix . $documento;
        if ($registration === '') {
            return array('success' => false, 'message' => 'Documento/matrícula vazio.');
        }

        if (!$this->client->ensureSession()) {
            $msg = $this->client->getLastError() ?: 'Sem sessão no equipamento.';
            $this->saveVinculo($tipo, $idOrigem, $documento, $nome, null, $registration, 'erro', $msg);
            $this->log('upsert', $tipo, $idOrigem, $registration, false, $msg, null, $usuarioSistema);
            return array('success' => false, 'message' => $msg);
        }

        $existente = $this->client->findUserByRegistration($registration);
        if ($existente === false) {
            $msg = $this->client->getLastError() ?: 'Falha ao consultar usuário no equipamento.';
            $this->saveVinculo($tipo, $idOrigem, $documento, $nome, null, $registration, 'erro', $msg);
            $this->log('upsert', $tipo, $idOrigem, $registration, false, $msg, null, $usuarioSistema);
            return array('success' => false, 'message' => $msg);
        }

        $userId = null;
        if ($existente !== null) {
            $userId = $this->resolveUserId($existente);
            $ok = $this->client->modifyUsers(
                array('name' => $nome, 'registration' => $registration),
                array('id' => (int) $userId)
            );
            if (!$ok) {
                $msg = $this->client->getLastError() ?: 'Falha ao atualizar usuário no iDSecure/equipamento.';
                $this->saveVinculo($tipo, $idOrigem, $documento, $nome, $userId, $registration, 'erro', $msg);
                $this->log('update', $tipo, $idOrigem, $registration, false, $msg, null, $usuarioSistema);
                return array('success' => false, 'message' => $msg);
            }
            $acao = 'update';
        } else {
            $ids = $this->client->createUsers(array(array(
                'registration' => $registration,
                'name' => $nome
            )));
            if ($ids === false || !isset($ids[0])) {
                $msg = $this->client->getLastError() ?: 'Falha ao criar usuário no iDSecure/equipamento.';
                $this->saveVinculo($tipo, $idOrigem, $documento, $nome, null, $registration, 'erro', $msg);
                $this->log('create', $tipo, $idOrigem, $registration, false, $msg, null, $usuarioSistema);
                return array('success' => false, 'message' => $msg);
            }
            $userId = $ids[0];
            $acao = 'create';
        }

        $this->saveVinculo($tipo, $idOrigem, $documento, $nome, $userId, $registration, 'sincronizado', 'OK');
        $this->log($acao, $tipo, $idOrigem, $registration, true, 'Sincronizado com sucesso', 'user_id=' . $userId, $usuarioSistema);

        return array(
            'success' => true,
            'message' => 'Sincronizado com sucesso.',
            'controlid_user_id' => $userId,
            'registration' => $registration,
            'acao' => $acao
        );
    }

    public function solicitarBiometria($tipo, $idOrigem, $usuarioSistema = '')
    {
        $tipoEsc = mysql_real_escape_string($tipo);
        $idOrigem = (int) $idOrigem;
        $rs = $this->q("SELECT * FROM controlid_vinculo WHERE tipo_origem = '$tipoEsc' AND id_origem = $idOrigem");
        $vinculo = $rs ? mysql_fetch_assoc($rs) : null;

        if (!$vinculo || empty($vinculo['controlid_user_id'])) {
            if ($tipo === 'proprietario') {
                $sync = $this->syncProprietario($idOrigem, $usuarioSistema);
            } else {
                $sync = $this->syncDependente($idOrigem, $usuarioSistema);
            }
            if (empty($sync['success'])) {
                return $sync;
            }
            $rs = $this->q("SELECT * FROM controlid_vinculo WHERE tipo_origem = '$tipoEsc' AND id_origem = $idOrigem");
            $vinculo = $rs ? mysql_fetch_assoc($rs) : null;
        }

        if (!$vinculo || empty($vinculo['controlid_user_id'])) {
            return array('success' => false, 'message' => 'Vínculo sem ID no Control iD.');
        }

        $resp = $this->client->remoteEnroll((int) $vinculo['controlid_user_id'], 'biometry');
        if ($resp === false) {
            $msg = $this->client->getLastError() ?: 'Falha ao iniciar captura biométrica.';
            $this->log('remote_enroll', $tipo, $idOrigem, $vinculo['registration'], false, $msg, null, $usuarioSistema);
            return array('success' => false, 'message' => $msg);
        }

        $this->q("UPDATE controlid_vinculo SET biometria_cadastrada = 1, msg_sync = 'Biometria solicitada no equipamento', dthr_sync = NOW()
                     WHERE id = " . (int) $vinculo['id']);
        $this->log('remote_enroll', $tipo, $idOrigem, $vinculo['registration'], true, 'Captura biométrica iniciada no equipamento', json_encode($resp), $usuarioSistema);

        return array(
            'success' => true,
            'message' => 'Captura biométrica iniciada. Oriente a pessoa a posicionar o dedo/rosto no equipamento.',
            'device_response' => $resp
        );
    }

    public function compararBases()
    {
        $deviceUsers = $this->client->loadUsers();
        if ($deviceUsers === false) {
            return array(
                'success' => false,
                'message' => $this->client->getLastError() ?: 'Não foi possível carregar usuários do equipamento.'
            );
        }

        $deviceByReg = array();
        foreach ($deviceUsers as $u) {
            $reg = isset($u['registration']) ? preg_replace('/\D/', '', $u['registration']) : '';
            if ($reg !== '') {
                $deviceByReg[$reg] = $u;
            } elseif (!empty($u['registration'])) {
                $deviceByReg[$u['registration']] = $u;
            }
        }

        $locais = array();
        $rs = $this->q("SELECT id_proprietario AS id, 'proprietario' AS tipo, CPF AS documento, nome FROM proprietario");
        while ($rs && ($r = mysql_fetch_assoc($rs))) {
            $doc = preg_replace('/\D/', '', $r['documento']);
            $locais[] = array(
                'tipo' => 'proprietario',
                'id' => $r['id'],
                'documento' => $doc,
                'nome' => $r['nome'],
                'no_dispositivo' => isset($deviceByReg[$doc]),
                'device_user' => isset($deviceByReg[$doc]) ? $deviceByReg[$doc] : null
            );
        }

        $rs2 = $this->q("SELECT id_dependente AS id, 'dependente' AS tipo, doc_indentificacao_dependente AS documento, nome_dependente AS nome FROM dependente");
        while ($rs2 && ($r = mysql_fetch_assoc($rs2))) {
            $doc = preg_replace('/\D/', '', $r['documento']);
            if ($doc === '') {
                $doc = 'D' . $r['id'];
            }
            $locais[] = array(
                'tipo' => 'dependente',
                'id' => $r['id'],
                'documento' => $doc,
                'nome' => $r['nome'],
                'no_dispositivo' => isset($deviceByReg[$doc]) || isset($deviceByReg['D' . $r['id']]),
                'device_user' => isset($deviceByReg[$doc])
                    ? $deviceByReg[$doc]
                    : (isset($deviceByReg['D' . $r['id']]) ? $deviceByReg['D' . $r['id']] : null)
            );
        }

        $soNoDispositivo = array();
        $docsLocais = array();
        foreach ($locais as $l) {
            $docsLocais[$l['documento']] = true;
        }
        foreach ($deviceByReg as $reg => $u) {
            if (!isset($docsLocais[$reg])) {
                $soNoDispositivo[] = $u;
            }
        }

        $pendentes = 0;
        foreach ($locais as $l) {
            if (!$l['no_dispositivo']) {
                $pendentes++;
            }
        }

        return array(
            'success' => true,
            'total_local' => count($locais),
            'total_dispositivo' => count($deviceUsers),
            'pendentes_envio' => $pendentes,
            'somente_dispositivo' => count($soNoDispositivo),
            'locais' => $locais,
            'somente_no_dispositivo' => $soNoDispositivo
        );
    }

    public function listarVinculos($limit = 200)
    {
        $limit = (int) $limit;
        $out = array();
        $rs = $this->q("SELECT * FROM controlid_vinculo ORDER BY dthr_sync DESC, id DESC LIMIT $limit");
        while ($rs && ($r = mysql_fetch_assoc($rs))) {
            $out[] = $r;
        }
        return $out;
    }

    public function listarLogs($limit = 50)
    {
        $limit = (int) $limit;
        $out = array();
        $rs = $this->q("SELECT * FROM controlid_sync_log ORDER BY id DESC LIMIT $limit");
        while ($rs && ($r = mysql_fetch_assoc($rs))) {
            $out[] = $r;
        }
        return $out;
    }

    private function resolveUserId(array $user)
    {
        if (!empty($user['idDevice'])) {
            return $user['idDevice'];
        }
        if (!empty($user['id'])) {
            return $user['id'];
        }
        if (!empty($user['Id'])) {
            return $user['Id'];
        }
        return null;
    }

    private function saveVinculo($tipo, $idOrigem, $documento, $nome, $userId, $registration, $status, $msg)
    {
        $tipo = mysql_real_escape_string($tipo);
        $idOrigem = (int) $idOrigem;
        $documento = mysql_real_escape_string($documento);
        $nome = mysql_real_escape_string($nome);
        $registration = mysql_real_escape_string($registration);
        $status = mysql_real_escape_string($status);
        $msg = mysql_real_escape_string($msg);
        $userSql = $userId === null ? 'NULL' : (int) $userId;

        $exists = $this->q("SELECT id FROM controlid_vinculo WHERE tipo_origem = '$tipo' AND id_origem = $idOrigem");
        if ($exists && mysql_num_rows($exists) > 0) {
            $this->q("UPDATE controlid_vinculo SET
                documento = '$documento',
                nome = '$nome',
                controlid_user_id = $userSql,
                registration = '$registration',
                status_sync = '$status',
                msg_sync = '$msg',
                dthr_sync = NOW()
                WHERE tipo_origem = '$tipo' AND id_origem = $idOrigem");
        } else {
            $this->q("INSERT INTO controlid_vinculo
                (tipo_origem, id_origem, documento, nome, controlid_user_id, registration, status_sync, msg_sync, biometria_cadastrada, dthr_sync, dthr_criacao)
                VALUES ('$tipo', $idOrigem, '$documento', '$nome', $userSql, '$registration', '$status', '$msg', 0, NOW(), NOW())");
        }
    }

    private function log($acao, $tipo, $idOrigem, $registration, $sucesso, $mensagem, $detalhe = null, $usuario = '')
    {
        $acao = mysql_real_escape_string($acao);
        $tipoSql = $tipo === null ? 'NULL' : "'" . mysql_real_escape_string($tipo) . "'";
        $idSql = $idOrigem === null ? 'NULL' : (int) $idOrigem;
        $regSql = $registration === null ? 'NULL' : "'" . mysql_real_escape_string($registration) . "'";
        $sucesso = $sucesso ? 1 : 0;
        $mensagem = mysql_real_escape_string($mensagem);
        $detalhe = $detalhe === null ? 'NULL' : "'" . mysql_real_escape_string($detalhe) . "'";
        $usuario = mysql_real_escape_string($usuario);

        $this->q("INSERT INTO controlid_sync_log
            (acao, tipo_origem, id_origem, registration, sucesso, mensagem, detalhe, usuario_sistema, dthr)
            VALUES ('$acao', $tipoSql, $idSql, $regSql, $sucesso, '$mensagem', $detalhe, '$usuario', NOW())");
    }
}
