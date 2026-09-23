<?php

if (!function_exists('enviar_email_sistema')) {

function enviar_email_sistema($para, $assunto, $mensagemHtml, $remetente = '', $nomeRemetente = '')
{
    $para = trim($para);
    if ($para === '' || !filter_var($para, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $autoload = dirname(__FILE__) . '/../Mailer/PHPMailerAutoload.php';
    if (!is_readable($autoload)) {
        error_log('enviar_email_sistema: PHPMailer não encontrado em ' . $autoload);
        return false;
    }
    require_once $autoload;

    $fromEmail = 'suporteweb@residencialvillage.com.br';
    $fromName = $nomeRemetente !== '' ? $nomeRemetente : 'Residencial Village Thermas das Caldas';
    if ($remetente !== '' && filter_var($remetente, FILTER_VALIDATE_EMAIL)) {
        $fromEmail = $remetente;
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'email-ssl.com.br';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'suporteweb@residencialvillage.com.br';
        $mail->Password = 'supWVillage@2025';
        $mail->SMTPSecure = 'tls';
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addReplyTo('residencialvillage.caldas@gmail.com', 'Administração Residencial Village');
        $mail->addAddress($para);
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body = $mensagemHtml;
        $mail->AltBody = trim(strip_tags(str_replace(array('<br>', '<br/>', '<br />', '</p>', '<p>'), array("\n", "\n", "\n", "\n", ''), $mensagemHtml)));
        $mail->send();
        return true;
    } catch (Exception $e) {
        $detalhe = isset($mail->ErrorInfo) ? $mail->ErrorInfo : $e->getMessage();
        error_log('enviar_email_sistema: ' . $detalhe);
        return false;
    }
}

}
