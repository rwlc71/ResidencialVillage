<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/componentes.js"></script>
<div id="conteudo">
<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";
?>


    <div id="cont">

        <h2>Convênios e Parcerias</h2>

        <hr>

        <font size="3"; color="#000000"><p>
            Uma de nossas vantagens exclusivas é oferecer aos nossos associados convênios e parcerias em diversas áreas de atuação.<p>

            Confira abaixo os serviços oferecidos e seus contatos:
        <hr>
        <dl>
            <dt><dd>
                <div style="max-height: 600px; overflow-y: auto;">
                    <table border="0" >
                        <tr>
                            <td align="center" valign="middle" bgcolor="#E9E9E9">
                                <a href="images/parceiros/empadas.jpeg" target="_blank">
                                    <img src="images/parceiros/empadas.jpeg" height=200 width=200 align='middle' border="0">
                                </a>
                            </td>    
                            <td align="center">
                                <font size="3" color="#000000">
                        <center>
                            <b><h2>EMPADAS DA CLEIA !!!</h2></b><hr>
                            <ul><h3>
                                <li>Residencial Village - Etapa Azaléia</li>
                                <li>Caldas Novas - GO</li>
                                <li>(61) 98307-8521</li>
                                <li><a href="https://api.whatsapp.com/send?phone=5561983078521" target="_blank"> 
                                        <img src="images/parceiros/whatsapp.png" height=40 width=40 align='middle' border="0">
                                    </a>
                                </li>
                            </h3></ul>
                        </center>
                        </td>
                        </tr>
                        <tr>
                            <td align="center" valign="middle" bgcolor="#E9E9E9">
                                <a href="images/parceiros/pizza.jpg" target="_blank">
                                    <img src="images/parceiros/pizza.jpg" height=200 width=200 align='middle' border="0">
                                </a>
                            </td>    
                            <td align="center">
                                <font size="3" color="#000000">
                        <center>
                            <b><h2>PIZZAS DA MARLI !!!</h2></b><hr>
                            <ul><h3>
                                <li>Residencial Village - Etapa Bougainville</li>
                                <li>Caldas Novas - GO</li>
                                <li>(62) 98173-7397</li>
                                <li><a href="https://api.whatsapp.com/send?phone=5562981737397" target="_blank"> 
                                        <img src="images/parceiros/whatsapp.png" height=40 width=40 align='middle' border="0">
                                    </a>
                                </li>
                                </h3></ul>
                        </center>
                        </td>
                        </tr>
                        <tr>
                            <td align="center" valign="middle" bgcolor="#E9E9E9">
                                <a href="images/parceiros/images.png" target="_blank">
                                    <img src="images/parceiros/images.png" height=200 width=200 align='middle' border="0">
                                </a>
                            </td>    
                            <td align="center">
                                <font size="3" color="#000000">
                        <center>
                            <b><h2>SERVIÇOS DE LIMPEZA E MANUTENÇÃO RESIDENCIAL</h2></b><hr>
                            <ul><h3>
                                <li>Endereço</li>
                                <li>Caldas Novas - GO</li>
                                <li>contato</li>
                                <li><a href="https://api.whatsapp.com/send?phone=55xxxxx" target="_blank"> 
                                        <img src="images/parceiros/whatsapp.png" height=40 width=40 align='middle' border="0">
                                    </a>
                                </li>
                                </h3></ul>
                        </center>
                        </td>
                        </tr>
                                                <tr>
                            <td align="center" valign="middle" bgcolor="#E9E9E9">
                                <a href="images/parceiros/novos.png" target="_blank">
                                    <img src="images/parceiros/novos.png" height=200 width=200 align='middle' border="0">
                                </a>
                            </td>    
                            <td align="center">
                                <font size="3" color="#000000">
                        <center>
                            <b><h2>INSERIR NOVOS PARCEIROS</h2></b><hr>
                            <ul><h3>
                                <li>Endereço</li>
                                <li>Caldas Novas - GO</li>
                                <li>contato</li>
                                <li><a href="https://api.whatsapp.com/send?phone=55xxxxx" target="_blank"> 
                                        <img src="images/parceiros/whatsapp.png" height=40 width=40 align='middle' border="0">
                                    </a>
                                </li>                           
                                </h3></ul>
                        </center>
                        </td>
                        </tr>
                    </table>
                </div>




        </dl>
        </dt></dd>




    </div><!-- fim div cont -->

</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>