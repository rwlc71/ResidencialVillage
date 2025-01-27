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
<script type="text/javascript" language=javascript>
    $(document).ready(function () {
        $("#txtNome").autocomplete("completar_nome.php", {
            width: 310,
            selectFirst: false
        });
    });

    $(document).ready(function () {
        $("#txtPet").autocomplete("completar_pet.php", {
            width: 310,
            selectFirst: false
        });
    });

    $(document).ready(function () {
        $("#txtCPF").autocomplete("completar_cpf.php", {
            width: 310,
            selectFirst: false
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
echo(var_dump($_REQUEST));
$id_unidade = $_REQUEST['dado'];
if ($id_unidade && $id_unidade !== null) {
    $consulta = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
            . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
            . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario"
            . " WHERE reg.id_unidade = " . $id_unidade;

    $consulta = mysql_query($consulta);
    $ln = mysql_fetch_array($consulta);
    $tamanho = strlen($ln['CPF']);
    if ($tamanho > 11) {
        $cpf = mask($ln['CPF'], '##.###.###/####-##');
    } else {
        $cpf = mask($ln['CPF'], '###.###.###-##');
    }
    $listalocacao = false;

//$dt_entrada = DateTime::createFromFormat('Y-m-d', $ln['dt_entrada'])->format('d/m/Y');
//$dt_saida = DateTime::createFromFormat('Y-m-d', $ln['dt_saida'])->format('d/m/Y');

    $telefone = $ln['telefone'];
    $telefone = str_replace(".", "", $telefone);
    $telefone = str_replace("-", "", $telefone);
    $telefone = str_replace(" ", "", $telefone);
    $telefone = str_replace("(", "", $telefone);
    $telefone = str_replace(")", "", $telefone);
    $telefone = mask($telefone, '(##)#####-#####');
    if (!is_numeric($var1)) {
        $telefone = "Não registrado";
    }

//echo($telefone);
//exit();
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
}
$listalocacao = false;
?>
<script>
    function atualizarContador() {
        var textarea = document.getElementById("ocorrencia");
        var contador = document.getElementById("contador");
        var caracteresDigitados = textarea.value.length;
        contador.textContent = caracteresDigitados + "/1000 caracteres";
    }
</script>
<div id="conteudo">

    <div id="cont">
<script type="text/javascript" language=javascript>
    $(document).ready(function () {
        $("#txtNome").autocomplete("completar_nome.php", {
            width: 310,
            selectFirst: false
        });
    });

    $(document).ready(function () {
        $("#txtPet").autocomplete("completar_pet.php", {
            width: 310,
            selectFirst: false
        });
    });

    $(document).ready(function () {
        $("#txtCPF").autocomplete("completar_cpf.php", {
            width: 310,
            selectFirst: false
        });
    });

</script>        <br>
        <hr>
        <form method="post" action="cadastrar_ocorrencia.php" enctype="multipart/form-data">
            <br>
            <table width="70%" border="0">
                <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                             id="txtNome" 
                                                             size="60" 
                                                             class="input_forms"
                                                             accept=""/><br></td> 
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidade:</th>
                    <td ><input type="text"  value="<?= $ln['etapa'] ?>" name="etapa" size="10" maxlength="10" />
                        <font size="2"; ><b>&nbsp;&nbsp;&nbsp;Unidade::</b> <input type="text" value="<?= $ln['numero_etapa'] ?>" maxlength="3" name="nr_etapa" size="3" />
                    </td>
                </tr> 
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $telefone ?>" size="16" maxlength="16"  /></th>
                </tr>
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Relato da Ocorrência:</b> </th>
                    <th width="15%" align="left" scope="col"><textarea id="ocorrencia" name="ocorrencia" cols="57" rows="5" maxlength="998" oninput="atualizarContador()"></textarea>
                        <span id="contador" style="font-size: 14px; color: #555;">0/1000 caracteres</span></th> 


                </tr>
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
            <br>
            <p></p>
            <center>
                <!--<input type="button" value="Voltar" onClick="JavaScript: window.history.back();">-->
                <input type="submit" name="botao" value="Pesquisar" >
                <input type="submit" name="botao" value="Cadastrar Ocorrência" />
            </center>    
        </form>
        <br />
        <?php
// Carrega dados de locação 


        $sql_locacao = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
                . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
                . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario";
//echo("$sql_locacao");
//exit();
//        
        $sql_locacao = mysql_query($sql_locacao);
        if (mysql_num_rows($sql_locacao) == true) {
            $listalocacao = true;
        }
        if ($listalocacao == true) {
            ?>
            <hr>
            <h3>Relação de ocorrências registradas</h3>
            <div class="estiloTabelas table-responsive">
                <table width="100%" border="2" border-collapse: collapse;>
                    <tr>
                        <td width="2%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="3%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Unidade</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <td width="4%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Entrada</b></td>
                        <td width="4%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Saída</b></td>
                        <td width="30%" align="center" bgcolor="#191970" border-collapse: collapse;><font size="2"; color="#F5FFFA"><b> Registro</b></td>
                    </tr>
                    <?php
                    while ($ln_locacao = mysql_fetch_array($sql_locacao)) {

                        $id_unidade = $ln_locacao['id_unidade'];
                        $sql_unidade = "SELECT * FROM unidade where id_unidade = '$id_unidade' ";
                        $sql_unidade = mysql_query($sql_unidade);
                        $ln_unidade = mysql_fetch_array($sql_unidade);

//                        $dt_entrada = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_entrada'])->format('d/m/Y');
//                        $dt_saida = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_saida'])->format('d/m/Y');

                        switch ($ln_unidade['etapa']) {
                            case 'Azaléia - AZ':
                                $etapa = 'AZ';
                                break;
                            case 'Bougainville - BO':
                                $etapa = 'BO';
                                break;
                            case 'Gardênia - GA':
                                $etapa = 'GA';
                                break;
                            case 'Jacarandás - JAC':
                                $etapa = 'JAC';
                                break;
                            case 'Orquídeas - OR':
                                $etapa = 'OR';
                                break;
                            case 'Pitangueiras - PIT':
                                $etapa = 'PIT';
                                break;
                            default:
                                $etapa = '';
                                break;
                        }
                        ?>
                        <tr>
                            <td align="center"><font size="2"; color="#000000"><?= $etapa ?></td>
                            <td align="center"><font size="2"; color="#000000">  <?= $ln_unidade['numero_etapa'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln_unidade['tipo_unidade'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_entrada ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_saida ?> </td>
                            <td align="left" border-collapse: collapse;><font size="2"; color="#000000"><?= $ln_locacao['ocorrencia'] ?> </td>


                        </tr>
                        <?php
                    } // Fecha Loop 
                    ?>
                </table>
            </div>
        <?php } ?>        
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>