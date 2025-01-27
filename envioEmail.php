<?php

// Configuração do banco de dados
$host = 'localhost';
$dbname = 'bdcasasvillage';
$user = 'root';
$password = '';

//$host = 'bdcasasvillage.mysql.dbaas.com.br';
//$dbname = 'bdcasasvillage';
//$user = 'bdcasasvillage';
//$password = 'Village@2024';

try {
    // Conexão com o banco de dados usando PDO

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql_count = "SELECT COUNT(*) AS total FROM proprietario";
//    $conn = new mysqli($host, $user, $password, $dbname);
    $stmt = $pdo->query($sql_count);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo($row[0]['total']);


    // Consulta para buscar os dados de proprietários
    $sql = "SELECT id_proprietario, nome, CPF, email FROM proprietario";
    $stmt = $pdo->query($sql);
    $proprietarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Configuração do remetente
    $remetenteEmail = "seuemail@dominio.com";
    $remetenteNome = "Seu Nome ou Empresa";
    $contador = 0;
    $situacao = 'Falha no envio do e-mail';

//    while ($contador < 5) {
        foreach ($proprietarios as $proprietario) {

            $contador++;

            $nome = $proprietario['nome'];
            $email = $proprietario['email'];
            $id_proprietario = $proprietario['id_proprietario'];
            $cpf = $proprietario['CPF'];

            // rotina para envio de e-mail ao proprietário e a adminstração        
            $dt_cadastro = date('d/m/Y - H:i:s');
            $quebra_linha = "\r\n"; // windows


            $formato = "MINE-Version: 1.1" . $quebra_linha;
            $formato .= "Content-Type: text/html; charset=UTF-8" . $quebra_linha;
            $formato .= "From: " . "Residencial Village Thermas das Caldas <residencial_village@1portodos.com.br>" . $quebra_linha;
            $formato .= "Return-Path: " . "residencial_village@1portodos.com.br" . $quebra_linha;

            // Montar a mensagem de boas-vindas
            $assunto = "🌟 Bem-vindo ao Sistema do Residencial Village Thermas das Caldas! 🌟";
            $menssagem = "<html>
                <head>
                  <title>Bem-vindo ao Sistema do Condomínio</title>
                </head>
                <body style='font-family: Arial, sans-serif; line-height: 1.6;'>
                  <h2>Prezado Sr.(a)  $nome !!</h2>

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
                  <p>Acesse agora pelo link abaixo:</p>
                  <p><a href='https://1portodos.com.br/ResidencialVillage/home.php' style='color: #0066cc; text-decoration: none;'>https://1portodos.com.br/ResidencialVillage/home.php</a></p>

                  <p><em>Em caso de dúvidas, estamos à disposição para ajudar pelo e-mail residencialvillage.caldas@gmail.com ou pelo telefone (64)3453-0644/98420-0801.</em></p>

                  <p>Contamos com sua participação para tornar nossa convivência ainda melhor. 😊</p>

                  <p>Atenciosamente,<br>
                    <p><b> Administração do Residencial Village Thermas das Caldas</b>

                </body>
                </html>";
            echo("$menssagem");
//            exit();
//          $envio = mail($email, $assunto, $mensagem, $formato, "-fresidencial_village@1portodos.com.br");
            $envio2 = mail("rogerio@1portodos.com.br", $assunto, $mensagem, $formato, "-fresidencial_village@1portodos.com.br");

            if (!$envio2) {
                $situacao = 'enviado com sucesso';
            } else {
                $situacao = 'Falha no envio do e-mail';
            }

//            if (!$envio2) {
//                $confirma = "Ocorrência realizada com sucesso! No entanto, tivemos um problema ao enviar o e-mail "
//                        . "de confirmação. Por favor, verifique se o endereço de e-mail informado está correto. "
//                        . "Caso precise de ajuda, entre em contato "
//                        . "com o nosso suporte.";
//                echo "<meta http-equiv='refresh' content='0; '>
//                    <script type=\"text/javascript\">
//                    alert(\"$confirma\");
//                    </script>
//                  ";
//                return die;
//            } else {
//                echo "<meta http-equiv='refresh' content='0; '>
//                    <script type=\"text/javascript\">
//                    alert(\"Ocorrência registrada com sucesso!  \");
//                    </script>
//                    ";
//                return die;
//            }
            // Registrar o status no banco de dados
            date_default_timezone_set('America/Sao_Paulo');
            $dataHoje = date('Y-m-d H:i:s');
            $sqlUpdate = "INSERT INTO enviomsg (id_proprietario, proprietario, email, situacao, data)
                 VALUES ('" . $id_proprietario . "','" . $nome . "','" . $email . "','" . $situacao . "','" . $dataHoje . "')";
            echo($sqlUpdate);
            $stmtUpdate = $pdo->prepare($sqlUpdate);
//            $stmtUpdate->bindParam(':status', $status, PDO::PARAM_INT);
//            $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            if ($contador == 5) {
                echo "O contador atingiu o limite e a rotina será encerrada. \n";
                $sqlultimo = "INSERT INTO ultimoenviado (ultimo, contador)
                 VALUES ('" . $id_proprietario . "','" . $contador . "')";
                echo($sqlultimo);
                $stmtUpdate = $pdo->prepare($sqlultimo);
//                $stmtUpdate->bindParam(':status', $status, PDO::PARAM_INT);
//                $stmtUpdate->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtUpdate->execute();
                break; // Sai da rotina
            }

            // Incrementa o contador
        }
//    }

    echo "Processo de envio de e-mails concluído.";
    echo "Último e-mail enviado para $nome";
} catch (PDOException $e) {
    echo "Erro no banco de dados: " . $e->getMessage();
} catch (Exception $e) {
    echo "Erro geral: " . $e->getMessage();
}
