<!-- Adicionar o CSS -->
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!-- Adicionar o jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Adicionar o jQuery UI (após o jQuery) -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Scripts adicionais -->
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/componentes.js"></script>

<!--Scripts utilizados no autocomplete--> 
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/thickbox-compressed.js"></script>
<script type="text/javascript" src="jquery-autocomplete/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/jquery.autocomplete.css"/>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/lib/thickbox.css"/>

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
            <h2>Relatório de Inspeção de Segurança</h2> <br>
            <hr />
            <form method="post" action="ronda.php">
                <br>
                <!--<font size="3"; color="#000000"><b>Faça a pesquisa para identificar a unidade:</b></font><br><p></p>-->
                <br>
                <table border="0">
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Colaborador:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="colaborador" 
                                                                     id="txtNome" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>
                    <tr>
                        <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</font></th>
                        <td>
                            <input type="text" id="dt_entrada" name="dt_entrada" size="10" maxlength="10"/>
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

                <center> <input type="submit" value="Pesquisar" name="filtro" value="sim"/></center>
                <br />
            </form>
            <hr/>          
            <br>
            <div class="estiloTabelas table-responsive">
                <h3>Relatório de Inspeção de Segurança</center></h3>
                <table  border="2">
                    <tr>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Colaborador</b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Local</b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data/Hora do registro</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Latitude</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Longitude</b></td>
                        <td width="3%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Mapa</b></td>
                    </tr>
                    <?php
                    $sql = "SELECT * from localizacao ";
//                echo ('$sql1 --> ' . $sql);

                    $sql = mysql_query($sql);
                    $num_rows = mysql_num_rows($sql);

                    if ($num_rows == 0) {
                        echo "<meta http-equiv='refresh' content='0; URL=home.php'>
                <script type=\"text/javascript\">
                alert(\"Não existem dados cadastrados!  \");
                </script>
                ";
                        return die;
                    }
//==========================================
                    $where = Array();

                    if ($_POST['colaborador']) {
                        $where[] = " usuario  LIKE  '%" . $_POST['colaborador'] . "%'";
                    }
                    if ($_POST['dt_entrada']) {
                        $dt_entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dt_entrada']))); // Converte para '2024-12-10'
                        $where[] = "   DATE(hora_usuario) = '{$dt_entrada}'";
                    }
//echo(var_dump($where));
//exit();
                    $sql = "SELECT * from localizacao ORDER BY hora_usuario DESC ";

                    if (sizeof($where)) {
                        $sql = "SELECT * from localizacao ";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . " ORDER BY  hora_usuario DESC";
                    }
//echo(($sql));
//exit();

                    $filtro = mysql_query($sql);
                    $num_rows = mysql_num_rows($filtro);
                    if ($num_rows == 0) {
                        echo "<meta http-equiv='refresh' content='0; URL=ronda.php'>
                <script type=\"text/javascript\">
                alert(\"Não existem registros para a consulta realizada!  \");
                </script>
                ";
                        return die;
                    }
//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {
                       $dt_entrada = date('d/m/Y H:i:s', strtotime($ln['hora_usuario']));
                        date_default_timezone_set('America/Bahia');
                        $dt_hoje = date("d/m/Y");
                        $datahoje = implode('', array_reverse(explode('/', $dt_hoje)));
                        $dataini = implode('', array_reverse(explode('/', $dt_entrada)));
                        $datafim = implode('', array_reverse(explode('/', $dt_saida)));
                        $maps = "https://www.google.com/maps?q=" . $ln['latitude'] . "," . $ln['longitude'];
                        ?>
                        <tr>

                            <td align="left"><font size="2"; color="#000000"><?= $ln['usuario'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['codigo'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_entrada ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['latitude'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['longitude'] ?></td>
							<td align="center" valign="middle" bgcolor="#FFFFFA">
								<a href="<?= $maps ?>" target="_blank" title="Abrir no Google Maps">
								<img src="images/localizacao.png" title="Abrir no Google Maps" height="20" width="20" align="middle" border="0">
								</a>
							</td>

                        </tr>
                        <?php
                    } // Fecha Loop 
                    ?>
                </table>
                <br>
                </body>
                </html>

            </div><!-- fim div cont -->

    </div> <!-- fim div conteudo -->
    <?php
    include "rodape.php";
    ?>