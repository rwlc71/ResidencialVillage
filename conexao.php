<?php
//conexão para Versão 5.4 php
$host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
if ($host == 'localhost') {
    $host = 'localhost';
    $dbname = 'bdcasasvillage';
    $user = 'root';
    $password = '';
} else {
    $host = 'bdcasasvillage.mysql.dbaas.com.br';
    $dbname = 'bdcasasvillage';
    $user = 'bdcasasvillage';
    $password = 'Village@2024';
}

 $con = mysql_connect($host, $user, $password);
 if (!$con) {
      echo "<meta http-equiv='refresh' content='0; URL=home.php'>
      <script type=\"text/javascript\">
      alert(\"Falha na comunicação com o banco de dados!  \");
      </script>
                ";
    return die;
}
  $db = mysql_select_db($dbname, $con);
?>