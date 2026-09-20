<?php
/**
 * Endpoint JSON das ações do módulo Validar Biometria (Control iD)
 */
header('Content-Type: application/json; charset=utf-8');
session_name('SESSAO_PHP');
session_start();

include dirname(__FILE__) . '/../conexao.php';
include dirname(__FILE__) . '/../valida/verifica_autenticacao.php';
require_once dirname(__FILE__) . '/../lib/ControlIdSyncService.php';

$tipoAcesso = isset($_COOKIE['tipo_acesso']) ? $_COOKIE['tipo_acesso'] : '';
$usuario = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : '';

$permitidos = array('adm', 'sup', 'seg', 'master', 'cord');
if (!in_array($tipoAcesso, $permitidos, true)) {
    echo json_encode(array('success' => false, 'message' => 'Acesso não autorizado a este módulo.'));
    return;
}

$acao = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';
$service = new ControlIdSyncService($con);
$config = $service->getConfig();

try {
    switch ($acao) {
        case 'testar':
            echo json_encode($service->testConnection());
            break;

        case 'sincronizar_todos':
            echo json_encode($service->syncAll($usuario));
            break;

        case 'sincronizar_um':
            $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            if ($tipo === 'proprietario') {
                echo json_encode($service->syncProprietario($id, $usuario));
            } elseif ($tipo === 'dependente') {
                echo json_encode($service->syncDependente($id, $usuario));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Tipo inválido.'));
            }
            break;

        case 'solicitar_biometria':
            $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            echo json_encode($service->solicitarBiometria($tipo, $id, $usuario));
            break;

        case 'comparar':
            echo json_encode($service->compararBases());
            break;

        case 'listar_vinculos':
            echo json_encode(array(
                'success' => true,
                'vinculos' => $service->listarVinculos(300)
            ));
            break;

        case 'listar_logs':
            echo json_encode(array(
                'success' => true,
                'logs' => $service->listarLogs(80)
            ));
            break;

        case 'config_resumo':
            echo json_encode(array(
                'success' => true,
                'tipo' => isset($config['tipo']) ? $config['tipo'] : 'equipamento',
                'host' => $config['host'],
                'host_publico' => isset($config['host_publico']) ? $config['host_publico'] : '',
                'host_local' => isset($config['host_local']) ? $config['host_local'] : '',
                'host_lan' => isset($config['host_lan']) ? $config['host_lan'] : '',
                'host_resolvido_origem' => isset($config['host_resolvido_origem']) ? $config['host_resolvido_origem'] : '',
                'port' => $config['port'],
                'modo' => $config['modo'],
                'use_https' => !empty($config['use_https']),
                'auto_sync_proprietario' => !empty($config['auto_sync_proprietario']),
                'docs' => 'https://www.controlid.com.br/docs/idsecure-pt/'
            ));
            break;

        case 'salvar_config':
            // Atualiza host/porta/login/senha/modo/tipo no arquivo de config
            if (!in_array($tipoAcesso, array('adm', 'sup', 'master'), true)) {
                echo json_encode(array('success' => false, 'message' => 'Somente administrativo pode alterar a configuração.'));
                break;
            }
            $path = dirname(__FILE__) . '/../config/controlid.php';
            $cfg = include $path;
            if (isset($_POST['tipo']) && in_array($_POST['tipo'], array('idsecure', 'equipamento'), true)) {
                $cfg['tipo'] = $_POST['tipo'];
                if ($_POST['tipo'] === 'idsecure') {
                    if (empty($cfg['port']) || (int) $cfg['port'] === 80) {
                        $cfg['port'] = 30443;
                    }
                    if (!isset($_POST['use_https'])) {
                        $cfg['use_https'] = true;
                    }
                }
            }
            if (isset($_POST['destino']) && in_array($_POST['destino'], array('remoto', 'local', 'lan'), true)) {
                $cfg['destino'] = $_POST['destino'];
            }
            if (isset($_POST['host_publico'])) {
                $cfg['host_publico'] = trim($_POST['host_publico']);
            }
            if (isset($_POST['host_local'])) {
                $cfg['host_local'] = trim($_POST['host_local']);
            }
            if (isset($_POST['host_lan'])) {
                $cfg['host_lan'] = trim($_POST['host_lan']);
            }
            if (isset($_POST['host']) && !isset($_POST['host_publico'])) {
                $cfg['host_publico'] = trim($_POST['host']);
            }
            if (isset($_POST['port'])) {
                $cfg['port'] = (int) $_POST['port'];
            }
            if (isset($_POST['login']) && $_POST['login'] !== '') {
                $cfg['login'] = trim($_POST['login']);
            }
            if (isset($_POST['password']) && $_POST['password'] !== '') {
                $cfg['password'] = $_POST['password'];
            }
            if (isset($_POST['modo']) && in_array($_POST['modo'], array('standalone', 'online'), true)) {
                $cfg['modo'] = $_POST['modo'];
            }
            if (isset($_POST['use_https'])) {
                $cfg['use_https'] = ($_POST['use_https'] === '1' || $_POST['use_https'] === 'true');
            }
            $export = var_export($cfg, true);
            $php = "<?php\n/** Configuração Control iD / iDSecure - atualizada em " . date('Y-m-d H:i:s') . " */\nreturn " . $export . ";\n";
            if (file_put_contents($path, $php) === false) {
                echo json_encode(array('success' => false, 'message' => 'Não foi possível gravar config/controlid.php (permissão?).'));
            } else {
                echo json_encode(array('success' => true, 'message' => 'Configuração salva.', 'config' => array(
                    'tipo' => isset($cfg['tipo']) ? $cfg['tipo'] : 'equipamento',
                    'host_publico' => isset($cfg['host_publico']) ? $cfg['host_publico'] : '',
                    'host_local' => isset($cfg['host_local']) ? $cfg['host_local'] : '',
                    'host_lan' => isset($cfg['host_lan']) ? $cfg['host_lan'] : '',
                    'port' => $cfg['port'],
                    'modo' => $cfg['modo'],
                    'use_https' => !empty($cfg['use_https'])
                )));
            }
            break;

        default:
            echo json_encode(array('success' => false, 'message' => 'Ação não reconhecida.'));
    }
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => 'Erro interno: ' . $e->getMessage()));
}
