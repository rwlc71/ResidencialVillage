<?php
// $con = mysql_connect('bdcasasvillage.mysql.dbaas.com.br', 'bdcasasvillage', 'Village@2024');
// $db = mysql_select_db('bdcasasvillage', $con);
//  $con = mysql_connect("localhost", "root", "");
//  $db = mysql_select_db("bdcontrolesvillage", $con);
 $con = mysql_connect("localhost", "root", "");

// $con = mysql_connect('bdcasasvillage.mysql.dbaas.com.br', 'bdcasasvillage', 'Village@2024');
 if (!$con) {
      echo "<meta http-equiv='refresh' content='0; URL=home.php'>
      <script type=\"text/javascript\">
      alert(\"Falha na comunicação com o banco de dados!  \");
      </script>
                ";
    return die;
}
  $db = mysql_select_db("bdcasasvillage", $con);
?>