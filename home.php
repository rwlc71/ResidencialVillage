<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Residencial Village - Página Inicial</title>
    <link href="css/style.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="css/menu.css" type="text/css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

/*        #conteudo {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }*/

        h2 {
            text-align: center;
            color: #333;
        }

        img {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        ol {
            margin-left: 20px;
        }

        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .gallery img {
            width: 300px;
            height: 200px;
            object-fit: cover;
            border: 1px solid #ccc;
        }

        .quote {
            text-align: center;
            font-style: italic;
            color: #666;
            margin-top: 20px;
        }

        .highlight {
            color: #2c3e50;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="page-container">
    <?php
    session_name('SESSAO_PHP');
    include "conexao.php";
    include "topo.php";
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    ?>

    <div id="conteudo">
        <h2>Bem-vindo ao Residencial Village</h2>
        
            
            <dt><dd> O <span class="highlight">Condomínio Residencial Village Thermas das Caldas</span> é um condomínio que une conforto, 
                segurança e qualidade de vida em um dos destinos turísticos mais procurados do Brasil. 
                Localizado em Caldas Novas, famosa por suas águas termais e clima acolhedor, o residencial oferece uma experiência 
                única para moradores e visitantes. Com infraestrutura moderna e planejada, o condomínio é 
                ideal tanto para momentos de lazer quanto para a convivência familiar, proporcionando um ambiente tranquilo e agradável.<p>
            </dd></dt>
        <!-- Galeria de Imagens Dinâmicas -->
        <div class="gallery">
            <img src="images/ResidencialVillage.jpg" alt="Vista do Residencial Village">
            <img src="images/ResidencialVillage1.jpg" alt="Entrada principal do Residencial">
            <!--<img src="images/ResidencialVillage2.jpg" alt="Area interna">-->
            <img src="images/c_acesso.jfif" alt="Area interna">
        </div>

        <!-- Texto explicativo -->
        
       <p>


<p>
            <dt><dd>Além de suas acomodações, o Residencial Village conta com uma ampla gama de serviços e facilidades, 
                como áreas de lazer, segurança 24 horas e gestão eficiente de reservas e visitas. Tudo foi pensado para 
                que os proprietários possam aproveitar ao máximo seu espaço, enquanto a equipe de colaboradores garante 
                uma recepção calorosa e organizada para hóspedes e convidados. É o lugar perfeito para quem busca 
                aliar praticidade e bem-estar em meio à natureza exuberante de Caldas Novas.
                <p>
           </dd></dt>

            <p><dt><dd>O Residencial Village vai além do conforto e segurança habituais. Ele oferece uma solução robusta e inovadora 
                que permite aos proprietários um controle total de suas unidades e seus visitantes. Por meio dessa solução, 
                os proprietários podem:</p></dd></dt>

      <dt><dd><ol style="list-style-type: disc">
            <li>Gerenciar informações detalhadas sobre sua unidade.</li>
            <li>Cadastrar e acompanhar visitantes em tempo real.</li>
            <li>Controlar e atualizar informações sobre dependentes.</li>
            <li>Gerenciar locações de forma segura, prática e independente.</li>
        </ol></dd></dt> 

       <dt><dd> <p>Com essa tecnologia, o Residencial Village garante uma administração eficiente, reduzindo 
               burocracias e otimizando a segurança. Tudo foi pensado para proporcionar tranquilidade aos proprietários, 
               ao mesmo tempo em que promove uma gestão mais moderna e organizada do condomínio.</p></dd></dt> 



<!--        <p>
            <dt><dd> Com o objetivo de aumentar a segurança de acesso ao <span class="highlight">Condomínio Residencial Village Thermas das Caldas</span>, 
                esta solução foi desenvolvida para facilitar o cadastro de visitantes no nosso Condomínio.</dd></dt> 
        </p>

        <dt><dd> <ol style="list-style-type: disc">
            <li>A quantidade de chegadas/entradas previstas para o dia.</li>
            <li>A etapa e unidade.</li>
            <li>O período de locação.</li>
            <li>O proprietário do imóvel.</li>
            <li>O responsável pela locação (hóspede principal).</li>
            <li>A impressão da autorização de hospedagem.</li>
            <li>Contato do responsável pela locação.</li>
        </ol></dd></dt> 
        <dt><dd><p>Todas essas informações e documentos estarão disponíveis em tempo real.</p></dd></dt> 

        <p>
            <dt><dd>Além disso, os condôminos poderão gerenciar suas informações e:</dd></dt> 
        </p>

        <dt><dd><ol style="list-style-type: disc">
            <li>Gerenciar sua(s) unidade(s).</li>
            <li>Cadastrar dependentes.</li>
            <li>Gerenciar reservas e hóspedes.</li>
            <li>Realizar o cadastramento de animais de estimação.</li>
        </ol></dd></dt> -->

        <p class="quote">
        <dt><dd><center> <b><i>"Um condomínio não se resume a um conjunto de casas dispostas de forma ordenada. 
                    É, sobretudo, o convívio numa sociedade fechada de pessoas que idealizam e projetam para si uma melhor qualidade de vida."</i></b></center></dd></dt> 
        </p>
    </div>

    <?php
    include "rodape.php";
    ?>
</div>
</body>
</html>
