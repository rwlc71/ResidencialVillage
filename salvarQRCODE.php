<?php

// Ativa erros para debug (remover em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Bahia');
include "conexao_validar.php";

$data_servidor = date('Y-m-d H:i:s');

$qrcode = isset($_POST['codigo']) ? $_POST['codigo'] : '';
$lat = isset($_POST['latitude']) ? $_POST['latitude'] : '';
$lng = isset($_POST['longitude']) ? $_POST['longitude'] : '';
$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : '';
$hora_usuario = isset($_POST['hora_usuario']) ? $_POST['hora_usuario'] : '';

// Grava também em log local (opcional)
//$linha = date('Y-m-d H:i:s') . " | DATA: $data_servidor | hora_usuario: $hora_usuario | QR: $qrcode | Usuario: $usuario | Lat: $lat | Lng: $lng\n";
//file_put_contents('log_qr.txt', $linha, FILE_APPEND);

try {
    // Inserção no banco
    $sql = "INSERT INTO localizacao (
                usuario, codigo, latitude, longitude,
                hora_usuario, hora_servidor, criado_em
            ) VALUES (
                :usuario, :codigo, :latitude, :longitude,
                :hora_usuario, :hora_servidor, :criado_em
            )";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario' => $usuario,
        ':codigo' => $qrcode,
        ':latitude' => $lat,
        ':longitude' => $lng,
        ':hora_usuario' => $hora_usuario,
        ':hora_servidor' => $data_servidor,
        ':criado_em' => $data_servidor
    ]);

    echo json_encode(['status' => 'ok', 'mensagem' => 'Dados gravados com sucesso.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Falha ao gravar: ' . $e->getMessage()]);
}
?>
