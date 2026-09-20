<?php
/**
 * Cliente do WAMP local que chama a ponte no WAMP remoto.
 * O PHP remoto é quem fala com o iDSecure em 127.0.0.1:30443.
 */
class WampIdSecureClient
{
    private $bridgeUrl;
    private $token;
    private $timeout;
    private $lastError = '';
    private $lastHttpCode = 0;

    public function __construct(array $config)
    {
        $base = isset($config['wamp_remoto_url']) ? rtrim($config['wamp_remoto_url'], '/') : '';
        if ($base === '') {
            $host = isset($config['host_publico']) ? $config['host_publico'] : '45.71.177.247';
            $base = 'http://' . $host . '/ResidencialVillage';
        }
        $this->bridgeUrl = $base . '/api/controlid_bridge.php';
        $this->token = isset($config['wamp_remoto_token']) ? $config['wamp_remoto_token'] : '';
        $this->timeout = isset($config['timeout']) ? (int) $config['timeout'] : 35;
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    public function getBaseUrl()
    {
        return $this->bridgeUrl;
    }

    public function testConnection()
    {
        $ping = $this->call('ping');
        if ($ping === false) {
            return array(
                'success' => false,
                'message' => $this->lastError,
                'base_url' => $this->bridgeUrl,
                'tcp_ok' => false,
                'tcp_detalhe' => $this->lastError,
                'solucoes' => $this->solucoesWamp()
            );
        }

        $inner = $this->call('testConnection');
        if ($inner === false) {
            return array(
                'success' => false,
                'message' => 'WAMP remoto respondeu, mas a ponte falhou: ' . $this->lastError,
                'base_url' => $this->bridgeUrl,
                'tcp_ok' => true,
                'solucoes' => $this->solucoesIdsecure()
            );
        }

        if (is_array($inner) && isset($inner['success'])) {
            $inner['base_url'] = $this->bridgeUrl;
            $inner['message'] = 'Ponte WAMP OK. ' . (isset($inner['message']) ? $inner['message'] : '');
            if (empty($inner['success']) && empty($inner['solucoes'])) {
                $inner['solucoes'] = $this->solucoesIdsecure();
            }
            return $inner;
        }

        return array(
            'success' => true,
            'message' => 'Ponte WAMP e iDSecure local no PC remoto OK.',
            'base_url' => $this->bridgeUrl,
            'result' => $inner
        );
    }

    public function ensureSession()
    {
        $r = $this->call('ensureSession');
        return $r === true || $r === 1 || $r === '1';
    }

    public function loadUsers($start = 0, $limit = 1000)
    {
        $r = $this->call('loadUsers', array('start' => $start, 'limit' => $limit));
        return $r;
    }

    public function findUserByRegistration($registration)
    {
        return $this->call('findUserByRegistration', array('registration' => $registration));
    }

    public function createUsers(array $values)
    {
        return $this->call('createUsers', array('values' => $values));
    }

    public function modifyUsers(array $values, array $where)
    {
        $r = $this->call('modifyUsers', array('values' => $values, 'where' => $where));
        return $r ? true : false;
    }

    public function remoteEnroll($userId, $type = 'biometry')
    {
        return $this->call('remoteEnroll', array('user_id' => (int) $userId, 'type' => $type));
    }

    private function call($metodo, array $args = array())
    {
        $this->lastError = '';
        if (!function_exists('curl_init')) {
            $this->lastError = 'Extensão cURL do PHP não está habilitada neste WAMP.';
            return false;
        }

        $payload = json_encode(array(
            'token' => $this->token,
            'metodo' => $metodo,
            'args' => $args
        ));

        $ch = curl_init($this->bridgeUrl);
        curl_setopt_array($ch, array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'X-Bridge-Token: ' . $this->token
            ),
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));

        $raw = curl_exec($ch);
        $this->lastHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            $this->lastError = 'Não alcançou o WAMP remoto em ' . $this->bridgeUrl . ' — ' . $err;
            return false;
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            $this->lastError = 'WAMP remoto respondeu HTML/texto inesperado (HTTP ' . $this->lastHttpCode . '): ' . substr(strip_tags($raw), 0, 180);
            return false;
        }

        if ($metodo === 'testConnection' && isset($decoded['result'])) {
            return $decoded['result'];
        }

        if (empty($decoded['success'])) {
            $this->lastError = isset($decoded['message']) ? $decoded['message'] : 'Ponte remota retornou erro.';
            if ($metodo === 'findUserByRegistration' && array_key_exists('result', $decoded)) {
                return $decoded['result'];
            }
            return false;
        }

        return array_key_exists('result', $decoded) ? $decoded['result'] : $decoded;
    }

    private function solucoesWamp()
    {
        return array(
            'No PC do condomínio, o WAMP (Apache) precisa estar iniciado, com a pasta ResidencialVillage em c:\\wamp64\\www\\ResidencialVillage.',
            'No roteador, redirecione a porta 80 TCP (Apache) para o IP LAN desse PC. É mais simples do que abrir a porta 30443 do iDSecure.',
            'Libere a porta 80 no Firewall do Windows do PC remoto.',
            'Confirme no navegador desta máquina: ' . $this->bridgeUrl . ' — se não abrir, o Apache remoto ainda não está público.',
            'Se o WAMP remoto usar outra pasta ou porta (ex.: 8080), ajuste wamp_remoto_url na tela (ex.: http://45.71.177.247:8080/ResidencialVillage).',
            'Copie estes arquivos novos para o WAMP remoto: api/controlid_bridge.php, config/controlid.php (mesmo token), lib/IDSecureClient.php.',
            'Os dois computadores devem ter o mesmo wamp_remoto_token em config/controlid.php.'
        );
    }

    private function solucoesIdsecure()
    {
        return array(
            'O WAMP remoto foi alcançado. Agora o PHP de lá precisa falar com https://127.0.0.1:30443.',
            'No PC do condomínio, abra o iDSecure e teste no próprio PC: https://127.0.0.1:30443/#/login',
            'Confira usuário/senha do operador do iDSecure (os mesmos da tela Validar Biometria).',
            'Se o iDSecure não estiver em execução, a ponte WAMP sobe mas o login biométrico falha.'
        );
    }
}
