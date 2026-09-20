<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/componentes.js"></script>

<!-- Adicionar o jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Adicionar o jQuery UI (após o jQuery) -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Scripts adicionais -->
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/componentes.js"></script>


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
<style>
    @media (max-width: 768px) {
        table {
            display: block;
            border: 0;
        }
        thead {
            display: none;
        }
        tr {
            display: block;
            margin-bottom: 15px;
        }
        td {
            display: block;
            text-align: left;
            border: 0;
        }
        td:before {
            content: attr(data-label);
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
    }
</style>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";

$codigo = $_REQUEST['codigo'];
$confirmado = 0;
// Verifica se código foi informado


// Verifica se o código é válido
    if ($_REQUEST['codigo']) {
        $consulta = "SELECT * FROM locacao WHERE codvalidacao =  '" . $_REQUEST['codigo'] . "'";
        $consulta = mysql_query($consulta);
        $ln = mysql_fetch_array($consulta);
   
if (!$ln) {
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
          <script>alert('CÓDIGO DE VALIDAÇÃO INVÁLIDO!');</script>";
    die;
}

$dt_saida = $ln['dt_saida'];
$data_hoje = date('Y-m-d');

if (strtotime($dt_saida) > strtotime($data_hoje)) {
    echo "<meta http-equiv='refresh' content='0; URL=index.php'>
          <script>alert('AUTORIZAÇÃO EXPIRADA!');</script>";
    die;
}

// Se ainda não confirmou, exibe alerta personalizado e para o programa
if ($confirmado !== '1') {
    echo <<<HTML
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmação</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: rgba(0,0,0,0.85);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modal-caixa {
            background-color: #fff;
            padding: 30px 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .modal-caixa h2 {
            font-size: 20px;
            color: #111;
        }
        .modal-caixa p {
            font-size: 16px;
            color: #333;
            margin: 20px 0;
        }
        .botoes {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .botoes button {
            padding: 12px;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-confirmar {
            background-color: #28a745;
            color: white;
        }
        .btn-cancelar {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="modal-caixa">
        <h2>Autorização válida</h2>
        <p>Deseja confirmar o registro da entrada no condomínio?</p>
        <div class="botoes">
            <button class="btn-confirmar" onclick="confirmar()">Registrar entrada</button>
            <button class="btn-cancelar" onclick="cancelar()">Cancelar</button>
        </div>
    </div>
    <script>
        function confirmar() {
            window.location.href = '?codigo=' + encodeURIComponent("{$codigo}") + '&confirmado=1';
        }
        function cancelar() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>
HTML;
    die; // Para o programa até o clique
}

// segue o restante do seu código PHP e HTML como estava

include "topo.php";
//echo($_REQUEST['codigo']);
//exit();
$codigo = $_REQUEST['codigo'];

if ($_REQUEST['dado']) {
    $id_audita = $_REQUEST['dado'];
    $id_locacao = $_REQUEST['dado'];
} else {
    if ($_REQUEST['codigo']) {
        $consulta = "SELECT * FROM locacao WHERE codvalidacao =  '" . $_REQUEST['codigo'] . "'";
        $consulta = mysql_query($consulta);
        $ln = mysql_fetch_array($consulta);

//        $sql = "SELECT * FROM locacao WHERE codvalidacao = :codigo";
//        $stmt = $pdo->prepare($sql);
//        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
//        $stmt->execute();
//        $ln = $stmt->fetch(PDO::FETCH_ASSOC);

        $dt_saida = $ln['dt_saida']; // exemplo vindo do banco
        $data_hoje = date('Y-m-d'); // data atual

        if (strtotime($dt_saida) > strtotime($data_hoje)) {
            echo "<meta http-equiv='refresh' content='0; URL=index.php'>
                <script type=\"text/javascript\">
                alert(\"AUTORIZAÇÃO EXPIRADA!\");
                </script> ";
            return die;        }
if (!$ln) {
    echo "<meta http-equiv='refresh' content='0; URL=index.php'>
        <script type=\"text/javascript\">
        alert(\"CÓDIGO DE VALIDAÇÃO INVÁLIDO!\");
        </script> ";
    return die;
}

//        if ($stmt->rowCount() == 0) {
        if (mysql_num_rows($consulta) != true) {
            echo "<meta http-equiv='refresh' content='0; URL=index.php'>
                <script type=\"text/javascript\">
                alert(\"CÓDIGO DE VALIDAÇÃO INVÁLIDO!\");
                </script> ";
            return die;
        } else {
            $id_audita = $ln['id_locacao'];
            $id_locacao = $ln['id_locacao'];
        }
    } else {
        echo "<meta http-equiv='refresh' content='0; URL=index.php'>
                <script type=\"text/javascript\">
                alert(\"CÓDIGO DE VALIDAÇÃO INVÁLIDO!\");
                </script> ";
        return die;
    }
}
if ($_REQUEST['origem']) {
    $origem = $_REQUEST['origem'];
}

$disabled = "";
$botaosalvar = ' <input type="submit" name="botao" value="Salvar" />';

if ($_REQUEST['d'] == 'd') {
    $disabled = "disabled";
    $botaosalvar = '';
}

$consulta = "SELECT aud.*, uni.*, prop.id_proprietario, prop.nome, prop.CPF FROM audita aud "
        . "JOIN proprietario prop ON aud.id_proprietario = prop.id_proprietario"
        . " JOIN unidade uni on aud.id_unidade = uni.id_unidade  "
        . " WHERE aud.id_audita = " . $id_audita . " order by aud.dt_entrada";
$consulta = mysql_query($consulta);
$ln = mysql_fetch_array($consulta);
//echo (var_dump($ln));
//exit();
if (mysql_num_rows($consulta) != true) {
    echo "<meta http-equiv='refresh' content='0; URL=index.php'>
    <script type=\"text/javascript\">
    alert(\"Não existem reservas cadastradas! \");
    </script>
  ";
    return die;
}

$tamanho = strlen($ln['CPF']);
if ($tamanho > 11) {
    $cpf = mask($ln['CPF'], '##.###.###/####-##');
} else {
    $cpf = mask($ln['CPF'], '###.###.###-##');
}
$listalocacao = false;
$dataEntradaEfetiva = '';
$dataSaidaEfetiva = '';
$horaEntradafetiva = '';
$horaSaidaEfetiva = '';

$dt_entrada = DateTime::createFromFormat('Y-m-d', $ln['dt_entrada'])->format('d/m/Y');
$dt_saida = DateTime::createFromFormat('Y-m-d', $ln['dt_saida'])->format('d/m/Y');

// Formatar as datas para dd/mm/aaaa hh:mm


if ($ln['dt_entrada_efetiva'] !== null) {
    $dataEntradaEfetiva = date('d/m/Y', strtotime($ln['dt_entrada_efetiva']));
    $horaEntradafetiva = date('H:i', strtotime($ln['dt_entrada_efetiva']));
}
if ($ln['dt_saida_efetiva'] !== null) {
    $dataSaidaEfetiva = date('d/m/Y', strtotime($ln['dt_saida_efetiva']));
    $horaSaidaEfetiva = date('H:i', strtotime($ln['dt_saida_efetiva']));
}

$telefone = $ln['contato_resp'];
$telefone = str_replace(".", "", $telefone);
$telefone = str_replace("-", "", $telefone);
$telefone = str_replace(" ", "", $telefone);
$telefone = str_replace("(", "", $telefone);
$telefone = str_replace(")", "", $telefone);
$telefone = mask($telefone, '(##)#####-#####');

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
?>
<div id="conteudo">

    <div id="cont">
        <h2>Dados da Reserva
            <a href="autorizacao.php?id=<?= $id_locacao ?>"  target="_blank" title="Visualizar Autorização de hospedagem">
                <img src="images/ver.jpg"  height=20 width=20 align="middle" border="0">
            </a></h2>
        <hr>
        <form method="post" action="funcoes/salvar_complemento.php" enctype="multipart/form-data">

            <table width="75%" border="0">
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
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['nome'] ?>" name="nome" size="60" disabled/></th>
                </tr>
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidade:</th>
                    <td ><input type="text"  value="<?= $etapa ?>" name="unidade" size="10" maxlength="10" disabled/>
                        <font size="2"; ><b>&nbsp;&nbsp;&nbsp;Qtde Hospedes::</b> <input type="data" value="<?= $ln['qtde_hospedes'] ?>" maxlength="3" name="qtde_hosp" size="3" disabled/>
                    </td>
                </tr> 
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</th>
                    <td ><input type="text"  value="<?= $dt_entrada ?>" name="dt_entrada" size="10" maxlength="10" onkeypress="aplicarMascaraData($dt_entrada)"  disabled />
                        <font size="2"; ><b>&nbsp;&nbsp;&nbsp;Data de saída:</b> <input type="data" value="<?= $dt_saida ?>" maxlength="10" name="dt_saida" size="10" onkeypress="aplicarMascaraData($dt_saida)" disabled />
                        <input type="hidden" name="cpf" value="<?= $cpf ?>" />
                        <input type="hidden" name="id_unidade" value="<?= $ln['id_unidade'] ?>" />
                        <input type="hidden" name="id_audita" value="<?= $ln['id_audita'] ?>" />

                    </td>
                </tr> 

                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Responsável pela locação:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['resp_locacao'] ?>" name="resp_loc" size="60" disabled /></th>
                </tr>
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/RG:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['doc_identificacao_resp'] ?>" name="doc_resp" size="18" disabled /></th>
                </tr>

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $ln['contato_resp'] ?>" size="18" maxlength="16" disabled /></th>
                </tr>
<!--                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Imformações Complementares:</b> </th>
                    <th width="15%" align="left" scope="col"><textarea  name="ocorrencia" cols="57" rows="5" placeholder="Exemplo: placa do carro, marca e modelo, quantidade de veículos..." <?= $disabled ?> ><?= $ln['complementares'] ?></textarea></th> 
                </tr>
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada efetiva:</font></th>
                    <td>
                        <input type="text" id="dt_entrada_efetiva" name="dt_entrada_efetiva"  value="<?= $dataEntradaEfetiva ?> " size="10" maxlength="10"  <?= $disabled ?>/>
                        <font size="2"><b>&nbsp;&nbsp;&nbsp;Hora da chegada:</b></font>
                        <input type="text" id="hr_entrada_efetiva" name="hr_entrada_efetiva"  value="<?= $horaEntradafetiva ?> " size="10" maxlength="5" oninput="validarHora(this)" placeholder="HH:MM"  <?= $disabled ?>/>
                    </td>
                </tr> 
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de saída efetiva:</font></th>
                    <td>
                        <input type="text" id="dt_saida_efetiva" name="dt_saida_efetiva"  value="<?= $dataSaidaEfetiva ?> " size="10" maxlength="10"  <?= $disabled ?>/>
                        <font size="2"><b>&nbsp;&nbsp;&nbsp;Hora de saída:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></font>
                        <input type="text" id="hr_saida_efetiva" name="hr_saida_efetiva"  value="<?= $horaSaidaEfetiva ?> " size="10" maxlength="5" oninput="validarHora(this)" placeholder="HH:MM"  <?= $disabled ?>/>
                    </td>
                </tr> -->
            </table>
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
            </script>
            <script>
                $(document).ready(function () {
                    if (typeof jQuery === "undefined") {
                        alert("Erro: jQuery não foi carregado corretamente!");
                        return;
                    }

                    var $dateFields = $("#dt_entrada_efetiva, #dt_saida_efetiva");
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
                function MascaraTelefone(input) {
                    // Adiciona um event listener para o evento de input (digitação)
                    input.addEventListener('input', function () {
                        // Remove todos os caracteres que não são dígitos
                        let valor = input.value.replace(/\D/g, '');

                        if (valor.length > 0) {
                            valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2'); // Adiciona o parêntese
                        }

                        if (valor.length > 9) {
                            valor = valor.replace(/(\d{5})(\d)/, '$1-$2'); // Adiciona o hífen após o quinto dígito
                        }

                        // Atualiza o valor do input com a máscara aplicada
                        input.value = valor;
                    });
                }

                function adicionarHospede() {
                    // Pega a referência da tabela onde os campos serão adicionados
                    var tabela = document.getElementById("tabelaHospedes");

                    // Cria uma nova linha na tabela
                    var linha = tabela.insertRow(-1);

                    // Cria a primeira célula (Nome do hóspede)
                    var cell1 = linha.insertCell(0);
                    cell1.innerHTML = '<input type="text" name="hospedes[nome][]" placeholder="Nome do hóspede" size="30" required>';

                    // Cria a segunda célula (CPF do hóspede)
                    var cell2 = linha.insertCell(1);
                    cell2.innerHTML = '<input type="text" name="hospedes[cpf][]" placeholder="doc. identificação" size="20" required>';

                    // Cria a terceira célula (Idade do hóspede)
//                    var cell3 = linha.insertCell(2);
////                    cell3.innerHTML = '<input type="number" name="hospedes[idade][]" placeholder="Idade" size="5" required>';
//                    cell3.innerHTML = '<button type="button" onclick="adicionarHospede()">Adicionar Hóspede</button>';
                }
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
            <br>
            <p></p>
            <center>
                <input type="button" value="Voltar" onClick="JavaScript: window.history.back();">
                <?= $botaosalvar ?>
            </center>    
        </form>
        <br />
        <hr/>          
        <br>
        <div class="estiloTabelas table-responsive">
            <h3>Hóspedes da reserva</h3>
            <table  border="2">
                <tr>
                    <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nome</b></td>
                    <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> CPF/RG</b></td>
                    <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Parentesco</b></td>
                </tr>
                <?php
                $sql = "SELECT * from hospede where  id_locacao = '{$id_locacao}'";
                $filtro = mysql_query($sql);
                $num_rows = mysql_num_rows($filtro);
                while ($ln = mysql_fetch_array($filtro)) {
                    ?>
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><?= strtoupper($ln['nome_hospede']) ?></td>
                        <td align="left"><font size="2"; color="#000000"><?= strtoupper($ln['doc_hospede']) ?></td>
                        <td align="left"><font size="2"; color="#000000"><?= strtoupper($ln['parentesco_hospede']) ?></td>
                    </tr>
                    <?php
                } // Fecha Loop 
                ?>
            </table>
            <br>
            <!--</table>--> 
            </body>
            </html>
        </div>
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->


<?php
include "rodape.php";
?>