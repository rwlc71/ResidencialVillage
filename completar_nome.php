<?php
include "conexao.php";
include "../valida/verifica_autenticacao.php";
$q = strtolower($_REQUEST["q"]);
$sql = "SELECT DISTINCT nome FROM proprietario WHERE nome like '%" . $q . "%'";
$result = mysql_query($sql);
if (!$result) {
    $erro = mysql_error();
	    echo "<meta http-equiv='refresh' content='0; URL=consulta_proprietarios.php'>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao buscar nome: $erro   \");
                    </script> ";
    return die;
} else {
    while ($reg = mysql_fetch_array($result)) {
        $mostrar = strtoupper($reg["nome"]);
        echo $mostrar . "\n";
    }
}
?>



