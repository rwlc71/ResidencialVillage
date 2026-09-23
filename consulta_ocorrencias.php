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

<!-- Scripts utilizados no autocomplete -->
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
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
$totalHospedes = 0;
?>


<div id="conteudo">
    <div id="cont">
        <body>
            <h2><center>Relatório para Acompanhamento de Ocorrências</center></h2> <br>
            <hr />
            <form method="post" action="consulta_ocorrencias.php">
                <br>
                <table border="0">
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                     id="txtNome" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>
                    <tr>
                        <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</font></th>
                        <td>
                            <input type="text" id="dt_entrada" name="dt_entrada" size="10" maxlength="10"/>
                            <font size="2"><b>&nbsp;&nbsp;&nbsp;Data de saída:</b></font>
                            <input type="text" id="dt_saida" name="dt_saida" size="10" maxlength="10"/>
                        </td>
                    </tr> 
                    <tr>
                        <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Etapa:</b> </td>
                        <th width="25%" align="left" scope="col">
                            <select id="Etapa" name="Etapa" ><font size="20"; color="#000000">
                                <option  value="<?= $descEtapa ?>" selected="selected"><?= $descEtapa ?></option>
                                <option value="Azaléia - AZ">Azaléia - AZ</option>
                                <option value="Bougainville - BO">Bougainville - BO</option>
                                <option value="Gardênia - GA">Gardênia - GA</option>
                                <option value="Jacarandás - JAC">Jacarandás - JAC</option>
                                <option value="Orquídeas - OR">Orquídeas - OR</option>
                                <option value="Pitangueiras - PIT">Pitangueiras - PIT</option>
                            </select>
                            &nbsp;<font size="2"; color="#000000">Unidade:
                            <input type="text" value="<?= $nrUnidade ?>" name="nr_etapa" size="3" maxlength="3" />
                        </th>        
                    </tr>
                    <tr>
                        <td align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tipo de unidade:</b> </td>
                        <th align="left" scope="col">
                            <select id="tipo_unidade" name="tipo_unidade" ><font size="20"; color="#000000">
                                <option  value="" selected="selected"></option>
                                <option value="Residência">Residência</option>
                                <option value="Locação Regular (+90dias)">Locação Regular (+90dias)</option>
                                <option value="Locação Temporária">Locação Temporária</option>
                            </select>
                        </th>        
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
                <h3>Relação de Ocorrências Registradas</center></h3>

                <table  border="2">
                    <tr>
                        <td width="1%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Proprietário</b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Contato Proprietário</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Entrada</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Saída</b></td>
                        <td width="14%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Responsável</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Contato Responsável</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="3"><font size="2"; color="#F5FFFA"><b>Ver </b></td>

                    </tr>
                    <?php
                    $sql = "SELECT oco.*, loc.*, uni.*, prop.id_proprietario, prop.nome FROM ocorrencias oco "
                            . " JOIN locacao loc ON loc.id_locacao = oco.id_locacao"
                            . " JOIN proprietario prop ON loc.id_proprietario = prop.id_proprietario"
                            . " JOIN unidade uni on uni.id_unidade = loc.id_unidade order by loc.dt_entrada ";
//                echo ('$sql1 --> ' . $sql);
//                exit();
                    $sql = mysql_query($sql);
                    $num_rows = mysql_num_rows($sql);

                    $where = Array();

                    if ($_POST['proprietario']) {
//                    $where[] = " prop.nome = '{$_POST['proprietario']}'";
                        $where[] = " prop.nome  LIKE  '%" . $_POST['proprietario'] . "%'";
                    }
                    if ($_POST['dt_entrada']) {
                        $dt_entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dt_entrada']))); // Converte para '2024-12-10'
                        $where[] = "   loc.dt_entrada >= '{$dt_entrada}'";
                    }
                    if ($_POST['dt_saida']) {
                        $dt_saida = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dt_saida']))); // Converte para '2024-12-10'
                        $where[] = "  loc.dt_saida <= '{$dt_saida}'";
                    }
                    if ($_POST['tipo_unidade']) {
                        $where[] = " uni.tipo_unidade = '{$_POST['tipo_unidade']}'";
                    }
                    if ($_POST['Etapa']) {
                        $where[] = " uni.etapa = '{$_POST['Etapa']}'";
                    }
                    if ($_POST['nr_etapa']) {
//                    $where[] = " uni.numero_etapa = '{$_POST['nr_etapa']}'";
                        $where[] = " uni.numero_etapa  LIKE  '%" . $_POST['nr_etapa'] . "%'";
                    }

