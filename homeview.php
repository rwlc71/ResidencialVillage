<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Residencial Village - Cadastro de Visitantes</title>
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link href="css/homeview.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="css/menu.css" type="text/css">
</head>

<body>
    <?php
    session_name('SESSAO_PHP');
    include "conexao.php";
    include "topo.php";
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    ?>

    <div id="conteudo">

        <div id="cont">

            <!-- <h2>Página inicial</h2> -->
            <hr>

            <a href="homeview.php" title="Voltar para a página inicial">
                <img src="images/c_acesso.jfif" alt="Imagem de Acesso" class="img-acesso">
            </a>

            <p>Este site foi criado para facilitar o cadastro de visitantes no <strong>Residencial Village Thermas das Caldas</strong>.</p>

            <p>Proprietários que alugam suas unidades podem inserir as informações da locação, como etapa, unidade, período de locação, autorização de hospedagem e dados do responsável pela locação.</p>

            <p>A ferramenta permite o gerenciamento das reservas e documentos inseridos (como arquivos PDF ou imagens). Assim, os funcionários do condomínio poderão validar a entrada de visitantes temporários verificando as informações antecipadamente.</p>

            <p>Serão verificadas as seguintes informações:</p>
            <ul>
                <li>Proprietário do imóvel</li>
                <li>Autorização de hospedagem</li>
                <li>Etapa e unidade</li>
                <li>Período de locação</li>
                <li>Responsável pela locação (hóspede principal)</li>
                <li>Contato do responsável pela locação</li>
            </ul>

            <p>Apenas <strong>condôminos do Residencial Village</strong> têm acesso ao sistema. Eles podem gerenciar:</p>
            <ul>
                <li>Etapas e unidades</li>
                <li>Dependentes</li>
                <li>Locações e reservas</li>
                <li>Animais de estimação (pets)</li>
            </ul>

            <p>Os gestores do Residencial Village poderão planejar melhor os recursos necessários com base nas informações inseridas.</p>

        </div><!-- fim div cont -->

    </div><!-- fim div conteudo -->

    <?php include "rodape.php"; ?>

</body>

</html>
