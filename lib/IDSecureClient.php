<?php
/**
 * Cliente HTTP para API REST do software iDSecure (Control iD).
 * Host resolvido em ControlIdSyncService (local 127.0.0.1 ou WAN 45.71.177.247).
 *
 * Diferente da API .fcgi do equipamento físico.
 * Ref: https://www.controlid.com.br/docs/idsecure-pt/
 */
class IDSecureClient
{
    private $baseUrl;
    private $login;
    private $password;
    private $timeout;
    private $token = null;
    private $lastError = '';
    private $lastHttpCode = 0;
    private $lastCurlError = '';
    private $lastCurlErrno = 0;
    private $host;
    private $port;

    public function __construct(array $config)
    {
        $scheme = !empty($config['use_https']) ? 'https' : 'http';
        $host = isset($config['host']) ? $config['host'] : 'localhost';
        $port = isset($config['port']) ? (int) $config['port'] : 30443;
        $this->baseUrl = $scheme . '://' . $host . ':' . $port;
        $this->host = $host;
        $this->port = $port;
        $this->login = isset($config['login']) ? $config['login'] : 'admin';
        $this->password = isset($config['password']) ? $config['password'] : 'admin';
        $this->timeout = isset($config['timeout']) ? (int) $config['timeout'] : 20;
    }

    public function getLastError()
    {
        return $this->lastError;
    }