"SELECT COUNT(*) as total FROM locacao WHERE (id_unidade = '$id_unidade' and dt_entrada <= '$dt_saida' AND dt_saida >= '$dt_entrada')";


                    $sql = "SELECT oco.*, loc.*, uni.*, prop.id_proprietario, prop.nome, prop.telefone "
                            . " FROM ocorrencias oco"
                            . " JOIN locacao loc ON loc.id_locacao = oco.id_locacao"
                            . " JOIN unidade uni ON uni.id_unidade = loc.id_unidade"
                            . " JOIN proprietario prop ON prop.id_proprietario = uni.id_proprietario"
                            . " GROUP BY loc.id_locacao"
                            . " ORDER BY loc.id_locacao";

                    if (sizeof($where)) {
                        $sql = "SELECT oco.*, loc.*, uni.*, prop.id_proprietario, prop.nome, prop.telefone "
                                . " FROM ocorrencias oco"
                                . " JOIN locacao loc ON loc.id_locacao = oco.id_locacao"
                                . " JOIN unidade uni ON uni.id_unidade = loc.id_unidade"
                                . " JOIN proprietario prop ON prop.id_proprietario = uni.id_proprietario";

                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . "  GROUP BY loc.id_locacao ORDER BY loc.id_locacao";
                    }
                    $filtro = mysql_query($sql);
                    $num_rows = mysql_num_rows($filtro);

                    if ($num_rows == 0) {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            echo "<script type=\"text/javascript\">alert(\"Não existem registros cadastrados para a consulta realizada!\");</script>";
                        }
                        echo '<tr><td colspan="12" align="center"><font size="2" color="#000000">Não existem registros cadastrados para a consulta realizada!</font></td></tr>';
                    }
//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {
//echo(var_dump($ln));
//exit();
                        $dt_entrada = DateTime::createFromFormat('Y-m-d', $ln['dt_entrada'])->format('d/m/Y');
                        $dt_saida = DateTime::createFromFormat('Y-m-d', $ln['dt_saida'])->format('d/m/Y');

                        date_default_timezone_set('America/Bahia');
                        $dt_hoje = date("d/m/Y");
                        $datahoje = implode('', array_reverse(explode('/', $dt_hoje)));
                        $dataini = implode('', array_reverse(explode('/', $dt_entrada)));
                        $datafim = implode('', array_reverse(explode('/', $dt_saida)));

                        $horaini = substr($data01, 11);
                        $horafim = substr($data02, 11);
                        switch ($ln['etapa']) {
                            case 'Azaléia - AZ':
                                $etapa = 'AZ/' . $ln['numero_etapa'];
                                break;
                            case 'Bougainville - BO':
                                $etapa = 'BO/' . $ln['numero_etapa'];
                                break;
                            case 'Gardênia - GA':
                                $etapa = 'GA/' . $ln['numero_etapa'];
                                ;
                                break;
                            case 'Jacarandás - JAC':
                                $etapa = 'JAC/' . $ln['numero_etapa'];
                                break;
                            case 'Orquídeas - OR':
                                $etapa = 'OR/' . $ln['numero_etapa'];
                                break;
                            case 'Pitangueiras - PIT':
                                $etapa = 'PIT/' . $ln['numero_etapa'];
                                break;
                        }


                        $telefone_limpo = str_replace(array("/", "-", " ", "(", ")"), "", $ln['telefone']);
                        $ln['telefone'] = mask($telefone_limpo, '(##)#####-#####');
                        $telefone_limpo = str_replace(array("/", "-", " ", "(", ")"), "", $ln['contato_resp']);
                        $ln['contato_resp'] = mask($telefone_limpo, '(##)#####-#####');
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                ' <a href="visualizar_ocorrencias.php?dado=' . $ln['id_locacao'] . '" title="Visualizar ocorrências"> ' .
                                '<img src="images/documento.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';
                        ?>
                        <tr>
                            <td style="padding:5px;" align="center"><font size="2"; color="#000000"><?= $etapa ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp;<?= $ln['nome'] ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp;<?= $ln['telefone'] ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp;<?= $ln['tipo_unidade'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_entrada ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_saida ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp;<?= $ln['resp_locacao'] ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp;<?= $ln['contato_resp'] ?></td>
                            <?= $visualizarcomprovante ?>
                        </tr>
                        <?php
                    } // Fecha Loop 
                    ?>
                </table>
            </div>
            <br>
        </body>
        </html>

    </div><!-- fim div cont -->

</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>