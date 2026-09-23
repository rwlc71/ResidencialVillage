<?php
include "conexao.php";
include "valida/verifica_autenticacao.php";

header('Content-Type: application/json');
$dadosRecebidos = json_decode(file_get_contents('php://input'), true);
if (isset($dadosRecebidos['fornecedor'])) {
    $dadosRecebidos = $dadosRecebidos['fornecedor'];
}

$fornecedor = isset($_POST['fornecedor']) ? trim($_POST['fornecedor']) : '';
if ($dadosRecebidos) {
    $fornecedor = $dadosRecebidos;
}

if (empty($fornecedor)) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Campo fornecedor vazio.'
    ));
    return;
}

$fornecedorEsc = mysql_real_escape_string($fornecedor);
$sql = "SELECT DISTINCT nome, contato, identificacao FROM entradas WHERE nome like '%" . $fornecedorEsc . "%'";
$result = mysql_query($sql);
if (!$result) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Erro no servidor: ' . mysql_error()
    ));
    return;
}

if (mysql_num_rows($result) > 0) {
    $dados = mysql_fetch_array($result);
    echo json_encode(array(
        'success' => true,
        'id' => $dados['identificacao'],
        'contato' => $dados['contato']
    ));
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Fornecedor não encontrado.'
    ));
}
?>
