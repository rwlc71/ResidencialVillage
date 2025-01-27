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
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";

$id_locacao = $_REQUEST['id'];

$consulta = "SELECT loc.*, uni.*, prop.id_proprietario, prop.nome, prop.CPF FROM locacao loc "
        . " JOIN proprietario prop ON loc.id_proprietario = prop.id_proprietario"
        . " JOIN unidade uni on loc.id_unidade = uni.id_unidade  "
        . " WHERE loc.id_locacao = " . $id_locacao . " order by loc.dt_entrada";
//echo($consulta);
//echo($id_locacao);
//exit();
$consulta = mysql_query($consulta);
$ln = mysql_fetch_array($consulta);
//echo (var_dump($ln));
//exit();
if (mysql_num_rows($consulta) != true) {
    echo "<meta http-equiv='refresh' content='0; '>
    <script type=\"text/javascript\">
    alert(\"Proprietário não possui reservas cadastradas!\");
    alert(\"Cadastre-as primeiramente!\");
    history.back(); 
    </script>  ";
    return die;
}

$tamanho = strlen($ln['CPF']);
if ($tamanho > 11) {
    $cpf = mask($ln['CPF'], '##.###.###/####-##');
} else {
    $cpf = mask($ln['CPF'], '###.###.###-##');
}
$listalocacao = false;

$dt_entrada = DateTime::createFromFormat('Y-m-d', $ln['dt_entrada'])->format('d/m/Y');
$dt_saida = DateTime::createFromFormat('Y-m-d', $ln['dt_saida'])->format('d/m/Y');

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
        <h2>Alterações de Reservas</h2>

        <hr>
        <form method="post" action="funcoes/alterar_reserva.php"  enctype="multipart/form-data">

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
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['nome'] ?>" name="nome" size="60"  maxlength="51" disabled/></th>
                </tr>
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidade:</th>
                    <td ><input type="text"  value="<?= $etapa ?>" name="unidade" size="10" maxlength="10" disabled/>
                        <font size="2"; ><b>&nbsp;&nbsp;&nbsp;Qtde Hospedes::</b> <input type="data" value="<?= $ln['qtde_hospedes'] ?>" maxlength="3" name="qtde_hosp" size="3" />
                    </td>
                </tr> 


                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</font></th>
                    <td>
                        <input type="text" id="dt_entrada" name="dt_entrada"  value="<?= $dt_entrada ?>" size="10" maxlength="10"/>
                        <font size="2"><b>&nbsp;&nbsp;&nbsp;Data de saída:</b></font>
                        <input type="text" id="dt_saida" name="dt_saida" size="10"  value="<?= $dt_saida ?>"  maxlength="10"/>
                        <input type="hidden" name="cpf" value="<?= $cpf ?>" />
                        <input type="hidden" name="id_locacao" value="<?= $id_locacao ?>" />
                    </td>
                </tr> 

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Horário previsto de chegada:</th>
                    <th width="25%" align="left" scope="col"><input type="text" name="hr_chegada" value="<?= $ln['chegada_prevista'] ?>" size="10" maxlength="10"  /></th>
                </tr>
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Responsável pela locação:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['resp_locacao'] ?>" name="resp_loc" size="60"  maxlength="23" /></th>
                </tr>
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Identificação:</font></th>
                    <td>
                        <input type="text" value="<?= $ln['doc_identificacao_resp'] ?>"  name="identificacao_resp_loc" size="20" maxlength="20"/>
                        <font size="2"><b>Parentesco: </b></font>
                        <input type="text" value="<?= $ln['parentesco'] ?>" id="parentesco" name="parentesco" size="10" maxlength="10" placeholder="Exemplo: filho, pai, primo.."/>
                    </td>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $ln['contato_resp'] ?>" size="16" maxlength="16"  /></th>
                </tr>
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Imformações Complementares:</b> </th>
                    <th width="15%" align="left" scope="col"><textarea  name="complementares" cols="57" rows="5" placeholder="Exemplo: placa do carro, marca e modelo, quantidade de veículos..."><?= $ln['complementares'] ?></textarea></th> 
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



            </script>
            <br>
            <p></p>
            <center>
                <!--<input type="button" value="Voltar" onClick="JavaScript: window.history.back();">-->
                <input type="submit" id="salvar" name="botao" value="Salvar Alterações" />

            </center>    
        </form>
        <br />
        </table>
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>