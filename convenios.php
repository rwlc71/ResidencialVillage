<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/componentes.js"></script>
<style>
    /* Estilos do modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        padding-top: 20px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        font-family: Arial, sans-serif; /* Fonte Arial */

    }
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 60%;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover, .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
<script>
    // Função para mostrar o modal
    function showModal(modalId) {
        document.getElementById(modalId).style.display = "block";
    }

    // Função para esconder o modal
    function hideModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }
</script>
<div id="conteudo">
    <?php
    session_name('SESSAO_PHP');
    include "conexao.php";
//    include "valida/verifica_autenticacao.php";
    include "valida/valida_cpf.php";
    include "valida/mascaraCPF.php";
    include "valida/mascaras.php";
    include "topo.php";
    ?>
    <div id="conteudo">
        <div id="cont">
            <p>
            <h2>Convênios e Serviços</h2>

            <hr>

            <font size="3"; color="#000000"><p>
                Uma de nossas vantagens exclusivas é oferecer ao nosso público interno e visitantes serviços e parcerias em diversas áreas de atuação proporcionados
                por moradores do Residencial Village, incentivando assim a ajuda colaborativa em nossa sociedade.<p>
                Confira abaixo os serviços oferecidos e seus contatos:
            <hr>
            <dl>
                <dt><dd>
                    <div class="estiloTabelas table-responsive">

                        <div style="max-height: 600px; overflow-y: auto;">
                            <table border="0" >
                                <!-- ====================================================== -->
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#E9E9E9">
                                        <a href="images/parceiros/gas.gif" target="_blank">
                                            <img src="images/parceiros/gas.gif" height=200 width=200 align='middle' border="0"
                                                 onmouseover="showModal('modal0')" onmouseout="hideModal('modal0')">
                                        </a>
                                    </td>    
                                    <td align="center">
                                        <font size="3" color="#000000">
                                <center>
                                    <b><h2>REVENDEDORES DE GÁS EM CALDAS NOVAS!!</h2></b>
                                    <hr>
                                         
                                        <dd><li type="square" align='left' disc><b><i>SETA GÁS<i></b>  - Contate-nos pelo WhatsApp:(64)94315995
                                        <a href="https://api.whatsapp.com/send?phone=556494315995" target="_blank"> 
                                            <img src="images/parceiros/whatsapp.jpg"  height=25 width=25 align='middle' border="0" title="SETA GÁS!">
                                        </a></li>

                                        <li type="square" align='left' disc><b><i>Gás 7000<i></b>  - Contate-nos pelo WhatsApp: (64) 3455-7000
                                        <a href="https://api.whatsapp.com/send?phone=556434557000" target="_blank"> 
                                            <img src="images/parceiros/whatsapp.jpg"  height=25 width=25 align='middle' border="0" title="Gás 7000!">
                                        </a></li>

                                        <li type="square" align='left' disc><b><i>Gás Vitória <i></b>  - Contate-nos pelo WhatsApp:(64)99240-2285
                                        <a href="https://api.whatsapp.com/send?phone=5564992402285" target="_blank"> 
                                            <img src="images/parceiros/whatsapp.jpg"  height=25 width=25 align='middle' border="0" title="Gás Vitória | Gás de Cozinha e Gás Industrial">
                                        </a></li> 
                                        
                                        <li type="square" align='left' disc><b><i>FORTEGÁS  <i></b>  - Contate-nos pelo WhatsApp:(64)992692213
                                        <a href="https://api.whatsapp.com/send?phone=5564992692213" target="_blank"> 
                                            <img src="images/parceiros/whatsapp.jpg"  height=25 width=25 align='middle' border="0" title="FORTEGÁS ">
                                        </a></li> 
                                        </dd>
                                    </ul>
                                </center>
                                </td>
                                </tr>
                                <!-- ====================================================== -->

                                <tr>
                                    <td align="center" valign="middle" bgcolor="#E9E9E9">
                                        <a href="images/parceiros/faztudo.jpg" target="_blank">
                                            <img src="images/parceiros/faztudo.jpg" height=200 width=200 align='middle' border="0"
                                                 onmouseover="showModal('modal0')" onmouseout="hideModal('modal0')">
                                        </a>
                                    </td>    
                                    <td align="center">
                                        <font size="3" color="#000000">
                                <center>
                                    <b><h2>MARIDO DE ALUGUEL!!</h2></b>
                                    <ul><h3> <li>Residencial Village - Etapa Pitangueiras casa 81</li></h3><hr>
                                        <li></li>  
                                        <dt><dd><li type="disc" align='left'>Serviços em geral!</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Eletricista</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Reparos hidráulicos</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Pintura</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Iluminação decorativa</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Para mais informações entre em contato pelo pelo WhatsApp!</li> </dd></dt> 

                                        <p><dt><dd><li align='left'><b>Contate-nos pelo WhatsApp:(35)90135653</b> 
                                            <a href="https://api.whatsapp.com/send?phone=5535991035653" target="_blank"> 
                                                <img src="images/parceiros/whatsapp.jpg" align='middle' height=20 width=20 align='middle' border="0" title="Clique aqui e marque uma visita!">
                                            </a>
                                        </li></dd></dt> 
                                    </ul>
                                </center>
                                </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#E9E9E9">
                                        <a href="images/parceiros/mimone.jpg" target="_blank">
                                            <img src="images/parceiros/mimone.jpg" height=200 width=200 align='middle' border="0"
                                                 onmouseover="showModal('modal0')" onmouseout="hideModal('modal0')">
                                        </a>
                                    </td>    
                                    <td align="center">
                                        <font size="3" color="#000000">
                                <center>
                                    <b><h2>MIMONE Artesanato!!</h2></b>
                                    <ul><h3> <li>Residencial Village</li></h3><hr>
                                        <li></li>  
                                        <dt><dd><li type="disc" align='left'>Colares de Mesa Decorativos e Enfeite de Porta!</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Envio para todo BR!</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Atendimento de Segunda a Sexta!</li> </dd></dt> 
                                        <dt><dd><li type="disc" align='left'>Para mais informações entre em contato pelo Direct!</li> </dd></dt> 

                                        <p><dt><dd><li align='left'><b>Siga-nos no Instagram:</b> 
                                            <a href="https://www.instagram.com/mimoneartesanato/?igsh=dHRlb3FjZTdiMmJl#" target="_blank"> 
                                                <img src="images/parceiros/instragram.jpg" align='middle' height=20 width=20 align='middle' border="0" title="Clique e entre em contato pelo Direct!">
                                            </a>
                                        </li></dd></dt> 
                                    </ul>
                                </center>
                                </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#E9E9E9">
                                        <a href="images/parceiros/pizza.jpg" target="_blank">
                                            <img src="images/parceiros/pizza.jpg" height=200 width=200 align='middle' border="0"
                                                 onmouseover="showModal('modal1')" onmouseout="hideModal('modal1')">
                                        </a>
                                    </td>    
                                    <td align="center">
                                        <font size="3" color="#000000">
                                <center>
                                    <b><h2>PIZZAS DA MARLI !!</h2></b>
                                    <ul><h3> <li>Residencial Village - Etapa Bougainville casa 11</li></h3><hr>
                                        <li></li>  
                                        <dt><dd><li align='left'>Temos pizzas de diversos sabores, pães e biscoitos de queijo!</li> </dd></dt> 
                                        <p><dt><dd><li align='left'><b> Contate-nos pelo WhatsApp:(62)98173-7397</b>
                                            <a href="https://api.whatsapp.com/send?phone=5562981737397" target="_blank"> 
                                                <img src="images/parceiros/whatsapp.png" align='middle' height=20 width=20 align='middle' border="0" title="Clique aqui e encomende sua pizza!">
                                            </a>
                                        </li></dd></dt> 
                                    </ul>
                                </center>
                                </td>
                                </tr>

                                <tr>
                                    <td align="center" valign="middle" bgcolor="#E9E9E9">
                                        <a href="images/parceiros/empadas.jpeg" target="_blank">
                                            <img src="images/parceiros/empadas.jpeg" height=200 width=200 align='middle' border="0"
                                                 onmouseover="showModal('modal2')" onmouseout="hideModal('modal2')">
                                        </a>
                                    </td>    
                                    <td align="center">
                                        <font size="3" color="#000000">
                                <center>
                                    <b><h2>EMPADAS DA CLEIA !!</h2></b>
                                    <ul><h3> <li></li></h3><hr>
                                        <li></li>  
                                        <dt><dd><li align='left'>Fazemos empadas, coxinhas e atendemos encomendas!</li> </dd></dt>
                                        <p> <dt><dd><li align='left'><b>Contate-nos pelo WhatsApp:(62)98173-7397</b>
                                            <a href="https://api.whatsapp.com/send?phone=5561983078521" target="_blank"> 
                                                <img src="images/parceiros/whatsapp.png" height=20 width=20 align='middle' border="0" title="Clique aqui e encomende suas empadas!">
                                            </a>
                                        </li></dd></dt>
                                    </ul>
                                </center>
                                </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="middle" bgcolor="#E9E9E9">
                                        <a href="images/parceiros/images.png" target="_blank">
                                            <img src="images/parceiros/faxina.jpg" height=200 width=200 align='middle' border="0"
                                                 onmouseover="showModal('modal3')" onmouseout="hideModal('modal3')">
                                        </a>
                                    </td>    
                                    <td align="center">
                                        <font size="3" color="#000000">
                                <center>
                                    <b><h2>FAXINA RESIDÊNCIAL </h2></b>
                                    <ul><h3> <li></li></h3>
                                        <ul><h3> <li>Residencial Village - Etapa Pitangueiras casa 35</li></h3><hr>
                                            <dt><dd><li align='left'>Limpeza de todos os ambientes da casa, como quartos, sala, cozinha, banheiros, varanda, área de serviço e garagem!</li></dd></dt> 
                                            <p><dt><dd><li align='left'><b>Contate-nos pelo WhatsApp: (62)999689135</b>
                                                <a href="https://api.whatsapp.com/send?phone=5562999689135" target="_blank"> 
                                                    <img src="images/parceiros/whatsapp.png" height=20 width=20 align='middle' border="0" title="Clique e nos contate!">
                                                </a></dd></dt> 
                                            </li>
                                        </ul>
                                </center>
                                </td>
                                </tr>

                                <tr>
                                    <td align="center" valign="middle" bgcolor="#E9E9E9">
                                        <a href="images/parceiros/images.png" target="_blank">
                                            <img src="images/parceiros/revenda.jpg" height=200 width=200 align='middle' border="0"
                                                 onmouseover="showModal('modal5')" onmouseout="hideModal('modal5')">
                                        </a>
                                    </td>    
                                    <td align="center">
                                        <font size="3" color="#000000">
                                <center>
                                    <b><h2> BOTICARIO, EUDORA E O.U.I</h2></b>
                                    <ul><h3> <li>Residencial Village - Etapa Pitangueiras casa 35</li></h3><hr>
                                        <dt><dd><li align='left'>Aqui você encontra perfumaria, cuidados pessoais, corpo e banho, produtos para cabelo, kit presente, maquiagem e muito mais! Aproveite os descontos e compre agora mesmo!
                                        </li> </dd></dt>
                                        <p><dt><dd><li align='left'><b>Contate-nos pelo WhatsApp: (62)999689135</b>
                                            <a href="https://api.whatsapp.com/send?phone=5562999689135" target="_blank"> 
                                                <img src="images/parceiros/whatsapp.png" height=20 width=20 align='middle' border="0" title="Clique e nos contate!">
                                            </a>
                                        </li></dd></dt>
                                    </ul>
                                </center>
                                </td>
                                </tr>
                            </table>
                        </div>
                    </div>



            </dl>
            </dt></dd>

        </div><!-- fim div cont -->
    </div> <!-- fim div conteudo -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>
