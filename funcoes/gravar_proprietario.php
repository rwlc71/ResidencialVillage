<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "senha.php";
include "enviar_email.php";

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

//if ($valida == "nok") {
//    echo "<meta http-equiv='refresh' content='0; URL= ../proprietario_cadastro.php'>
// <script type=\"text/javascript\">
// alert(\"Número de CPF inválido!\");
// </script>
//   ";
//    Return die;
//}

If ($valida == "nok") {
    echo json_encode(["status" => "error", "message" => mensagemCpfCnpjInvalido($cpf)]);
    return;
}

//====================================

If ($_POST['nome'] == "") {
    echo json_encode(["status" => "error", "message" => "Campo NOME de preenchimento obrigatório!"]);
    return;
}
If ($_POST['endereco'] == "") {
    echo json_encode(["status" => "error", "message" => "Campo ENDEREÇO de preenchimento obrigatório!"]);
    return;
}
If (($_POST['cidade'] == "")) {
    echo json_encode(["status" => "error", "message" => "Campo CIDADE de preenchimento obrigatório!"]);
    return;
}
If ($_POST['estado'] == "") {
    echo json_encode(["status" => "error", "message" => "Campo ESTADO de preenchimento obrigatório!"]);
    return;
}
If ($_POST['cep'] == "") {
    echo json_encode(["status" => "error", "message" => "Campo CEP de preenchimento obrigatório!"]);
    return;
}
If ($_POST['email'] == "") {
    echo json_encode(["status" => "error", "message" => "Campo E-MAIL de preenchimento obrigatório!"]);
    return;
}
If ($_POST['telefone'] == "") {
    echo json_encode(["status" => "error", "message" => "Campo TELEFONE de preenchimento obrigatório!"]);
    return;
}

$conselho = 'não';
if (isset($_POST['conselho'])) {
    $conselhoInformado = strtolower(trim($_POST['conselho']));
    if ($conselhoInformado === 'sim') {
        $conselho = 'sim';
    }
}

if ($_POST['botao'] == "Incluir dados de proprietário") {
//    echo json_encode(["status" => "error", "message" => "Entrou em incluir! "]);
//    return;
    //================================= Gravar no banco - Inclusao
    $sql = mysql_query("SELECT * FROM proprietario WHERE CPF = '$cpf'");
    $ln = mysql_fetch_array($sql);
    if (mysql_num_rows($sql)) {
        echo json_encode(["status" => "error", "message" => "Proprietário já cadastrado!"]);
        return;
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
            echo json_encode(["status" => "error", "message" => "Falha ao salvar novo usuário na tabela PROPRIETÁRIOS: " . $erro]);
            return;
        }

        $sql = mysql_query("SELECT * FROM proprietario WHERE CPF = '$cpf'");
        $ln = mysql_fetch_array($sql);
        $id_doc = mysql_insert_id();
        $senha = geraSenha(10);
        $perfil = $_POST['perfil'];
        switch ($perfil) {
            case 'Proprietário':
                $perfil = 'con';
                break;
            case 'Administrativo':
                $perfil = 'adm';
                break;
            case 'Segurança':
                $perfil = 'seg';
                break;
            case 'Master':
                $perfil = 'sup';
                break;
        }

        $sql2 = "INSERT INTO usuarios (id_proprietario, usuario, senha, tipo_acesso, conselho)
                 VALUES ('" . $ln['id_proprietario'] . "','" . $cpf . "','" . $senha . "','" . $perfil . "','" . $conselho . "')";

        $result = mysql_query($sql2);
        if (!$result) {
            $erro = mysql_error();
            echo json_encode(["status" => "error", "message" => "Falha ao salvar novo usuário na tabela USUÁRIOS: " . $erro]);
            return;
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
            echo json_encode(["status" => "success", "message" => "Cadastro realizado com sucesso!"]);
            return;
        } else {
            $confirma = "Cadastro realizado com sucesso! No entanto, tivemos um problema ao enviar o e-mail "
                    . "de confirmação. Por favor, verifique se o endereço de e-mail informado está correto "
                    . "ou tente novamente mais tarde. Caso precise de ajuda, entre em contato "
                    . "com o nosso suporte.";
            echo json_encode(["status" => "success", "message" => $confirma]);
            return;
        }
    }
} else { // Atualizar
//      echo json_encode(["status" => "error", "message" => "Entrou em Atualizar! "]);
//            return;
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
        $perfil = $_POST['perfil'];
        switch ($perfil) {
            case 'Proprietário':
                $perfil = 'con';
                break;
            case 'Administrativo':
                $perfil = 'adm';
                break;
            case 'Segurança':
                $perfil = 'seg';
                break;
            case 'Master':
                $perfil = 'sup';
                break;
        }
        $result = mysql_query($sql1);
        if (!$result) {
            $erro = mysql_error();
            echo json_encode(["status" => "error", "message" => "Falha ao atualizar dados de PROPRIETÁRIOS: " . $erro]);
            return;
        } else {

            $sql2 = "UPDATE usuarios SET tipo_acesso = '" . $perfil . "', conselho = '" . $conselho . "'  WHERE id_proprietario = '" . $ln['id_proprietario'] . "'";

//            echo json_encode(["status" => "error", "message" => $sql1]);
//            return;
            
            $result = mysql_query($sql2);
            if (!$result) {
                $erro = mysql_error();
                echo json_encode(["status" => "error", "message" => "Falha ao atualizar novo usuário na tabela USUÁRIOS: " . $erro]);
                return;
            } else {
                echo json_encode(["status" => "success", "message" => "Atualização realizada com sucesso!"]);
                return;
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Proprietário não encontrado para atualização."]);
        return;
    }
}
?>
