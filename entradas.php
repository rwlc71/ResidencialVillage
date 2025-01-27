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
<script type="text/javascript">
    $(document).ready(function () {
        // Autocomplete para nome
        $("#txtfornecedor").autocomplete("completar_fornecedor.php", {
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
<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";

$id_entrada = '';
$id_entrada = $_REQUEST['dado'];

//echo(var_dump($_REQUEST));
if ($_REQUEST['filtro'] === 'Gravar') {
    $dados = [
        'autor' => $_COOKIE['nome_usuario'],
        'nome' => $_REQUEST['nome'],
        'destino' => $_REQUEST['destino'],
        'dt_entrada' => $_REQUEST['dt_entrada'],
        'hr_entrada' => $_REQUEST['hr_entrada'],
        'dt_saida' => $_REQUEST['dt_saida'],
        'hr_saida' => $_REQUEST['hr_saida'],
        'contato' => $_REQUEST['contato'],
        'id_entrada' => $_REQUEST['id_entrada'],
        'anotacoes' => $_REQUEST['anotacoes'],
        'identificacao' => $_REQUEST['identificacao']
    ];
    $host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($path, '/'));
    $firstPart = isset($parts[0]) ? $parts[0] : '';
    $url = $host . "/" . $firstPart . "/funcoes/gravar_entradas.php";

    // Configuração e envio da requisição POST via cURL
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($dados)
    ]);

    $r = curl_exec($ch);
//    echo("Resposta1: " . $r . '<p>');
    $re = json_decode($r, true);
    $msg = $re['message'];
    $status = $re['status'];
//    echo("Resposta2: " . $r['status'] . '<p>');
//    echo("Resposta3: " . $re['message'] . '<p>');
//    exit();

    curl_close($ch);
    if ($status == 'success') {
        echo "<meta http-equiv='refresh' content='0; URL= entradas.php'>
                    <script type=\"text/javascript\">
                    alert(\"$msg!\");
                    </script> ";
        return die;
    } else {
        echo "<meta http-equiv='refresh' content='0; '>
        <script type=\"text/javascript\">
            alert(\"$msg!\");
            history.back(); 
          </script>  ";
        return die;
    }
} else {

    $lnEntrada = '';
    $consulta = "SELECT * from entradas";
    if ($id_entrada != '') {
        $consulta = "SELECT * from entradas where id_entrada = " . $id_entrada;
        $consulta = mysql_query($consulta);
        $lnEntrada = mysql_fetch_array($consulta);
        date_default_timezone_set('America/Sao_Paulo');
        $dataEntrada = '';
        $horaEntrada = '';
        $dataSaida = '';
        $horaSaida = '';
        if ($lnEntrada['dthora_entrada']) {
            $data = new DateTime($lnEntrada['dthora_entrada']);
            $dataEntrada = $data->format('d/m/Y');
            $horaEntrada = $data->format('H:i');
        }
        if ($lnEntrada['dthora_saida']) {
            $data = new DateTime($lnEntrada['dthora_saida']);
            $dataSaida = $data->format('d/m/Y');
            $horaSaida = $data->format('H:i');
        }
    }
}
?>
<div id="conteudo">

    <div id="cont">
        <h2>Inclusão / Alteração de Registro de entradas e saídas</h2>
        <br>
        <hr><br>
        <form method="post" action="entradas.php" enctype="multipart/form-data">

            <table width="80%" border="0">
                <tr>
                    <!--<td colspan="2"> <b>Dados Pessoais:</b></td>-->
                </tr>
<!--                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                    <th width="25%" align="left" scope="col">
                        <input name="cpf" type="text" class="imput" id="cpf" size="14" maxlength="14" value="<?= $cpf ?>"
                               placeholder="Somente números"  disabled />
                    </th>
                </tr>-->

                <tr>
                    <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome ou fornecedor:</b> </td>
                    <td>  <font size="2"; color="#000000"><input type="text" name="nome" 
                                                                 value="<?= $lnEntrada['Nome'] ?>"
                                                                 id="txtfornecedor" 
                                                                 size="60" 
                                                                 onblur="buscarDadosFornecedor()"
                                                                 class="input_forms" 
                                                                 /><br></td> 
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Identificação:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $lnEntrada['identificacao'] ?>" id="id_fornecedor" name="identificacao" size="60" /></th>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Destino:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $lnEntrada['destino'] ?>" name="destino" size="60" /></th>
                </tr>

                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</font></th>
                    <td>
                        <input type="text" id="dt_entrada" name="dt_entrada"  value="<?= $dataEntrada ?> " size="10" maxlength="10"/>
                        <font size="2"><b>&nbsp;&nbsp;&nbsp;Hora de entrada:</b></font>
                        <input type="text" id="hr_entrada"  value="<?= $horaEntrada ?> "name="hr_entrada" size="10" maxlength="5" oninput="validarHora(this)" placeholder="HH:MM"/>
                    </td>
                </tr> 

                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de saída:</font></th>
                    <td>
                        <input type="text" id="dt_saida" name="dt_saida"  value="<?= $dataSaida ?> " size="10" maxlength="10"/>
                        <font size="2"><b>&nbsp;&nbsp;&nbsp;Hora de saída:</b></font>
                        <input type="text" id="hr_saida" name="hr_saida"  value="<?= $horaSaida ?> " size="10" maxlength="5" oninput="validarHora(this)" placeholder="HH:MM"/>
                    </td>
                </tr> 
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato:</th>
                    <th width="25%" align="left" scope="col"><input type="text" id="id_fornecedor_contato"  name="contato" value="<?= $lnEntrada['contato'] ?>" size="25" maxlength="25"  /></th>
                <input type="hidden" name="id_entrada" value="<?= $id_entrada ?>" />

                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anotações:</b> </th>
                    <th width="15%" align="left" scope="col"><textarea name="anotacoes" value="<?= $lnEntrada['anotacoes'] ?>" cols="57" rows="5" placeholder="Exemplo: placa do carro, marca e modelo, quantidade de veículos..."><?= $lnEntrada['anotacoes'] ?></textarea></th> 
                </tr>
            </table>
            <br>
            <center> 
                <input type="submit" value="Pesquisar" name="filtro" value="pesquisar"/>
                <input type="submit" value="Gravar" name="filtro" value="gravar"/></center>
            <br />
            <script>
                function aplicarMascaraData(input) {
                    // Adiciona um event listener para o evento de input (digitação)
                    input.addEventListener('input', function () {
                        // Remove todos os caracteres que não são dígitos
                        let valor = input.value.replace(/\D/g, '');

                        // Limita a quantidade de caracteres para 8 (ddmmaaaa)
                        if (valor.length > 8) {
                            valor = valor.slice(0, 8);
                        }

                        // Aplica a máscara de data (dd/mm/yyyy)
                        if (valor.length >= 5) {
                            valor = valor.replace(/(\d{2})(\d{2})(\d{1,4})/, '$1/$2/$3');
                        } else if (valor.length >= 3) {
                            valor = valor.replace(/(\d{2})(\d{1,2})/, '$1/$2');
                        }

                        // Atualiza o valor do input com a máscara aplicada
                        input.value = valor;
                    });
                }

                // Função para validar a entrada da hora
                function validarHora(input) {
                    // Remove qualquer caractere que não seja dígito ou dois pontos
                    input.value = input.value.replace(/[^0-9:]/g, '');

                    // Verifica o formato e impede que o usuário digite mais do que HH:MM
                    if (input.value.length === 2 && !input.value.includes(':')) {
                        input.value += ':';
                    }

                    // Limita a entrada ao formato HH:MM
                    if (input.value.length > 5) {
                        input.value = input.value.slice(0, 5);
                    }
                }
            </script>
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
            <hr><p>
            <h3>Entradas(s) registrada(s)</h3>
            <div class="estiloTabelas table-responsive">
                <table width="100%" border="2" border-collapse: collapse;>
                    <tr>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nome </b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Destino </b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data/Hora do Entrada </b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data/Hora do Saída </b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Contato</b></td>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Anotações</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="2"><font size="2"; color="#F5FFFA"><b> Editar</b></td>
                    </tr>
                    <?php
//                    $sql = "SELECT *FROM entradas LIMIT 3 order by dthora_entrada ";
//                    $sql = mysql_query($sql);
//                    $num_rows = mysql_num_rows($sql);
//==========================================
                    $where = Array();

                    if ($_POST['nome']) {
                        $where[] = " nome LIKE  '%" . $_POST['nome'] . "%'";
                    }
                    if ($_POST['identificacao']) {
                        $where[] = " identificacao LIKE  '%" . $_POST['identificacao'] . "%'";
                    }
                    if ($_POST['destino']) {
                        $where[] = " destino LIKE  '%" . $_POST['destino'] . "%'";
                    }
                    if (trim($_POST['dt_entrada']) != '') {
                        $dt_entrada = $_POST['dt_entrada'];  // A string com a data no formato '17/01/2025'
                        $data = DateTime::createFromFormat('d/m/Y', $dt_entrada);
                        $dataFormatada = $data->format('Y-m-d');
                        $where[] = " dthora_entrada >= '$dataFormatada 00:00:00' ";
                    }
                    if (trim($_POST['dt_saida']) != '') {
                        $dt_saida = $_POST['dt_saida'];  // A string com a data no formato '17/01/2025'
                        $data = DateTime::createFromFormat('d/m/Y', $dt_saida);
                        $dataFormatada = $data->format('Y-m-d');

                        $where[] = " dthora_saida <= '$dataFormatada 23:59:59' ";
                    }
                    if ($_POST['contato']) {
                        $where[] = " contato LIKE  '%" . $_POST['contato'] . "%'";
                    }
                    if ($_POST['anotacoes']) {
                        $where[] = " anotacoes LIKE  '%" . $_POST['anotacoes'] . "%'";
                    }

                    $sql = "SELECT *FROM entradas order by dthora_entrada desc LIMIT 15 ";

                    if (sizeof($where)) {
                        $sql = "SELECT * FROM entradas  ";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . " ORDER BY dthora_entrada desc limit 15";
                    }
//                    echo(var_dump($_POST));
//                    echo('data: ' . $dataFormatada . '<p>');
//                    echo($sql);

                    $filtro = mysql_query($sql);
                    $num_rows = mysql_num_rows($filtro);

//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {
                        date_default_timezone_set('America/Sao_Paulo');
                        $dataEntradaFormatada = '';
                        $dataSaidaFormatada = '';
                        if ($ln['dthora_entrada']) {
                            $data = new DateTime($ln['dthora_entrada']);
                            $dataEntradaFormatada = $data->format('d/m/Y \a\s H:i:s');
                        }
                        if ($ln['dthora_saida']) {
                            $data = new DateTime($ln['dthora_saida']);
                            $dataSaidaFormatada = $data->format('d/m/Y \a\s H:i:s');
                        }
                        $editar = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                ' <a href="entradas.php?dado=' . $ln['id_entrada'] . '" title="Editar entrada"> ' .
                                '<img src="images/complementar.png"  title="Editar entrada" height=20 width=20 align="middle" border="0">' .
                                '</td>';
                        ?>
                        <tr>
                            <td style="padding:5px;" align="left"><font size="2"; color="#000000"><?= strtoupper($ln['Nome']) ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= strtoupper($ln['destino']) ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= $dataEntradaFormatada ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $dataSaidaFormatada ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['contato'] ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln['anotacoes'] ?></td>
                            <?= $editar ?>



                        </tr>
                        <?php
                    }// Fecha Loop 
                    ?>                </table>
            </div>
            <br>
            <center>
                <input type="button" value="Voltar" onClick="JavaScript: window.history.back();">
            </center>  
            <br>
            <p></p>
<!--            <center>
                <input type="submit" name="botao" value="Cadastrar Ocorrência" />
            </center>    -->
        </form>
        <br />
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>