    public function getLastHttpCode()
    {
        return $this->lastHttpCode;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getSession()
    {
        return $this->token;
    }

    /**
     * Autentica e obtém JWT (accessToken).
     */
    public function login()
    {
        // Formatos comuns usados pelas versões do iDSecure
        $payloads = array(
            array('UserName' => $this->login, 'Password' => $this->password),
            array('username' => $this->login, 'password' => $this->password),
            array('Login' => $this->login, 'Password' => $this->password),
            array('login' => $this->login, 'password' => $this->password)
        );

        $endpoints = array('api/login/', 'api/login', 'api/Login/');
        $lastMsg = '';

        foreach ($endpoints as $ep) {
            foreach ($payloads as $body) {
                $resp = $this->request('POST', $ep, $body, false);
                if ($resp === false) {
                    $lastMsg = $this->lastError;
                    continue;
                }
                $token = $this->extractToken($resp);
                if ($token) {
                    $this->token = $token;
                    return $this->token;
                }
                $lastMsg = 'Login sem token na resposta: ' . substr(json_encode($resp), 0, 180);
            }
        }

        $this->lastError = $lastMsg ?: 'Falha ao autenticar no iDSecure. Verifique usuário/senha e se o serviço está em ' . $this->baseUrl;
        return false;
    }

    public function ensureSession()
    {
        if ($this->token) {
            return true;
        }
        return (bool) $this->login();
    }

    public function testConnection()
    {
        $tcp = $this->probeTcp();
        $ok = $this->login();
        $solucoes = $this->montarSolucoes($ok, $tcp);

        if (!$ok) {
            return array(
                'success' => false,
                'message' => $this->lastError ?: 'Falha ao autenticar no iDSecure remoto.',
                'base_url' => $this->baseUrl,
                'host' => $this->host,
                'port' => $this->port,
                'tcp_ok' => !empty($tcp['ok']),
                'tcp_detalhe' => isset($tcp['detalhe']) ? $tcp['detalhe'] : '',
                'http_code' => $this->lastHttpCode,
                'curl_error' => $this->lastCurlError,
                'solucoes' => $solucoes
            );
        }

        $users = $this->loadUsers(0, 1);
        $listOk = ($users !== false);

        return array(
            'success' => true,
            'message' => $listOk
                ? 'Conexão remota com iDSecure OK (login + API de usuários) em ' . $this->baseUrl
                : 'Login OK, mas a listagem de usuários falhou: ' . $this->lastError,
            'base_url' => $this->baseUrl,
            'host' => $this->host,
            'port' => $this->port,
            'tcp_ok' => !empty($tcp['ok']),
            'session' => substr($this->token, 0, 20) . '...',
            'listagem_usuarios' => $listOk,
            'solucoes' => $solucoes
        );
    }

    /**
     * Testa se a porta TCP do iDSecure responde no IP remoto.
     */
    public function probeTcp()
    {
        $errno = 0;
        $errstr = '';
        $fp = @fsockopen($this->host, $this->port, $errno, $errstr, 8);
        if ($fp) {
            fclose($fp);
            return array('ok' => true, 'detalhe' => 'Porta ' . $this->port . ' aberta em ' . $this->host);
        }
        return array(
            'ok' => false,
            'detalhe' => 'TCP recusado/timeout em ' . $this->host . ':' . $this->port . ' (' . $errno . ' ' . $errstr . ')'
        );
    }

    private function montarSolucoes($loginOk, $tcp)
    {
        $itens = array();
        $tcpOk = !empty($tcp['ok']);

        if ($loginOk) {
            return $itens;
        }

        if (!$tcpOk) {
            $itens[] = 'No roteador do condomínio (Link Direct Optic), crie redirecionamento (NAT/port forward) da porta 30443 TCP para o IP LAN do PC que roda o IDCONTROL/iDSecure.';
            $itens[] = 'No Windows do PC remoto: Firewall → permitir entrada TCP 30443 (e o executável do iDSecure).';
            $itens[] = 'O iDSecure precisa escutar em 0.0.0.0 (todas as interfaces), não só em 127.0.0.1. Confirme no PC remoto acessando https://IP-LAN:30443/#/ a partir de outro computador da mesma rede.';
            $itens[] = 'Confirme que o iDSecure está em execução no PC da portaria (ícone / serviço).';
            $itens[] = 'Se o IP 45.71.177.247 for dinâmico, ele pode ter mudado. Confira de novo em MyIPAddress.com no PC do condomínio.';
            $itens[] = 'Alguns ISPs bloqueiam portas de entrada. Se o forward não funcionar, use VPN (Tailscale/ZeroTier/OpenVPN) até o PC da portaria e conecte no IP da VPN.';
            $itens[] = 'Alternativa estável: hospedar um túnel (ngrok, Cloudflare Tunnel) apontando para https://127.0.0.1:30443 no PC do iDSecure.';
        } else {
            $itens[] = 'A porta 30443 está aberta, mas o login da API falhou. Confira usuário e senha do operador no iDSecure (não necessariamente admin/admin).';
            $itens[] = 'Abra no navegador https://' . $this->host . ':' . $this->port . '/#/login e valide o certificado autoassinado (Avançado → continuar).';
            $itens[] = 'Se o painel abrir mas a API recusar, a versão do iDSecure pode usar outro path de login — envie a mensagem de erro para ajustarmos o endpoint.';
        }

        return $itens;
    }

    /**
     * Lista pessoas/usuários do iDSecure.
     */
    public function loadUsers($start = 0, $limit = 1000)
    {
        if (!$this->ensureSession()) {
            return false;
        }

        $paths = array(
            'api/users?start=' . (int) $start . '&limit=' . (int) $limit,
            'api/users/?start=' . (int) $start . '&limit=' . (int) $limit,
            'api/persons?start=' . (int) $start . '&limit=' . (int) $limit,
            'api/users'
        );

        foreach ($paths as $path) {
            $resp = $this->request('GET', $path, null, true);
            if ($resp === false) {
                continue;
            }
            $list = $this->normalizeUserList($resp);
            if ($list !== null) {
                return $list;
            }
        }

        $this->lastError = $this->lastError ?: 'Não foi possível listar usuários no iDSecure.';
        return false;
    }

    public function findUserByRegistration($registration)
    {
        $users = $this->loadUsers();
        if ($users === false) {
            return false;
        }
        $target = preg_replace('/\D/', '', (string) $registration);
        foreach ($users as $u) {
            $reg = '';
            if (!empty($u['registration'])) {
                $reg = preg_replace('/\D/', '', $u['registration']);
            } elseif (!empty($u['cpf'])) {
                $reg = preg_replace('/\D/', '', $u['cpf']);
            } elseif (!empty($u['Registration'])) {
                $reg = preg_replace('/\D/', '', $u['Registration']);
            }
            if ($reg !== '' && $reg === $target) {
                return $u;
            }
            // matrícula sintética D123
            if (!empty($u['registration']) && (string) $u['registration'] === (string) $registration) {
                return $u;
            }
        }
        return null;
    }

    /**
     * Cria pessoa no iDSecure. Retorna array de ids (compatível com SyncService).
     */
    public function createUsers(array $values)
    {
        if (!$this->ensureSession()) {
            return false;
        }

        $ids = array();
        foreach ($values as $value) {
            $payload = $this->mapUserPayload($value);
            $created = false;
            foreach (array('api/users/', 'api/users', 'api/persons/', 'api/persons') as $path) {
                $resp = $this->request('POST', $path, $payload, true);
                if ($resp === false) {
                    continue;
                }
                $id = $this->extractCreatedId($resp);
                if ($id !== null) {
                    $ids[] = $id;
                    $created = true;
                    break;
                }
                // algumas versões retornam o objeto criado ou 200 vazio
                if ($this->lastHttpCode >= 200 && $this->lastHttpCode < 300) {
                    $ids[] = isset($resp['id']) ? $resp['id'] : 0;
                    $created = true;
                    break;
                }
            }
            if (!$created) {
                $this->lastError = $this->lastError ?: 'Falha ao criar usuário no iDSecure.';
                return false;
            }
        }
        return $ids;
    }

    /**
     * Atualiza pessoa no iDSecure.
     */
    public function modifyUsers(array $values, array $where)
    {
        if (!$this->ensureSession()) {
            return false;
        }
        $id = isset($where['id']) ? (int) $where['id'] : 0;
        if ($id <= 0 && isset($where['idDevice'])) {
            $id = (int) $where['idDevice'];
        }
        if ($id <= 0) {
            $this->lastError = 'ID do usuário iDSecure não informado para atualização.';
            return false;
        }

        $payload = $this->mapUserPayload($values);
        $payload['id'] = $id;

        foreach (array(
            'api/users/' . $id,
            'api/users/' . $id . '/',
            'api/persons/' . $id,
            'api/users'
        ) as $path) {
            $method = (strpos($path, (string) $id) !== false) ? 'PUT' : 'POST';
            $resp = $this->request($method, $path, $payload, true);
            if ($resp !== false && $this->lastHttpCode >= 200 && $this->lastHttpCode < 300) {
                return true;
            }
            // fallback PATCH
            if (strpos($path, (string) $id) !== false) {
                $resp = $this->request('PATCH', $path, $payload, true);
                if ($resp !== false && $this->lastHttpCode >= 200 && $this->lastHttpCode < 300) {
                    return true;
                }
            }
        }

        $this->lastError = $this->lastError ?: 'Falha ao atualizar usuário no iDSecure.';
        return false;
    }

    public function destroyUsers(array $ids)
    {
        if (!$this->ensureSession() || empty($ids)) {
            return false;
        }
        foreach ($ids as $id) {
            $ok = false;
            foreach (array('api/users/' . (int) $id, 'api/users/' . (int) $id . '/', 'api/persons/' . (int) $id) as $path) {
                $resp = $this->request('DELETE', $path, null, true);
                if ($resp !== false && $this->lastHttpCode >= 200 && $this->lastHttpCode < 300) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                return false;
            }
        }
        return true;
    }

    /**
     * Cadastro biométrico remoto no iDSecure depende do dispositivo vinculado.
     * Mantido como stub informativo — a captura costuma ser feita no próprio painel.
     */
    public function remoteEnroll($userId, $type = 'biometry')
    {
        if (!$this->ensureSession()) {
            return false;
        }
        $payload = array(
            'userId' => (int) $userId,
            'idUser' => (int) $userId,
            'type' => $type
        );
        foreach (array('api/users/enroll', 'api/remote_enroll', 'api/biometry/enroll') as $path) {
            $resp = $this->request('POST', $path, $payload, true);
            if ($resp !== false && $this->lastHttpCode >= 200 && $this->lastHttpCode < 300) {
                return $resp;
            }
        }
        $this->lastError = 'A captura biométrica pelo iDSecure deve ser feita no painel '
            . '(Pessoas → Identificação) ou no equipamento. Endpoint de enroll remoto não disponível nesta versão.';
        return false;
    }

    public function systemInformation()
    {
        if (!$this->ensureSession()) {
            return false;
        }
        foreach (array('api/about', 'api/system', 'api/devices') as $path) {
            $resp = $this->request('GET', $path, null, true);
            if ($resp !== false) {
                return $resp;
            }
        }
        return array('product' => 'iDSecure', 'base_url' => $this->baseUrl);
    }

    private function mapUserPayload(array $value)
    {
        $registration = isset($value['registration']) ? (string) $value['registration'] : '';
        $name = isset($value['name']) ? (string) $value['name'] : '';
        $cpf = preg_replace('/\D/', '', $registration);
        if (strpos($registration, 'D') === 0) {
            $cpf = '';
        }

        $payload = array(
            'name' => $name,
            'registration' => $registration,
            'idType' => 0, // 0 = pessoa, 1 = visitante
            'inativo' => 0,
            'deleted' => 0
        );
        if ($cpf !== '' && (strlen($cpf) === 11 || strlen($cpf) === 14)) {
            $payload['cpf'] = $cpf;
        }
        if (!empty($value['email'])) {
            $payload['email'] = $value['email'];
        }
        if (!empty($value['phone']) || !empty($value['telefone'])) {
            $payload['phone'] = !empty($value['phone']) ? $value['phone'] : $value['telefone'];
        }
        return $payload;
    }

    private function extractToken($resp)
    {
        if (!is_array($resp)) {
            return null;
        }
        $keys = array('accessToken', 'AccessToken', 'token', 'Token', 'jwt', 'JWT', 'access_token');
        foreach ($keys as $k) {
            if (!empty($resp[$k]) && is_string($resp[$k])) {
                return $resp[$k];
            }
        }
        if (!empty($resp['data']) && is_array($resp['data'])) {
            return $this->extractToken($resp['data']);
        }
        return null;
    }

    private function extractCreatedId($resp)
    {
        if (!is_array($resp)) {
            return null;
        }
        if (isset($resp['id'])) {
            return $resp['id'];
        }
        if (isset($resp['idDevice'])) {
            return $resp['idDevice'];
        }
        if (isset($resp['ids'][0])) {
            return $resp['ids'][0];
        }
        if (isset($resp['data']['id'])) {
            return $resp['data']['id'];
        }
        return null;
    }

    private function normalizeUserList($resp)
    {
        if (!is_array($resp)) {
            return null;
        }
        if (isset($resp[0]) && is_array($resp[0])) {
            return $resp;
        }
        foreach (array('data', 'users', 'persons', 'items', 'result', 'aaData') as $key) {
            if (isset($resp[$key]) && is_array($resp[$key])) {
                // dataTables style
                if (isset($resp[$key][0]) || empty($resp[$key])) {
                    return $resp[$key];
                }
            }
        }
        // objeto único
        if (isset($resp['id']) || isset($resp['name'])) {
            return array($resp);
        }
        return null;
    }

    private function request($method, $endpoint, $body = null, $withAuth = true)
    {
        $this->lastError = '';
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        if (!function_exists('curl_init')) {
            $this->lastError = 'Extensão cURL do PHP não está habilitada.';
            return false;
        }

        $headers = array('Content-Type: application/json', 'Accept: application/json');
        if ($withAuth) {
            if (!$this->token) {
                $this->lastError = 'Token iDSecure não iniciado.';
                return false;
            }
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        $ch = curl_init($url);
        $opts = array(
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CONNECTTIMEOUT => $this->timeout,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        );
        if ($body !== null) {
            $opts[CURLOPT_POSTFIELDS] = json_encode($body);
        }
        curl_setopt_array($ch, $opts);

        $raw = curl_exec($ch);
        $this->lastHttpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->lastCurlErrno = curl_errno($ch);
        $this->lastCurlError = curl_error($ch);
        $err = $this->lastCurlError;
        curl_close($ch);

        if ($raw === false) {
            $this->lastError = 'Erro cURL: ' . $err . ' (' . $url . ')';
            return false;
        }

        $decoded = json_decode($raw, true);
        if ($this->lastHttpCode >= 400) {
            $msg = is_array($decoded)
                ? (isset($decoded['message']) ? $decoded['message'] : (isset($decoded['error']) ? $decoded['error'] : json_encode($decoded)))
                : substr($raw, 0, 200);
            $this->lastError = 'HTTP ' . $this->lastHttpCode . ' em ' . $endpoint . ': ' . (is_string($msg) ? $msg : json_encode($msg));
            return false;
        }

        if ($decoded === null) {
            if (trim($raw) === '' && $this->lastHttpCode >= 200 && $this->lastHttpCode < 300) {
                return array('ok' => true);
            }
            // token pode vir como texto puro em casos raros
            if (is_string($raw) && strlen($raw) > 20 && substr_count($raw, '.') === 2) {
                return array('accessToken' => trim($raw, "\" \n\r"));
            }
            $this->lastError = 'Resposta inválida de ' . $endpoint . ': ' . substr($raw, 0, 180);
            return false;
        }

        return $decoded;
    }
}
