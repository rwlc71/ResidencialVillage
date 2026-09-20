<?php
if ($_COOKIE['usuario'] == '') {
    header("Location: home.php");
    RETURN DIE;
} else {
    setcookie("senha", "", time() - 3600, "/");
    setcookie("usuario", "", time() - 3600, "/");
    setcookie("nome_usuario", "", time() - 3600, "/");
    setcookie("tipo_acesso", "", time() - 3600, "/");
    $_SESSION['nome_usuario'] = '';
    $_SESSION['tipo_acesso'] = '';
    $_SESSION['usuario'] = '';
    $_SESSION['senha'] = '';

    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
		<script type=\"text/javascript\">
		alert(\"Saída do sistema realizada com sucesso!\");
		</script>
                ";
    return die;
}
exit();
?>