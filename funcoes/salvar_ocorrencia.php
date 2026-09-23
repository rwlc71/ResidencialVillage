<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
//====================================
$id_unidade = $_POST['id_unidade'];
$id_audita = $_POST['id_audita'];
//echo(var_dump($_COOKIE));
//exit();
If ($_POST['ocorrencia'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_ocorrencia.php?dado=$id_audita'>
    <script type=\"text/javascript\">
        alert(\"Campo de OCORRÊNCIAS deve ser preenchido!  \");
      </script>
                ";
    return die;
}
//exit();

if ($_POST['botao'] == "Cadastrar Ocorrência") {
    date_default_timezone_set('America/Sao_Paulo');
    $dataHoje = date('Y-m-d H:i:s');
    $autor = $_COOKIE['nome_usuario'];

//Busca dados do proprietário
    $buscaDados = "SELECT prop.nome, prop.email, loc.id_locacao, uni.etapa, uni.numero_etapa  FROM locacao loc "
            . " JOIN unidade uni ON uni.id_unidade = loc.id_unidade"
            . " JOIN proprietario prop ON prop.id_proprietario = loc.id_proprietario"
            . " WHERE loc.id_locacao = '" . $id_audita . "'";

    $filtro = mysql_query($buscaDados);
    $ln = mysql_fetch_array($filtro);
    $nome = $ln['nome'];
    $email = $ln['email'];
    $unidade = $ln['numero_etapa'];
    $etapa = $ln['etapa'];
//    echo($ln['email'].'<p>');
//    echo($email);
//    exit();
// =================Insere na tabela ocorrencia
    $sql2 = "INSERT INTO ocorrencias (id_ocorrencia, id_locacao, ocorrencia, dt_registro, autor  )
            VALUES (NULL,'" . $id_audita . "','" . $_POST['ocorrencia'] . "','" . $dataHoje . "','" . $autor . "')";
    $result = mysql_query($sql2);
    if ($result) {
// rotina para envio de e-mail ao proprietário e a adminstração        
        $dt_cadastro = date('d/m/Y - H:i:s');
        $quebra_linha = "\r\n"; // windows

        $assunto = "Registro de ocorrência para a unidade " . $unidade . " etapa " . $etapa . " do Residencial Village Thermas das Caldas";
        $mensagem = "Prezado Sr(a). " . $nome . "<p>" .
                "Comunicamos que foi registrada uma ocorrência para sua unidade conforme abaixo nesta data: <p> " .
                "<dt><dd><ol style='list-style-type: disc'><li><i>" . $_POST['ocorrencia'] . "</i></li></ol></dd></dt></b><p>" .
                "Havendo dúvidas ou questionamentos sobre o ocorrido, entre em contato com a administração do Residencial Village." .
                "<p> Atenciosamente,
             <p><b> Administração do Residencial Village Thermas das Caldas</b>
             <p><b><i>Um condomínio não se resume a um conjunto de casas dispostas de forma ordenada.</i><b>
             <br><b><i>É, sobretudo, o convívio numa sociedade fechada de pessoas que idealizam</i><b>
             <b><i>e projetam para si uma melhor qualidade de vida.</i><b>";

        $formato = "MINE-Version: 1.1" . $quebra_linha;
        $formato .= "Content-Type: text/html; charset=UTF-8" . $quebra_linha;
        $formato .= "From: " . "Residencial Village Thermas das Caldas <residencial_village@1portodos.com.br>" . $quebra_linha;
        $formato .= "Return-Path: " . "residencial_village@1portodos.com.br" . $quebra_linha;
        
        $envio = mail($email, $assunto, $mensagem, $formato, "-fresidencial_village@1portodos.com.br");
        $envio2 = mail("residencialvillage.caldas@gmail.com", $assunto, $mensagem, $formato, "-fresidencial_village@1portodos.com.br");

//        $envio = mail('rogerio@1portodos.com.br', $assunto, $mensagem, $formato, "-fresidencial_village@1portodos.com.br");
//        echo ('E-mail usuario: ' . $email . '<p>');
//        echo ('Assunto: ' . $assunto . '<p>');
//        echo ('Mensagem: ' . $mensagem . '<p>');
//        exit();

        if (!$envio) {
            $confirma = "Ocorrência realizada com sucesso! No entanto, tivemos um problema ao enviar o e-mail "
                    . "de confirmação. Por favor, verifique se o endereço de e-mail informado está correto. "
                    . "Caso precise de ajuda, entre em contato "
                    . "com o nosso suporte.";
            echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_ocorrencia.php?dado=$id_audita'>
                    <script type=\"text/javascript\">
                    alert(\"$confirma\");
                    </script>
                  ";
            return die;
        }

//=======================        
        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_ocorrencia.php?dado=$id_audita'>
                    <script type=\"text/javascript\">
                    alert(\"Ocorrência registrada com sucesso!  \");
                    </script>
                    ";
        return die;
    } else {
        $erro = mysql_error();
        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_ocorrencia.php?dado=$id_audita'>
                    <script type=\"text/javascript\">
                       alert(\"Falha ao salvar ocorrência: $erro   \");
                    </script>
                    ";
        return die;
    }
//======================================        
} else {
    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Funcionalidade não localizada!  \");
                    </script>
                    ";
    return die;
}
?>
