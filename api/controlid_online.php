<?php
/**
 * Stub para modo Online do Control iD.
 * Quando o equipamento estiver em modo Pro/Enterprise, configure-o para
 * consultar este endpoint nas identificações.
 *
 * Documentação: https://www.controlid.com.br/docs/access-api-pt/
 *
 * Nesta versão o modo ativo do condomínio é Standalone (sync unidirecional).
 * Este arquivo registra o evento e responde estrutura básica para evolução futura.
 */
header('Content-Type: application/json; charset=utf-8');

$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

// Log simples para diagnóstico
$logDir = dirname(__FILE__) . '/../logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
@file_put_contents(
    $logDir . '/controlid_online.log',
    date('Y-m-d H:i:s') . ' ' . $raw . "\n",
    FILE_APPEND
);

// Resposta mínima: negar por padrão até regras de negócio Online serem definidas
echo json_encode(array(
    'result' => array(
        'event' => 7,
        'user_id' => isset($data['user_id']) ? (int) $data['user_id'] : 0,
        'user_name' => 'Modulo Online pendente de ativacao',
        'user_image' => false,
        'portal_id' => isset($data['portal_id']) ? (int) $data['portal_id'] : 1,
        'actions' => array(),
        'message' => 'Modo Online ainda nao habilitado. Use Standalone via validar_biometria.php'
    )
));
