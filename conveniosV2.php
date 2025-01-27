<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propagandas com Modal</title>
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
</head>
<body>

<div style="max-height: 600px; overflow-y: auto;">
    <table border="0">
        <!-- Primeira propaganda -->
        <tr>
            <td align="center" valign="middle" bgcolor="#E9E9E9">
                <a href="images/parceiros/pizza.jpg" target="_blank">
                    <img src="images/parceiros/pizza.jpg" height="200" width="200" align="middle" border="0"
                         onmouseover="showModal('modal1')" onmouseout="hideModal('modal1')">
                </a>
            </td>
            <td align="center">
                <font size="3" color="#000000">
                    <center>
                        <b><h2>PIZZAS MARLI !!!</h2></b>
                        <ul>
                            <h3><li>Residencial Village - Etapa Bougainville casa xx</li></h3>
                            <hr>
                            <li align='left'>Temos pizzas de diversos sabores, pães de queijo e quitutes em geral!</li>
                            <li align='left'>Contato: (62) 98173-7397
                                <a href="https://api.whatsapp.com/send?phone=5562981737397" target="_blank">
                                    <img src="images/parceiros/whatsapp.png" height="20" width="20" align="middle"
                                         border="0" title="Clique aqui e encomende sua pizza!">
                                </a>
                            </li>
                        </ul>
                    </center>
                </font>
            </td>
        </tr>
        
        <!-- Segunda propaganda -->
        <tr>
            <td align="center" valign="middle" bgcolor="#E9E9E9">
                <a href="images/parceiros/empadas.jpeg" target="_blank">
                    <img src="images/parceiros/empadas.jpeg" height="200" width="200" align="middle" border="0"
                         onmouseover="showModal('modal2')" onmouseout="hideModal('modal2')">
                </a>
            </td>
            <td align="center">
                <font size="3" color="#000000">
                    <center>
                        <b><h2>EMPADAS CLEIA !!!</h2></b>
                        <ul>
                            <h3><li>Residencial Village - Etapa Azaléia casa xx</li></h3>
                            <hr>
                            <li align='left'>Fazemos empadas, coxinhas e atendemos encomendas!</li>
                            <li align='left'>Contato: (62) 98173-7397
                                <a href="https://api.whatsapp.com/send?phone=5561983078521" target="_blank">
                                    <img src="images/parceiros/whatsapp.png" height="20" width="20" align="middle"
                                         border="0" title="Clique aqui e encomende suas empadas!">
                                </a>
                            </li>
                        </ul>
                    </center>
                </font>
            </td>
        </tr>
    </table>
</div>

<!-- Modal 1 -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('modal1')">&times;</span>
        <h2>PIZZAS MARLI</h2>
        <p><strong>Produtos:</strong> Pizzas de diversos sabores, pães de queijo, quitutes em geral.</p>
        <p><strong>Valores:</strong> R$ 30 - R$ 50</p>
    </div>
</div>

<!-- Modal 2 -->
<div id="modal2" class="modal">
    <div class="modal-content">
        <span class="close" onclick="hideModal('modal2')">&times;</span>
        <h2>EMPADAS CLEIA</h2>
        <p><strong>Produtos:</strong> Empadas, coxinhas, salgados.</p>
        <p><strong>Valores:</strong> R$ 5 - R$ 20</p>
    </div>
</div>

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

</body>
</html>
