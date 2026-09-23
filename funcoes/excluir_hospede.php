<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
include "senha.php";


$id_hospede = isset($_POST['id_hospede']) ? intval($_POST['id_hospede']) : 0;
if ($id_hospede <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Hóspede inválido.']);
    return;
}

$id_locacao = '';
$sqlHosp = mysql_query("SELECT id_locacao FROM hospede WHERE id_hospede = " . $id_hospede);
if ($sqlHosp && mysql_num_rows($sqlHosp)) {
    $lnHosp = mysql_fetch_array($sqlHosp);
    $id_locacao = $lnHosp['id_locacao'];
}

$deletou = false;

$query = "DELETE FROM hospede WHERE id_hospede = $id_hospede";
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
