<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Inclui o arquivo senha.php apenas uma vez, relativo ao diretório atual
include_once __DIR__ . '/senha.php';
// Inclui o arquivo conexao.php apenas uma vez, subindo uma pasta
include_once __DIR__ . '/../conexao.php';

$senha = geraSenha(10);
$usuario = $_REQUEST['usuario'];

$buscaDados = "SELECT prop.nome, prop.email, usu.id_proprietario FROM proprietario prop "
        . " JOIN usuarios usu ON prop.id_proprietario = usu.id_proprietario"
        . " WHERE prop.CPF = '" . $usuario . "'";

$filtro = mysql_query($buscaDados);
if (!$filtro) {
    echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
                    <script type=\"text/javascript\">
                       alert(\"Falha ao buscar dados do usuario! \");
                    </script>";
    return die;
}

$num_rows = mysql_num_rows($filtro);
if ($num_rows == 0) {
    echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
                    <script type=\"text/javascript\">
                       alert(\"Usuário não encontrado! \");
                    </script>";
    return die;
}

$ln = mysql_fetch_array($filtro);

$email = $ln['email'];
$sql = "UPDATE usuarios SET senha = '" . $senha . "' WHERE id_proprietario = '" . $ln['id_proprietario'] . "'";

$result = mysql_query($sql);
if (!$result) {
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
// Assunto e mensagem
$assunto = "Solicitação de nova senha de acesso - Residencial Village Thermas das Caldas";
$mensagem = "
<p>Prezado condômino,</p>
<p>Foi solicitada a geração de uma nova senha através do site do condomínio.</p>
<p>A senha gerada pelo sistema é: <b>{$senha}</b></p>
<p>Acesse o sistema e realize a alteração para uma senha de sua escolha.</p>
<br>
<p>Atenciosamente,</p>
<p><b>Administração do Residencial Village Thermas das Caldas</b></p>
<p><i>Um condomínio não se resume a um conjunto de casas dispostas de forma ordenada.</i><br>
<i>É, sobretudo, o convívio numa sociedade fechada de pessoas que idealizam e projetam para si uma melhor qualidade de vida.</i></p>
";
// Monta dados para envio via PHPMailer
$dadosEmail = array(
    'para' => $ln['email'], // e-mail do destinatário
    'assunto' => $assunto,
    'mensagem' => $mensagem,
    'remetente' => 'suporteweb@residencialvillage.com.br',
    'nomeRemetente' => 'SuporteWeb - Residencial Village'
);

// Chama a função genérica de envio
enviarEmailPHPMailer($dadosEmail);

/**
 * Função genérica de envio
 */
function enviarEmailPHPMailer(array $dados) {
    $host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($path, '/'));
    $firstPart = isset($parts[0]) ? $parts[0] : '';
    $url = $host . "/" . $firstPart . "/enviarEmail.php";
    $url2 =  "/" . $firstPart . "/autentica.php";
//echo($url2);
//exit();
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // compatibilidade para PHP 5.4 e HTTPS
    $resposta = curl_exec($ch);
    curl_close($ch);
    $respostaArray = json_decode($resposta, true); // decodifica como array associativo
//   echo($respostaArray['status']);
//    exit();

    if ($respostaArray['status'] == 'success') {
        echo "<meta http-equiv='refresh' content='0;  URL={$url2}'>
            <script type=\"text/javascript\">
            alert(\"Nova senha gerada! Verifique seu e-mail!\");
            </script> ";
    } else {
        echo "<meta http-equiv='refresh' content='0;  URL={$url2}'>
            <script type=\"text/javascript\">
            alert(\"Falha ao enviar e-mail de confirmação. Contate o administrador do sistema!\");
            </script> ";
    }
}
?>