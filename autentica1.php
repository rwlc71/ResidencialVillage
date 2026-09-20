<?php
include "conexao.php";
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$inf = $_REQUEST['inf'];

if ($inf == 't') {
    $_SESSION['nome_usuario'] = '';
    $_SESSION['tipo_acesso'] = '';
    $_SESSION['usuario'] = '';
    $_SESSION['senha'] = '';
    $_COOKIE['usuario'] = '';
    $_COOKIE['tipo_acesso'] = '';

    setcookie("senha", "", time() - 3600, "/");
    setcookie("usuario", "", time() - 3600, "/");
    setcookie("nome_usuario", "", time() - 3600, "/");
    setcookie("tipo_acesso", "", time() - 3600, "/");
    header("Location: autentica.php");
    RETURN DIE;
}

if ($_POST['botao'] == "Autenticar") {
// busca usuario cadastrado
    $sql = ("SELECT * FROM usuarios WHERE usuario = '$usuario' and  senha = '$senha'");
    $sql = mysql_query("SELECT * FROM usuarios WHERE usuario = '$usuario' and  senha = '$senha'");
    if (mysql_num_rows($sql) == true) {
        while ($ln = mysql_fetch_array($sql)) {
            $proprietario = $ln['id_proprietario'];
            $sqlnome = mysql_query("SELECT * FROM proprietario WHERE id_proprietario = '$proprietario'");
            $lnome = mysql_fetch_array($sqlnome);
            $lgpd = $lnome['lgpd'];
//                    $lgpd = 1;
            if ($lgpd == 1) {
                $expire_time = time() + (60 * 180);
                $nome = strtoupper($lnome['nome']);

                setcookie("usuario", $ln['usuario'], $expire_time, "/");
                setcookie("nome_usuario", $nome, $expire_time, "/");
                setcookie("tipo_acesso", $ln['tipo_acesso'], $expire_time, "/");
                $_SESSION['nome_usuario'] = $nome;
                $_SESSION['tipo_acesso'] = $ln['tipo_acesso'];
                $_SESSION['usuario'] = $ln['usuario'];
                
                date_default_timezone_set('America/Bahia');
                $datahoje = date('d/m/Y');
                $horalogin = date('H:i:s');
                $ip = getenv("REMOTE_ADDR"); // pego IP
                $host = gethostbyaddr("$ip"); //pego o host

                $sqlAcesso = "INSERT INTO controleacesso (datalogin, horalogin, idusuario, nomelogin, end_ip, host)
				VALUES ('$datahoje', '$horalogin', $proprietario, '$nome', '$ip', '$host')
						";
                mysql_query($sqlAcesso);

                echo "<meta http-equiv='refresh' content='0; URL=proprietarios.php'>
                            <script type=\"text/javascript\">
                            alert(\"Seja bem vindo Sr(a): $nome!\");
                            </script> ";
            } else {
                echo "<meta http-equiv='refresh' content='0; URL=lgpd.php?dado=$usuario'>
                            <script type=\"text/javascript\">
                            </script>   ";
            }
        }
    } else {
        echo "<meta http-equiv='refresh' content='0; URL=autentica.php'>
    <script type=\"text/javascript\">
    alert(\"Usuário ou senha incorretos!\");
    </script>
  ";
    }
}
