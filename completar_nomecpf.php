<?php
include "conexao.php";

$q=strtolower ($_GET["q"]);

$sql = "SELECT DISTINCT * FROM tab_docfiscal WHERE nome_fornecedor like '%" . $q . "%'";

$query = mysql_query($sql);// or die ("Erro". mysql_query());

//echo ($_GET["q"]);
//echo ' / ';
//echo ($sql);
//exit();

while($reg=mysql_fetch_array($query)){

	//if (srtpos(strtolower($reg['nom_lista']),$q !== false){
		echo $reg["cpfcnpj_fornecedor"]."|".$reg["cpfcnpj_fornecedor"]."\n";
//	}
}
?>

