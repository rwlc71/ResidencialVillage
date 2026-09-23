<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "senha.php";
if (is_readable(dirname(__FILE__) . '/funcoes/enviar_email.php')) {
    include dirname(__FILE__) . '/funcoes/enviar_email.php';
} else {
    include dirname(__FILE__) . '/enviar_email.php';
}

//echo(var_dump($_POST));
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
    $msgDoc = mensagemCpfCnpjInvalido($cpf);
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
 <script type=\"text/javascript\">
 alert(\"" . $msgDoc . "\");
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

$conselho = 'não';
if (isset($_POST['conselho'])) {
    $conselhoInformado = strtolower(trim($_POST['conselho']));
    if ($conselhoInformado === 'sim') {
        $conselho = 'sim';
    }
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
        $usu_principal = '1';
        $adimplente = '0';
        $sql = "INSERT INTO proprietario (id_proprietario, usu_principal, CPF, nome, endereco, cidade, estado, cep, email, telefone, lgpd, adimplente)
                VALUES (NULL,'" . $usu_principal . "','" . $cpf . "','" . $_POST['nome'] . "','" . $_POST['endereco'] . "',
                '" . $_POST['cidade'] . "','" . $_POST['estado'] . "','" . $cep . "','" . $_POST['email'] . "','" . $telefone . "','" . $lgpd . "','" . $adimplente . "')";

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

        $sql1 = "INSERT INTO usuarios (id_proprietario, usuario, senha, tipo_acesso, conselho)
                 VALUES ('" . $ln['id_proprietario'] . "','" . $cpf . "','" . $senha . "','" . 'con' . "','" . $conselho . "')";

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

        $envio = enviar_email_sistema($_POST['email'], $assunto, $mensagem);

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
} else {
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
            echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao atualizar dados de PROPRIETÁRIOS: $erro   \");
                    </script> ";
            return die;
        } else {
            mysql_query("UPDATE usuarios SET conselho = '" . $conselho . "' WHERE id_proprietario = '" . $ln['id_proprietario'] . "'");
            echo "<meta http-equiv='refresh' content='0; URL= ../proprietarios.php'>
                    <script type=\"text/javascript\">
                    alert(\"Atualização realizada com sucesso!  \");
                    </script>
                ";
            return die;
        }
    }
}
?>
