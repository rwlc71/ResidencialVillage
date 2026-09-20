<!-- Adicionar o CSS -->
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!-- Adicionar o jQuery -->
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

 Adicionar o jQuery UI (após o jQuery) 
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

<!-- Scripts adicionais -->
<!--<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/componentes.js"></script>

Scripts utilizados no autocomplete 
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/thickbox-compressed.js"></script>
<script type="text/javascript" src="jquery-autocomplete/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/jquery.autocomplete.css"/>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/lib/thickbox.css"/>-->

<!-- Inicializar os plugins jQuery -->
<script type="text/javascript">
    $(document).ready(function () {
        // Autocomplete para nome
        $("#txtNome").autocomplete("completar_nome.php", {
            width: 310,
            selectFirst: false
        });

        // Autocomplete para CPF
        $("#txtCPF").autocomplete("completar_cpf.php", {
            width: 310,
            selectFirst: false
        });

        // Inicializar o datepicker
        $("#dt_entrada, #dt_saida").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });
    });
</script>
<!-- Reintroduzir o $.browser (script para versões mais recentes do jQuery) -->
<script>
    (function () {
        var matched, browser;

        jQuery.uaMatch = function (ua) {
            ua = ua.toLowerCase();

            var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                    /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                    /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                    /(msie) ([\w.]+)/.exec(ua) ||
                    ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
                    [];

            return {
                browser: match[1] || "",
                version: match[2] || "0"
            };
        };

        matched = jQuery.uaMatch(navigator.userAgent);
        browser = {};

        if (matched.browser) {
            browser[matched.browser] = true;
            browser.version = matched.version;
        }

        // Chrome is Webkit, but Webkit is also Safari.
        if (browser.chrome) {
            browser.webkit = true;
        } else if (browser.webkit) {
            browser.safari = true;
        }

        jQuery.browser = browser;
    })();
</script>
<?php
session_name('SESSAO_PHP');
include "topo.php";
include "conexao.php";
include "valida/verifica_acessoAdm.php";
include "valida/verifica_autenticacao.php";
include "valida/mascaras.php";
$totalHospedes = 0;
?>
<div id="conteudo">
    <div id="cont">
        <body>
            <p>
            <h2>Imprimir QR Code de pontos de Monitoramento</h2> 
            <hr />
            <form method="post" action="gera_qrcode.php" target="_blank">
                <br>
                <table border="0">
                    <tr>
                        <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ponto de monitoramento:</font></th>
                        <td>
                            <input type="text" id="texto" name="texto" required placeholder="Exemplo: Av. das Rosas">
                        </td>
                    </tr> 

                </table>
                <!-- Código para aplicar o datepicker -->
                <script type="text/javascript">
                    $(document).ready(function () {
                        if (typeof jQuery === "undefined") {
                            alert("Erro: jQuery não foi carregado corretamente!");
                            return;
                        }

                        var $dateFields = $("#dt_entrada, #dt_saida");
                        if ($dateFields.length) {
                            try {
                                $dateFields.datepicker({
                                    dateFormat: "dd/mm/yy",
                                    changeMonth: true,
                                    changeYear: true,
                                    showButtonPanel: true
                                });
                            } catch (error) {
                                console.error("Erro ao aplicar o datepicker: ", error);
                            }
                        } else {
                            console.warn("Nenhum campo de data encontrado.");
                        }
                    });
                </script> 

                <center> <input type="submit" value="Gerar QRCOde" name="filtro" value="sim"/></center>
                <br />


    </div> <!-- fim div conteudo -->
    <?php
    include "rodape.php";
    ?>