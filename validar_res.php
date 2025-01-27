<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="css/style.css" type="text/css" rel="stylesheet">
        <link rel="stylesheet" href="css/menu.css" type="text/css">
        <script type="text/javascript" src="js/componentes.js"></script>
        <style>
            /* Esconde conteúdo original em dispositivos móveis */
            @media (max-width: 600px) {
                #conteudo, #topo, #rodape {
                    display: none;
                }
                /* Configura modal para ser exibido automaticamente */
                #modal {
                    display: flex;
                }
            }

            /* Estilos para o modal */
            .modal {
                display: none; /* Modal escondido por padrão */
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
                z-index: 9999;
                font-family: Arial, sans-serif; /* Fonte Arial */

            }

            .modal-content {
                background-color: #fff;
                padding: 20px;
                width: 90%;
                max-width: 400px;
                border-radius: 8px;
                text-align: center;
            }

            /* Estilo para o campo e botão dentro do modal */
            .modal-content input[type="text"] {
                width: 100%;
                padding: 8px;
                font-size: 1em;
                margin: 10px 0;
            }

            .modal-content input[type="submit"] {
                width: 100%;
                padding: 10px;
                font-size: 1em;
            }
        </style>
    </head>
    <body>
        <?php
        session_name('SESSAO_PHP');
        include "conexao.php";
        include "valida/verifica_autenticacao.php";
        include "valida/valida_cpf.php";
        include "valida/mascaraCPF.php";
        include "valida/mascaras.php";
        include "topo.php";
        ?>

        <div id="conteudo">
            <div id="cont">
                <h2>Validar Autorização de Hospedagem</h2>
                <hr>
                <form method="post" action="creserva_res.php" enctype="multipart/form-data">
                    <table width="75%" border="0">
                        <tr>
                            <th width="6%" align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Código da autorização:</font></th>
                            <th width="25%" align="left" scope="col"><input type="text" name="codigo" size="30" maxlength="30" /></th>
                        </tr>
                    </table>
                    <center>
                        <input type="submit" name="botao" value="Consultar" />
                    </center>
                </form>
                <hr>
            </div>
        </div>

        <?php include "rodape.php"; ?>

        <!-- Modal para dispositivos móveis -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <h2>Código a autorização</h2>
                <form method="post" action="creserva_res.php" enctype="multipart/form-data">
                    <input type="text" name="codigo" size="30" maxlength="30" placeholder="Código da autorização" />
                    <input type="submit" name="botao" value="Consultar" />
                </form>
            </div>
        </div>

        <script>
            // Verifica o tamanho da tela e exibe o modal em dispositivos móveis
            function checkScreenSize() {
                const modal = document.getElementById('modal');
                if (window.innerWidth <= 600) {
                    modal.style.display = 'flex';  // Exibe o modal em dispositivos móveis
                } else {
                    modal.style.display = 'none';  // Esconde o modal em telas maiores
                }
            }

            // Executa a verificação ao carregar a página e ao redimensionar a janela
            window.onload = checkScreenSize;
            window.onresize = checkScreenSize;
        </script>
    </body>
</html>
