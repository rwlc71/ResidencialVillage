<?php

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

require_once dirname(__FILE__) . '/funcoes/enviar_email.php';

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recebe parâmetros
    $para = filter_var(trim(isset($_POST['para']) ? $_POST['para'] : ''), FILTER_SANITIZE_EMAIL);
    $assunto = trim(isset($_POST['assunto']) ? $_POST['assunto'] : '');
    $mensagem = trim(isset($_POST['mensagem']) ? $_POST['mensagem'] : '');
    $remetente = filter_var(trim(isset($_POST['remetente']) ? $_POST['remetente'] : ''), FILTER_SANITIZE_EMAIL);
    $nomeRemetente = htmlspecialchars(trim(isset($_POST['nomeRemetente']) ? $_POST['nomeRemetente'] : ''), ENT_QUOTES);

//    echo($para.'<p>');
//    echo($assunto.'<p>');
//    echo($mensagem.'<p>');
//    echo($remetente.'<p>');
//    echo($nomeRemetente.'<p>');
//    exit();

    if (empty($para) || empty($assunto) || empty($mensagem)) {
        echo json_encode(['status' => 'error', 'message' => '⚠️ Parâmetros insuficientes']);
        return;
    }

    if (enviar_email_sistema($para, $assunto, $mensagem, $remetente, $nomeRemetente)) {
        echo json_encode(['status' => 'success', 'message' => 'E-mail enviado com sucesso para ' . $para]);
        return;
    }

    echo json_encode(['status' => 'error', 'message' => 'Erro ao enviar e-mail!']);
    return;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado!']);
    return;
}
