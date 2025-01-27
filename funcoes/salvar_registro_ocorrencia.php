
<?php

//session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
//====================================
$id_unidade = $_POST['id_unidade'];
$proprietario = $_POST['proprietario'];
$email = $_POST['email'];
$id_proprietario = $_POST['id_proprietario'];
$ocorrencia = $_POST['ocorrencia'];
$autor = $_POST['autor'];
$usuario = $_POST['usuario'];
$unidade = $_POST['unidade'];
$etapa = $_POST['etapa'];

//   echo json_encode(['status' => 'teste', 'usuario' => $_COOKIE['usuario'], 'post' => $_POST, 'cookie' => $_COOKIE]);
//   return;
//echo json_encode(['status' => 'error', 'message' => 'Campo de OCORRÊNCIAS deve ser preenchido!']);
//return;

If (trim($ocorrencia) == '' || strlen($ocorrencia) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Campo de OCORRÊNCIAS deve ser preenchido!']);
    return;
}
If (trim($proprietario) == '' || strlen($proprietario) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Campo de Proprietário deve ser preenchido!']);
    return;
}
If (trim($id_unidade) == '' || strlen($id_unidade) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Campo de UNIDADE deve ser preenchido!']);
    return;
}



date_default_timezone_set('America/Sao_Paulo');
$dataHoje = date('Y-m-d H:i:s');

//Busca dados do proprietário
//    echo($ln['email'].'<p>');
//    echo($email);
//    exit();
// =================Insere na tabela ocorrencia
$sql2 = "INSERT INTO registro_ocorrencia (id_ocorrencia, id_unidade, autor, data_registro, ocorrencia  )
            VALUES (NULL,'" . $id_unidade . "','" . $autor . "','" . $dataHoje . "','" . $ocorrencia . "')";

$result = mysql_query($sql2);
if ($result) {
// rotina para envio de e-mail ao proprietário e a adminstração        
    $dt_cadastro = date('d/m/Y - H:i:s');
    $quebra_linha = "\r\n"; // windows

    $assunto = "Registro de ocorrência para a unidade " . $unidade . " etapa " . $etapa . " do Residencial Village Thermas das Caldas";
    $mensagem = "Prezado Sr(a). " . $proprietario . "<p>" .
            "Comunicamos que foi registrada uma ocorrência para sua unidade conforme abaixo nesta data: <p> " .
            "<dt><dd><ol style='list-style-type: disc'><li><i>" . $ocorrencia . "</i></li></ol></dd></dt></b><p>" .
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

    if ($envio) {
        echo json_encode(['status' => 'success', 'message' => 'Ocorrência registrada com sucesso!']);
        return;
    } else {
        $confirma = "Ocorrência registrada com sucesso! No entanto, tivemos um problema ao enviar o e-mail "
                . "de confirmação. Por favor, verifique se o endereço de e-mail informado está correto. "
                . "Caso precise de ajuda, entre em contato com nossa adminstração ou "
                . "com o nosso suporte.";
        echo json_encode(['status' => 'error', 'message' => $confirma]);
        return;
    }
} else {
    $erro = mysql_error();
    $msg = 'Falha ao salvar ocorrência: ' . $erro;
    echo json_encode(['status' => 'error', 'message' => $msg]);
    return;
}
//======================================        
?>
