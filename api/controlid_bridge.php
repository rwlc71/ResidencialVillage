<?php
/**
 * Ponte WAMP no PC do condomínio.
 * Recebe chamadas do WAMP local e conversa com o iDSecure em 127.0.0.1:30443.
 *
 * Não usa cookie de sessão: autentica por token compartilhado em config/controlid.php.
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Headers: Content-Type, X-Bridge-Token');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    echo json_encode(array('ok' => true));
    return;
}

$config = include dirname(__FILE__) . '/../config/controlid.php';
$esperado = isset($config['wamp_remoto_token']) ? $config['wamp_remoto_token'] : '';

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!is_array($body)) {
    $body = $_POST;
}

$token = '';
if (!empty($body['token'])) {
    $token = $body['token'];
} elseif (!empty($_SERVER['HTTP_X_BRIDGE_TOKEN'])) {
    $token = $_SERVER['HTTP_X_BRIDGE_TOKEN'];
}

if ($esperado === '' || $token === '' || $token !== $esperado) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Token da ponte WAMP inválido. Use o mesmo wamp_remoto_token nos dois computadores.'
    ));
    return;
}

$metodo = isset($body['metodo']) ? $body['metodo'] : 'ping';
$args = isset($body['args']) && is_array($body['args']) ? $body['args'] : array();

if ($metodo === 'ping') {
    echo json_encode(array(
        'success' => true,
        'message' => 'Ponte WAMP no PC remoto OK. Este PHP vai falar com o iDSecure em 127.0.0.1:30443.',
        'result' => array(
            'php' => PHP_VERSION,
            'host' => isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '',
            'idsecure' => 'https://127.0.0.1:30443'
        )
    ));
    return;
}

require_once dirname(__FILE__) . '/../lib/IDSecureClient.php';

$cfgLocal = $config;
$cfgLocal['host'] = '127.0.0.1';
$cfgLocal['port'] = isset($config['port']) ? (int) $config['port'] : 30443;
$cfgLocal['use_https'] = true;
$client = new IDSecureClient($cfgLocal);

try {
    $result = null;
    switch ($metodo) {
        case 'testConnection':
            $result = $client->testConnection();
            echo json_encode(array(
                'success' => !empty($result['success']),
                'message' => isset($result['message']) ? $result['message'] : '',
                'result' => $result
            ));
            return;

        case 'ensureSession':
            $ok = $client->ensureSession();
            echo json_encode(array(
                'success' => (bool) $ok,
                'message' => $ok ? 'Sessão iDSecure OK' : $client->getLastError(),
                'result' => $ok
            ));
            return;

        case 'loadUsers':
            $start = isset($args['start']) ? (int) $args['start'] : 0;
            $limit = isset($args['limit']) ? (int) $args['limit'] : 1000;
            $result = $client->loadUsers($start, $limit);
            echo json_encode(array(
                'success' => ($result !== false),
                'message' => $result === false ? $client->getLastError() : 'OK',
                'result' => $result
            ));
            return;

        case 'findUserByRegistration':
            $reg = isset($args['registration']) ? $args['registration'] : '';
            $result = $client->findUserByRegistration($reg);
            echo json_encode(array(
                'success' => ($result !== false),
                'message' => $result === false ? $client->getLastError() : 'OK',
                'result' => $result
            ));
            return;

        case 'createUsers':
            $values = isset($args['values']) ? $args['values'] : array();
            $result = $client->createUsers($values);
            echo json_encode(array(
                'success' => ($result !== false),
                'message' => $result === false ? $client->getLastError() : 'OK',
                'result' => $result
            ));
            return;

        case 'modifyUsers':
            $values = isset($args['values']) ? $args['values'] : array();
            $where = isset($args['where']) ? $args['where'] : array();
            $result = $client->modifyUsers($values, $where);
            echo json_encode(array(
                'success' => ($result !== false && $result !== false),
                'message' => $result ? 'OK' : $client->getLastError(),
                'result' => $result
            ));
            return;

        case 'remoteEnroll':
            $userId = isset($args['user_id']) ? (int) $args['user_id'] : 0;
            $type = isset($args['type']) ? $args['type'] : 'biometry';
            $result = $client->remoteEnroll($userId, $type);
            echo json_encode(array(
                'success' => ($result !== false),
                'message' => $result === false ? $client->getLastError() : 'OK',
                'result' => $result
            ));
            return;

        default:
            echo json_encode(array('success' => false, 'message' => 'Método não suportado: ' . $metodo));
    }
} catch (Exception $e) {
    echo json_encode(array('success' => false, 'message' => 'Erro na ponte: ' . $e->getMessage()));
}
