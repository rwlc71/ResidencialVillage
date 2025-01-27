<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
if ($_COOKIE['usuario'] == '') {
    header("Location: home.php");
    RETURN DIE;
} else {
    $_SESSION['nome_usuario'] = '';
    $_SESSION['tipo_acesso'] = '';
    $_SESSION['usuario'] = '';
    $_SESSION['senha'] = '';

    setcookie("senha", "", time() - 3600, "/");
    setcookie("usuario", "", time() - 3600, "/");
    setcookie("nome_usuario", "", time() - 3600, "/");
    setcookie("tipo_acesso", "", time() - 3600, "/");
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
		<script type=\"text/javascript\">
		alert(\"Saída do sistema realizada com sucesso!\");
		</script>
                ";
    return die;
}
exit();
?>