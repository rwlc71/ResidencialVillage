<?php
/**
 * Cliente HTTP para API REST da linha de Controle de Acesso Control iD.
 * Ref: https://www.controlid.com.br/docs/access-api-pt/
 */
class ControlIdClient
{
    private $baseUrl;
    private $login;
    private $password;
    private $timeout;
    private $session = null;
    private $lastError = '';
    private $lastHttpCode = 0;

    public function __construct(array $config)
    {
        $scheme = !empty($config['use_https']) ? 'https' : 'http';
        $host = isset($config['host']) ? $config['host'] : '127.0.0.1';
        $port = isset($config['port']) ? (int) $config['port'] : 80;
        $this->baseUrl = $scheme . '://' . $host . ($port && $port != 80 && $port != 443 ? ':' . $port : '');
        $this->login = isset($config['login']) ? $config['login'] : 'admin';
        $this->password = isset($config['password']) ? $config['password'] : 'admin';
        $this->timeout = isset($config['timeout']) ? (int) $config['timeout'] : 15;
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    public function getLastHttpCode()
    {
        return $this->lastHttpCode;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Autentica no equipamento e guarda a sessão.
     */
    public function login()
    {
        $resp = $this->request('login.fcgi', array(
            'login' => $this->login,
            'password' => $this->password
        ), false);

        if ($resp === false) {
            return false;
        }
        if (empty($resp['session'])) {
            $this->lastError = 'Resposta de login sem sessão. Verifique usuário/senha.';
            return false;
        }
        $this->session = $resp['session'];
        return $this->session;
    }

    public function sessionIsValid()
    {
        if (!$this->session) {
            return false;
        }
        $resp = $this->request('session_is_valid.fcgi', array(
            'session' => $this->session
        ), false);
        return is_array($resp) && !empty($resp['session_is_valid']);
    }

    /**
     * Garante sessão ativa (login se necessário).
     */
    public function ensureSession()
    {
        if ($this->session && $this->sessionIsValid()) {
            return true;
        }
        return (bool) $this->login();
    }

    /**
     * Lista usuários do equipamento.
     */
    public function loadUsers($where = null)
    {
        if (!$this->ensureSession()) {
            return false;
        }
        $body = array('object' => 'users');
        if ($where !== null) {
            $body['where'] = array('users' => $where);
        }
        $resp = $this->request('load_objects.fcgi', $body);
        if ($resp === false) {
            return false;
        }
        return isset($resp['users']) ? $resp['users'] : array();
    }

    /**
     * Cria usuários no equipamento.
     * @param array $values lista de arrays com registration, name, (password opcional)
     * @return array|false ids criados
     */
    public function createUsers(array $values)
    {
        if (!$this->ensureSession()) {
            return false;
        }
        $resp = $this->request('create_objects.fcgi', array(
            'object' => 'users',
            'values' => $values
        ));
        if ($resp === false) {
            return false;
        }
        return isset($resp['ids']) ? $resp['ids'] : array();
    }

    /**
     * Atualiza usuários.
     */
    public function modifyUsers(array $values, array $where)
    {
        if (!$this->ensureSession()) {
            return false;
        }
        $resp = $this->request('modify_objects.fcgi', array(
            'object' => 'users',
            'values' => $values,
            'where' => array('users' => $where)
        ));
        return $resp !== false;
    }

    /**
     * Remove usuários pelo id no equipamento.
     */
    public function destroyUsers(array $ids)
    {
        if (!$this->ensureSession() || empty($ids)) {
            return false;
        }
        $resp = $this->request('destroy_objects.fcgi', array(
            'object' => 'users',
            'where' => array(
                'users' => array(
                    'id' => array('IN' => array_values($ids))
                )
            )
        ));
        return $resp !== false;
    }

    /**
     * Busca usuário pela matrícula (registration), tipicamente o CPF.
     */
    public function findUserByRegistration($registration)
    {
        $users = $this->loadUsers(array('registration' => (string) $registration));
        if ($users === false) {
            return false;
        }
        return !empty($users[0]) ? $users[0] : null;
    }

    /**
     * Inicia cadastro biométrico remoto no equipamento (digital/face conforme modelo).
     * O morador deve estar no leitor nesse momento.
     */
    public function remoteEnroll($userId, $type = 'biometry')
    {
        if (!$this->ensureSession()) {
            return false;
        }
        return $this->request('remote_enroll.fcgi', array(
            'type' => $type,
            'user_id' => (int) $userId,
            'save' => true
        ));
    }

    /**
     * Informações do dispositivo (quando disponível).
     */
    public function systemInformation()
    {
        if (!$this->ensureSession()) {
            return false;
        }
        return $this->request('system_information.fcgi', array());
    }

    /**
     * Teste rápido de conectividade + autenticidade.
     */
    public function testConnection()
    {
        $ok = $this->login();
        if (!$ok) {
            return array(
                'success' => false,
                'message' => $this->lastError ?: 'Falha ao autenticar no Control iD.',
                'base_url' => $this->baseUrl
            );
        }
        $info = $this->systemInformation();
        return array(
            'success' => true,
            'message' => 'Conexão estabelecida com o equipamento.',
            'base_url' => $this->baseUrl,
            'session' => $this->session,
            'device' => $info
        );
    }

    private function request($endpoint, array $body, $withSession = true)
    {
        $this->lastError = '';
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        if ($withSession) {
            if (!$this->session) {
                $this->lastError = 'Sessão não iniciada.';
                return false;
            }
            $url .= (strpos($url, '?') === false ? '?' : '&') . 'session=' . urlencode($this->session);
        }

        if (!function_exists('curl_init')) {
            $this->lastError = 'Extensão cURL do PHP não está habilitada.';
            return false;
        }

        $json = json_encode($body);
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS => $json,
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
            $this->lastError = 'Erro de comunicação cURL: ' . $err;
            return false;
        }

        $decoded = json_decode($raw, true);
        if ($decoded === null && trim($raw) !== '' && trim($raw) !== 'null') {
            // Alguns endpoints respondem vazio/OK
            if ($this->lastHttpCode >= 200 && $this->lastHttpCode < 300 && trim($raw) === '') {
                return array('ok' => true);
            }
            $this->lastError = 'Resposta inválida do equipamento (HTTP ' . $this->lastHttpCode . '): ' . substr($raw, 0, 200);
            return false;
        }

        if ($this->lastHttpCode >= 400) {
            $msg = isset($decoded['error']) ? $decoded['error'] : ('HTTP ' . $this->lastHttpCode);
            $this->lastError = is_string($msg) ? $msg : json_encode($msg);
            return false;
        }

        return is_array($decoded) ? $decoded : array('ok' => true);
    }
}
