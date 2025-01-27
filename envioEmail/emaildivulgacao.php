<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include "../conexao.php";
$query = mysql_query('SELECT * FROM envio');
$total = mysql_num_rows($query);
$totalVoltas = 75;
$contador = 0;

if ($total < $totalVoltas) {
    $totalVoltas = $total;
}
echo('$totalVoltas: ' . $totalVoltas . '<p>');
echo('$total: ' . $total . '<p>');
//if ($totalVoltas <= $total) {

$filtro = mysql_query("SELECT * FROM envio ");

while ($ln = mysql_fetch_array($filtro)) {
//        echo('$ln: ' . var_dump($ln) . '<p>');
//        exit();
//        while ($voltas < $totalVoltas) { // envia 88 emails a cada loop

    $nome = $ln['nome'];
    $email = $ln['email'];
    $id_proprietario = $ln['id_proprietario'];
    $cpf = $ln['CPF'];

    // rotina para envio de e-mail ao proprietário e a adminstração        
    $dt_cadastro = date('d/m/Y - H:i:s');
    $quebra_linha = "\r\n"; // windows


    $formato = "MINE-Version: 1.1" . $quebra_linha;
    $formato .= "Content-Type: text/html; charset=UTF-8" . $quebra_linha;
    $formato .= "From: " . "Residencial Village Thermas das Caldas <residencial_village@1portodos.com.br>" . $quebra_linha;
    $formato .= "Return-Path: " . "residencial_village@1portodos.com.br" . $quebra_linha;

    // Montar a mensagem de boas-vindas
    $assunto = "🌟 Bem-vindo a Solução WEB do Residencial Village Thermas das Caldas! 🌟";
    $menssagem = "<html>
                <head>
                  <title>Bem-vindo!</title>
                </head>
                <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
                  <h2>Prezado(a) Sr.(a)  $nome !!</h2>

                  <p>É com muita satisfação que anunciamos que o nosso condomínio agora conta com um sistema exclusivo para facilitar sua rotina e melhorar nossa comunicação! 🎉</p>

                  <p>Com o sistema, você poderá:</p>
                  <ul>
                    <li><strong>Gerenciar </strong>informações detalhadas sobre sua unidade.</li>
                    <li><strong>Cadastrar </strong>e acompanhar visitantes em tempo real.</li>
                    <li><strong>Controlar e atualizar </strong>informações sobre dependentes.</li>
                    <li><strong>Receber avisos e comunicados </strong>em tempo real.</li>
                    <li><strong>Consultar documentos importantes</strong>, como atas de reuniões e regulamentos</li>
                    <li><strong>Gerenciar locações</strong> de forma segura, prática e independente.</li>
                  </ul>

                  <p>Seu usuário e senha de acesso são:</p>
                  <ul>
                    <li><strong>Usuário: </strong>$cpf</li>
                    <li><strong>Senha: </strong>$cpf</li>
                  </ul>
                  <p>Para alterar a sua senha, basta acessar a opção correspondente no menu de Autenticação.</p>
                  <p>Lembre-se de que, ao utilizar o sistema, é necessário concordar com nossa <a href='https://1portodos.com.br/ResidencialVillage/politicaprivacidade.php' style='color: #0066cc; text-decoration: none;'><b>Política de Privacidade!<b></p>
                  <p>Acesse agora pelo link abaixo:</p>
                  <li><a href='https://1portodos.com.br/ResidencialVillage/home.php' style='color: #0066cc; text-decoration: none;'>https://1portodos.com.br/ResidencialVillage/home.php</a></li>

                  <p><em>Em caso de dúvidas, estamos à disposição para ajudar pelo e-mail residencialvillage.caldas@gmail.com ou pelo telefone (64)3453-0644/98420-0801.</em></p>

                  <p>Contamos com sua participação para tornar nossa convivência ainda melhor. 😊</p>

                  <p>Atenciosamente,<br>
                    <p><b> Administração do Residencial Village Thermas das Caldas</b>

                </body>
                </html>";
//    echo("$menssagem");
//            exit();
          $envio = mail($email, $assunto, $menssagem, $formato, "-fresidencial_village@1portodos.com.br");
//    $envio2 = mail("rogerio@1portodos.com.br", $assunto, $menssagem, $formato, "-fresidencial_village@1portodos.com.br");

    if (!$envio2) {
        $situacao = 'enviado com sucesso';
    } else {
        $situacao = 'Falha no envio do e-mail';
    }
        echo("Foi enviado e-mail para $nome. Data/Hora:$dataHoje - Situação: $situacao <p>");

    // Registrar o status no banco de dados
    date_default_timezone_set('America/Sao_Paulo');
    $dataHoje = date('Y-m-d H:i:s');
    $sqlUpdate = "INSERT INTO enviomsg (id_proprietario, proprietario, email, situacao, data)
                 VALUES ('" . $id_proprietario . "','" . $nome . "','" . $email . "','" . $situacao . "','" . $dataHoje . "')";
    $result = mysql_query($sqlUpdate);
    // deletar de envio
    $sql = mysql_query("DELETE FROM envio WHERE id_proprietario = '" . $id_proprietario . "'");

    $contador++;
    if ($contador == $totalVoltas) {
        echo "O contador atingiu o limite e a previsto de $totalVoltas voltas e a rotina será encerrada. \n";
        break; // Sai da rotina
    }
}
//================================= 
//    }// end while
//}// end while
//
echo("Saiu");

//$inicial = $inicial + 1;

exit();
?>