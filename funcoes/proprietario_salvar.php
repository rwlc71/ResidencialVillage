<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "senha.php";

//echo(var_dump($_POST));
//echo('Salvar - chegou');
//exit();
$cpf = $_POST['cpf'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);

$cep = $_POST['cep'];
$cep = str_replace(".", "", $cep);
$cep = str_replace("-", "", $cep);

$telefone = $_POST['telefone'];
$telefone = str_replace(".", "", $telefone);
$telefone = str_replace("-", "", $telefone);
$telefone = str_replace(" ", "", $telefone);
$telefone = str_replace("(", "", $telefone);
$telefone = str_replace(")", "", $telefone);

$validacpf = $cpf;

if (validarCPFeCNPJ($cpf)) {
    $valida = "ok";
} else {
    $valida = "nok";
}

if ($valida == "nok") {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
 <script type=\"text/javascript\">
 alert(\"Número de CPF inválido!\");
 </script>
   ";
    Return die;
}

//====================================

If ($_POST['nome'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
    <script type=\"text/javascript\">
      alert(\"Campo NOME de preenchimento obrigatório!  \");
      </script>
                ";
    return die;
}
If ($_POST['endereco'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
      <script type=\"text/javascript\">
      alert(\"Campo ENDEREÇO de preenchimento obrigatório!  \");
      </script>
                ";
    return die;
}
If (($_POST['cidade'] == "")) {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
      <script type=\"text/javascript\">
      alert(\"Campo CIDADE de preenchimento obrigatório!  \");
      </script>
                ";
    return die;
}
If ($_POST['estado'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
      <script type=\"text/javascript\">
      alert(\"Campo ESTADO de preenchimento obrigatório!  \");
      </script>
                ";
    return die;
}
If ($_POST['cep'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
      <script type=\"text/javascript\">
      alert(\"Campo CEP de preenchimento obrigatório!  \");
      </script>
                ";
    return die;
}
If ($_POST['email'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
      <script type=\"text/javascript\">
      alert(\"Campo E-MAIL de preenchimento obrigatório!  \");
      </script>
                ";
    return die;
}
If ($_POST['telefone'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
      <script type=\"text/javascript\">
      alert(\"Campo TELEFONE de preenchimento obrigatório!  \");
      </script>
                ";
    return die;
}
if ($_POST['botao'] == "Incluir dados de proprietário") {
    //================================= Gravar no banco - Inclusao
    $sql = mysql_query("SELECT * FROM proprietario WHERE CPF = '$cpf'");
    $ln = mysql_fetch_array($sql);
    if (mysql_num_rows($sql)) {
        echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
        <script type=\"text/javascript\">
        alert(\"Proprietário já cadastrado!  \");
        </script> ";
        return die;
    } else {
        $lgpd = '0';
        $sql = "INSERT INTO proprietario (id_proprietario, CPF, nome, endereco, cidade, estado, cep, email, telefone, lgpd)
                VALUES (NULL,'" . $cpf . "','" . $_POST['nome'] . "','" . $_POST['endereco'] . "',
                '" . $_POST['cidade'] . "','" . $_POST['estado'] . "','" . $cep . "','" . $_POST['email'] . "','" . $telefone . "','" . $lgpd . "')";

        $result = mysql_query($sql);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao salvar novo usuário na tabela PROPRIETÁRIOS: $erro   \");
                    </script> ";
            return die;
        }

        $sql = mysql_query("SELECT * FROM proprietario WHERE CPF = '$cpf'");
        $ln = mysql_fetch_array($sql);
        $id_doc = mysql_insert_id();
        $senha = geraSenha(10);

        $sql1 = "INSERT INTO usuarios (id_proprietario, usuario, senha, tipo_acesso)
                 VALUES ('" . $ln['id_proprietario'] . "','" . $cpf . "','" . $senha . "','" . 'con' . "')";

        $result = mysql_query($sql1);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao salvar novo usuário  na tabela USUÁRIOS: $erro   \");
                    </script> ";
            return die;
        }

//=======================================
        $dt_cadastro = date('d/m/Y - H:i:s');
        $quebra_linha = "\r\n";
        $assunto = "Cadastro de proprietario -  Residencial Village Thermas das Caldas ";
        $mensagem = "Prezado, proprietário; <p>" .
                "Seus dados foram cadastrados na aplicação WEB do Residencial Village Thermas das Caldas. <p> " .
                "A senha gerada pelo sistema é: <b>" . $senha . "</b><p>" .
                "Acesse o sistema e realize a atualização da senha para uma de sua escolha." .
                "<p> Atenciosamente,
                <p><b> Administração do Residencial Village Thermas das Caldas</b>
                <p><b><i>Um condomínio não se resume a um conjunto de casas dispostas de forma ordenada.</i><b>
                <br><b><i>É, sobretudo, o convívio numa sociedade fechada de pessoas que idealizam</i><b>
                <b><i>e projetam para si uma melhor qualidade de vida.</i><b>";

        $formato = "MINE-Version: 1.1" . $quebra_linha;
        $formato .= "Content-Type: text/html; charset=UTF-8" . $quebra_linha;
        $formato .= "From: " . "Residencial Village Thermas das Caldas <residencial_village@1portodos.com.br>" . $quebra_linha;
        $formato .= "Return-Path: " . "residencial_village@1portodos.com.br" . $quebra_linha;

        $envio = mail($_POST['email'], $assunto, $mensagem, $formato, "-fresidencial_village@1portodos.com.br");

        if ($envio) {
            echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
                <script type=\"text/javascript\">
                alert(\"Cadastro realizado com sucesso!  \");
                </script> ";
            return die;
        } else {
            echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
            <script type=\"text/javascript\">
            alert(\"Cadastro realizado com sucesso!\");
            alert(\"Falha ao enviar e-mail de confirmação. Contate o administrador do sistema!\");
            </script> ";
            return die;
        }
    }
} else { // Atualizar
//====================
    $sql = "SELECT * FROM proprietario WHERE CPF = '$cpf'";
    $sql = mysql_query("SELECT * FROM proprietario WHERE CPF = '$cpf'");
    $ln = mysql_fetch_array($sql);
    if (mysql_num_rows($sql)) { //Alteração
        $sql1 = "UPDATE proprietario SET nome = '" . $_POST['nome'] . "',
        endereco = '" . $_POST['endereco'] . "',
        cidade = '" . $_POST['cidade'] . "',
        estado = '" . $_POST['estado'] . "',
        cep = '" . $cep . "',
        email = '" . $_POST['email'] . "',
        telefone = '" . $telefone . "'
        WHERE id_proprietario = '" . $ln['id_proprietario'] . "'";
//    echo($sql1);
//    exit();

        $result = mysql_query($sql1);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                    alert(\"Falha ao atualizar dados de PROPRIETÁRIOS: $erro   \");
                history.back(); 
                </script> ";
            return die;
        } else {
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                    alert(\"Atualização realizada com sucesso!  \");
                history.back(); 
                </script> ";
            return die;
        }
    }
}
?>
