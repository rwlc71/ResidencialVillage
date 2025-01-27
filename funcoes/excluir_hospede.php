<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
include "senha.php";


$id_hospede = $_POST['id_hospede'];

//================================= Gravar no banco - Inclusao de hospedes
$deletou = false;

$query = "DELETE FROM hospede WHERE id_hospede = $id_hospede";
//echo($query);
//exit();
$result = mysql_query($query);
if ($result) {
    $deletou = true;
} else {
    $erro = mysql_error();
    $deletou = false;
    echo json_encode(['status' => 'error', 'message' => 'Ação falhou: ' . $erro]);
    return;
}

if ($deletou == true) {
    echo json_encode(['id' => $id_locacao, 'status' => 'success', 'message' => 'Hóspede excluído com sucesso!']);
    return;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Atenção: falha ao excluir hóspede. Refaça a transação!']);
}
?>
