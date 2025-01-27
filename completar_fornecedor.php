<?php
include "conexao.php";
$q = strtolower($_REQUEST["q"]);
$sql = "SELECT DISTINCT nome FROM entradas WHERE nome like '%" . $q . "%'";
$result = mysql_query($sql);
if (!$result) {
    $erro = mysql_error();
    echo "<meta http-equiv='refresh' content='0; URL=entradas.php'>
	<script type=\"text/javascript\">
	alert(\"Falha ao buscar fornecedor: $erro   \");
	</script> ";
    return die;
	} else {
		while ($reg = mysql_fetch_array($result)) {
			$mostrar = strtoupper($reg["nome"]);
			echo $mostrar . "\n";
			}
			}
			?>