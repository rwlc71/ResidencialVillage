<?php

$usuario = $_SESSION['usuario'];
if ($usuario == '') {
    $usuario = $_COOKIE['usuario'];
}

$sql1 = mysql_query("SELECT * FROM usuarios WHERE usuario = '$usuario'");
$ln1 = mysql_fetch_array($sql1);
//echo('acesso: ' . $ln1['tipo_acesso']);
//exit();
if (mysql_num_rows($sql1) == true) {
    if ($ln1['tipo_acesso'] != 'seg' && $ln1['tipo_acesso'] != 'adm' && $ln1['tipo_acesso'] != 'sup') {
                echo "<meta http-equiv='refresh' content='0; URL=autentica.php'>
                    <script type=\"text/javascript\">
                    alert(\"Acesso restrito à administração do Residencial Village! \");
                    </script> ";
        Return die;
    }
}
?>