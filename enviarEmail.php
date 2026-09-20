<?php

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

require_once 'Mailer/PHPMailerAutoload.php';

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

    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP
        $mail->isSMTP();
        $mail->Host = 'email-ssl.com.br';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'suporteweb@residencialvillage.com.br';
        $mail->Password = 'supWVillage@2025';
//        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPSecure = 'tls'; // ou 'ssl' dependendo do servidor
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;

        // Remetente e destinatário
        $mail->setFrom($remetente ?: 'suporteweb@residencialvillage.com.br', $nomeRemetente ?: 'SuporteWeb - Residencial Village');
        $mail->addAddress($para);

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $mensagem;
        $mail->AltBody = strip_tags($mensagem);

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'E-mail enviado com sucesso para ' . $para]);
        return;
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => '❌ Erro ao enviar e-mail!' . $mail->ErrorInfo]);
        return;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Acesso negado!']);
    return;
}
