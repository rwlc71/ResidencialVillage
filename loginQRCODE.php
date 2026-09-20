<?php
// login.php - compatível com PHP 5.4
header('Content-Type: application/json; charset=utf-8');

// Evita warnings e caracteres antes do JSON
error_reporting(0);
ob_clean();
date_default_timezone_set('America/Sao_Paulo');

include "conexao_validar.php";

// Lê os parâmetros (compatível com envio via fetch POST)
$login = isset($_POST['login']) ? $_POST['login'] : '';
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

$cpf = preg_replace('/[^0-9]/', '', $login);

// Consulta banco de dados
$sql = "SELECT p.*, u.tipo_acesso 
        FROM proprietario p
        LEFT JOIN usuarios u ON u.id_proprietario = p.id_proprietario
        WHERE p.CPF = :cpf";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$stmt->execute();
$ln = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ln && isset($ln['tipo_acesso'])) {
    if ($ln['tipo_acesso'] === 'seg' || $ln['tipo_acesso'] === 'sup' || $ln['tipo_acesso'] === 'adm' ) {
        // Usuário autorizado
        echo json_encode(array(
            'status' => 'ok',
            'nome' => $ln['nome']
        ));
        exit;
    } else {
        echo json_encode(array(
            'status' => 'erro',
            'mensagem' => 'Acesso não permitido'
        ));
        exit;
    }
} else {
    echo json_encode(array(
        'status' => 'erro',
        'mensagem' => 'Acesso não permitido: usuário não cadastrado'
    ));
    exit;
}
?>
