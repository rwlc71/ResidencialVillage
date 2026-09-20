<?php
include "conexao.php";

$q = '';
if (isset($_GET['q'])) {
    $q = $_GET['q'];
} elseif (isset($_GET['valor'])) {
    $q = $_GET['valor'];
}

$q = preg_replace('/[^0-9]/', '', $q);
if ($q === '') {
    exit;
}

$q = mysql_real_escape_string($q);
$sql = "SELECT DISTINCT CPF FROM proprietario
        WHERE REPLACE(REPLACE(REPLACE(REPLACE(CPF,'.',''),'-',''),'/',''),' ','') LIKE '%" . $q . "%'
        LIMIT 20";
$query = mysql_query($sql);
if (!$query) {
    exit;
}

while ($reg = mysql_fetch_array($query)) {
    $documento = formatarDocumento($reg['CPF']);
    if ($documento !== '') {
        print $documento . "\n";
    }
}

function formatarDocumento($numero)
{
    $numero = preg_replace('/[^0-9]/', '', $numero);
    if (strlen($numero) == 11) {
        return substr($numero, 0, 3) . '.' .
            substr($numero, 3, 3) . '.' .
            substr($numero, 6, 3) . '-' .
            substr($numero, 9, 2);
    }
    if (strlen($numero) == 14) {
        return substr($numero, 0, 2) . '.' .
            substr($numero, 2, 3) . '.' .
            substr($numero, 5, 3) . '/' .
            substr($numero, 8, 4) . '-' .
            substr($numero, 12, 2);
    }
    return $numero;
}
