<?php

include "conexao_validar.php";
$dadosJSON = file_get_contents('php://input');
$dados = json_decode($dadosJSON, true);
date_default_timezone_set('America/Sao_Paulo');

$id_locacao = isset($dados['id']) ? trim($dados['id']) : '';
$login = isset($dados['login']) ? trim($dados['login']) : '';
//echo(var_dump($dados));
//echo(var_dump($id_locacao));
//echo(var_dump($login));
//exit();

if ($id_locacao <= 0 || empty($login)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados inválidos.']);
    exit;
}
if (!$id_locacao) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Código inválido.']);
    exit;
}
$cpf = $login;
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);

$stmtProprietario = $pdo->prepare("
    SELECT p.*, u.tipo_acesso 
    FROM proprietario p
    LEFT JOIN usuarios u ON u.id_proprietario = p.id_proprietario
    WHERE p.CPF = :cpf
");
$stmtProprietario->execute(['cpf' => $cpf]);
$ln = $stmtProprietario->fetch();
$autor = $ln['nome'];
$tipo_acesso = $ln['tipo_acesso'];
$linhas = $stmtProprietario->rowCount();
if ($linhas > 0) {
    if ($tipo_acesso === 'con') {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso não permitido']);
        exit;
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso não permitido']);
    exit;
}


$stmt = $pdo->prepare("SELECT dt_entrada_efetiva FROM audita WHERE id_audita = :id");
$stmt->bindParam(':id', $id_locacao, PDO::PARAM_INT);
$stmt->execute();
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

if ($registro && empty($registro['dt_entrada_efetiva'])) {
    $agora = date('Y-m-d H:i:s');

    // Atualiza audita
    $stmt1 = $pdo->prepare("UPDATE audita SET dt_entrada_efetiva = :agora, autor = :autor WHERE id_audita = :id");
    $stmt1->bindParam(':agora', $agora);
    $stmt1->bindParam(':autor', $autor); // variável com o nome do usuário que registrou a entrada
    $stmt1->bindParam(':id', $id_locacao);
    $stmt1->execute();

    // Atualiza locacao
    $stmt2 = $pdo->prepare("UPDATE locacao SET dt_entrada_efetiva = :agora WHERE id_locacao = :id");
    $stmt2->bindParam(':agora', $agora);
    $stmt2->bindParam(':id', $id_locacao);
    $stmt2->execute();

    echo json_encode(['status' => 'ok']);
} else {
    if (!$registro) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Registro não encontrado.']);
    } else {
        $data = date('d/m/Y', strtotime($registro['dt_entrada_efetiva']));
        $hora = date('H:i', strtotime($registro['dt_entrada_efetiva']));
        echo json_encode([
            'status' => 'ja_registrado',
            'mensagem' => "Essa autorização já foi registrada.<br>Data: {$data}<br>Hora: {$hora}"
        ]);
    }
}
exit;
?>
