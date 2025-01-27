<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>


<?php
//===========================================

include "senha.php";
include "../conexao.php";

$senha = geraSenha(10);
$usuario = $_REQUEST['usuario'];

$buscaDados = "SELECT prop.nome, prop.email, usu.id_proprietario FROM proprietario prop "
        . " JOIN usuarios usu ON prop.id_proprietario = usu.id_proprietario"
        . " WHERE prop.CPF = '" . $usuario . "'";

//echo ('$buscaDados: ' . $buscaDados . '<p>');
$filtro = mysql_query($buscaDados);
$num_rows = mysql_num_rows($filtro);
$ln = mysql_fetch_array($filtro);

$email = $ln['email'];
$sql = "UPDATE usuarios SET senha = '" . $senha . "' WHERE id_proprietario = '" . $ln['id_proprietario'] . "'";

$result = mysql_query($sql);
if ($result) {
    echo "<script type=\"text/javascript\">
           alert(\"Nova senha gerada com sucesso!  \");
          </script>";
} else {
    $erro = mysql_error();
    echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
                    <script type=\"text/javascript\">
                       alert(\"Falha ao gerar nova senha: $erro   \");
                    </script>
                    ";
    return die;
}

$dt_cadastro = date('d/m/Y - H:i:s');
$quebra_linha = "\r\n"; // windows

$assunto = "Solicitação de nova senha de acesso - Residencial Village Thermas das Caldas ";
$mensagem = "Prezado, condômino; <p>" .
        "Foi solicitada a geração de uma nova senha através do site do condomínio. <p> " .
        "A senha gerada pelo sistema é: <b>" . $senha . "</b><p>" .
        "Acesse o sistema e realize a alteração para uma senha de sua escolha." .
        "<p> Atenciosamente,
             <p><b> Administração do Residencial Village Thermas das Caldas</b>
             <p><b><i>Um condomínio não se resume a um conjunto de casas dispostas de forma ordenada.</i><b>
             <br><b><i>É, sobretudo, o convívio numa sociedade fechada de pessoas que idealizam</i><b>
             <b><i>e projetam para si uma melhor qualidade de vida.</i><b>";

$formato = "MINE-Version: 1.1" . $quebra_linha;
$formato .= "Content-Type: text/html; charset=UTF-8" . $quebra_linha;
$formato .= "From: " . "Residencial Village Thermas das Caldas <residencial_village@1portodos.com.br>" . $quebra_linha;
$formato .= "Return-Path: " . "residencial_village@1portodos.com.br" . $quebra_linha;

$envio = mail($ln['email'], $assunto, $mensagem, $formato, "-fresidencial_village@1portodos.com.br");

//echo ('E-mail usuario: ' . $ln['email'] . '<p>');
//echo ('Resposta: ' . $envio . '<p>');
//exit();

if ($envio) {
    echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
    <script type=\"text/javascript\">
    alert(\"Verifique seu e-mail!\");
    </script>
  ";
} else {
    echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
    <script type=\"text/javascript\">
    alert(\"Falha ao enviar e-mail de confirmação. Contate o administrador do sistema!\");
    </script>
  ";
}
?>