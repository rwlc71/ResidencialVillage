<?php
header('Content-Type: text/html; charset=utf-8');
include "conexao.php";
include "valida/verifica_autenticacao.php";

$q = '';
if (isset($_REQUEST['term'])) {
    $q = $_REQUEST['term'];
} elseif (isset($_REQUEST['valor'])) {
    $q = $_REQUEST['valor'];
} elseif (isset($_REQUEST['q'])) {
    $q = $_REQUEST['q'];
}
$q = trim($q);
if ($q === '') {
    exit;
}

$q = mysql_real_escape_string($q);
$sql = "SELECT CPF FROM proprietario WHERE nome LIKE '%" . $q . "%' LIMIT 1";
$result = mysql_query($sql);
if (!$result) {
    exit;
}

if ($reg = mysql_fetch_array($result)) {
    echo $reg['CPF'];
}
?>