<!-- Modal 1 -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('modal1')">&times;</span>
        <h2>PIZZAS DA MARLI!!</h2>
        <p><strong>Mini pizzas de frango, calabresa e presunto:</strong> R$ 5,00 cada.</p>
        <p><strong>Biscoito de queijo - pacote:</strong> R$ 23,00.</p>
        <p><strong>Pão de queijo - pacote:</strong> R$ 23,00.</p>
        <p><strong>Contato: </strong> Marli</p>
    </div>
</div>

<!-- Modal 2 -->
<div id="modal2" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('modal2')">&times;</span>
        <h2>EMPADAS CLEIA</h2>
        <p><strong>Produtos:</strong> Empadas, coxinhas e salgados de sabores diversos:
        <li>Frango com requeijão </li>
        <li> Carne de sol na nata</li>
        <li> Calabresa com requeijão </li>
        <li> Palmito</li>
        <li> Chocolate </li>
        <p><strong>Reserve já o seu! </strong> </p>
        <p><strong>Contato: </strong> Cleia</p>

    </div>
</div>
<div id="modal3" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('modal2')">&times;</span>
        <h2>FAXINA RESIDÊNCIAL</h2>
        <p><strong>Contato: </strong> Daniele</p>
        <p><strong>Faxina/Limpeza Residencial - Valor:</strong> R$ 150,00.</p>
    </div>
</div>

<div id="modal5" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('modal2')">&times;</span>
        <h2>REVENDERORA BOTICARIO, EUDORA E O.U.I</h2>
        <p><strong>Aqui você encontra perfumaria, cuidados pessoais, corpo e banho, produtos para cabelo, kit presente, maquiagem e muito mais! Aproveite os descontos e compre agora mesmo! </strong> </p>


        <p><strong>Contato: </strong> Daniele</p>
    </div>
</div>






