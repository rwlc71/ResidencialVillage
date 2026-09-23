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
        // Inicializar o datepicker
        $("#dt_entrada").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });
    });
</script>

<?php
session_name('SESSAO_PHP');
include "topo.php";
include "conexao.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
$totalHospedes = 0;

$btn = '<input type="submit" name="botao" value="Pesquisar" > '
        . ' <input type="submit" name="botao" value="Cadastrar Ocorrência" />';
if ($_COOKIE['tipo_acesso'] == 'adm') {
    $btn = '<input type="submit" name="botao" value="Pesquisar" >';
}
$dataRegistro = '';
if ($_REQUEST['botao'] == "Cadastrar Ocorrência") {
    $dados = [
        'autor' => $_COOKIE['nome_usuario'],
        'usuario' => $_COOKIE['usuario'],
        'proprietario' => $_REQUEST['proprietario'],
        'id_unidade' => $_REQUEST['unidades'],
        'email' => $_REQUEST['email'],
        'ocorrencia' => $_REQUEST['ocorrencia'],
        'proprietario' => $_REQUEST['proprietario'],
        'id_proprietario' => $_REQUEST['id_proprietario'],
        'unidade' => $_REQUEST['unidade'],
        'etapa' => $_REQUEST['etapa'],
    ];

    $host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($path, '/'));
    $firstPart = isset($parts[0]) ? $parts[0] : '';
    $url = $host . "/" . $firstPart . "/funcoes/salvar_registro_ocorrencia.php";

    // Configuração e envio da requisição POST via cURL
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($dados)
    ]);
    $r = curl_exec($ch);
    curl_close($ch);

    // Decodificar a resposta JSON
    $re = json_decode($r, true);

// Verificar se o JSON foi decodificado corretamente
    $msg = $re['message'];
    $status = $re['status'];

    if ($status == 'success') {
        echo "<meta http-equiv='refresh' content='0; URL= cadastra_ocorrencias.php'>
                    <script type=\"text/javascript\">
                    alert(\"$msg!\");
                    </script> ";
        return die;
    } else {
        $msgJs = isset($msg) ? str_replace(array('\\', '"'), array('\\\\', '\\"'), $msg) : 'Falha ao registrar ocorrência.';
        echo "<script type=\"text/javascript\">
            alert(\"" . $msgJs . "\");
          </script>  ";
    }
}
?>


<div id="conteudo">
    <div id="cont">
        <body>
            <h2>Registro de ocorrências</h2> <br>
            <hr />
            <form method="post" action="cadastra_ocorrencias.php">
                <br>
                <table width="80%" border="0">
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                     id="txtNome" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     onblur="buscarDadosProprietario()"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>

                    <input type="hidden" name="id_proprietario" id="id_proprietario"  value="" />
                    <input type="hidden" name="email" id="email"  value="" />
                    <input type="hidden" name="unidade" id="unidade"  value="" />
                    <input type="hidden" name="etapa" id="etapa"  value="" />

<!--                    <tr>
                        <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Etapa:</b> </td>
                        <th width="25%" align="left" scope="col">
                            <select id="Etapa" name="nomeEtapa" ><font size="20"; color="#000000">
                                <option  value="<?= $descEtapa ?>" selected="selected"><?= $descEtapa ?></option>
                                <option value="Azaléia - AZ">Azaléia - AZ</option>
                                <option value="Bougainville - BO">Bougainville - BO</option>
                                <option value="Gardênia - GA">Gardênia - GA</option>
                                <option value="Jacarandás - JAC">Jacarandás - JAC</option>
                                <option value="Orquídeas - OR">Orquídeas - OR</option>
                                <option value="Pitangueiras - PIT">Pitangueiras - PIT</option>
                            </select>
                            &nbsp;<font size="2"; color="#000000">Unidade:
                            <input type="text" value="<?= $nrUnidade ?>" name="numero_unidade" size="3" maxlength="3" />
                        </th>        
                    </tr>-->

                    <tr>
                        <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidade/Etapa:</th>
                        <td>
                            <select name="unidades" id="unidades">
                                <option value="">Selecione...</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de registro:</font></th>
                        <td>
                            <input type="text" id="dt_entrada" name="dt_entrada"  value="<?= $dataRegistro ?> " size="10" maxlength="10"/>
                        </td>
                    </tr> 
                    <tr>
                        <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Relato da Ocorrência:</b> </th>
                        <th width="15%" align="left" scope="col"><textarea id="ocorrencia" name="ocorrencia" cols="57" rows="5" maxlength="998" oninput="atualizarContador()"></textarea>
                            <span id="contador" style="font-size: 14px; color: #555;">0/1000 caracteres</span></th> 


                    </tr>
                </table>
                <br>
                <center>
                    <!--<input type="button" value="Voltar" onClick="JavaScript: window.history.back();">-->
                    <?= $btn ?>
<!--                    <input type="submit" name="botao" value="Pesquisar" >
                    <input type="submit" name="botao" value="Cadastrar Ocorrência" />-->
                </center>  
                <br />
            </form>
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

                    var $dateFields = $("#dt_entrada");
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
            <hr/>          
            <br>
            <div class="estiloTabelas table-responsive">
                <h3>Relatórios de ocorrências</center></h3>
                <table width="85%" border="2">
                    <tr>
                        <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data/Hora do registro</b></td>
                        <td width="18%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Proprietário</b></td>
                        <td width="3%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <td width="30%" align="center" bgcolor="#191970" border-collapse: collapse;><font size="2"; color="#F5FFFA"><b> Registro</b></td>

                    </tr>
                    <?php
                    $sql = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
                            . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
                            . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario"
                            . " ORDER BY reg.data_registro DESC";
                    

                    $sql = mysql_query($sql);
                    $num_rows = mysql_num_rows($sql);
                    $where = Array();

                    if ($_POST['proprietario']) {
                        $where[] = " prop.nome  LIKE  '%" . $_POST['proprietario'] . "%'";
                    }
                    if ($_POST['ocorrencia']) {
                        $where[] = " reg.ocorrencia  LIKE  '%" . $_POST['ocorrencia'] . "%'";
                    }
                    if (trim($_POST['dt_entrada']) != '') {
                        $dt_entrada = $_POST['dt_entrada'];  // A string com a data no formato '17/01/2025'
                        $data = DateTime::createFromFormat('d/m/Y', $dt_entrada);
                        $dataFormatada = $data->format('Y-m-d');
                        $where[] = " reg.data_registro >= '$dataFormatada 00:00:00' ";
                    }
//                    if ($_POST['nomeEtapa']) {
//                        $where[] = " uni.etapa  LIKE  '%" . $_POST['nomeEtapa'] . "%'";
//                    }
//                    if ($_POST['numero_unidade']) {
//                        $where[] = " uni.numero_etapa  LIKE  '%" . $_POST['numero_unidade'] . "%'";
//                    }

                    $sql = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
                            . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
                            . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario"
                            . " ORDER BY reg.data_registro DESC";


                    if (sizeof($where)) {
                        $sql = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
                                . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
                                . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . " ORDER BY reg.data_registro DESC";
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
                        $tamanho = strlen($ln['CPF']);
                        if ($tamanho > 11) {
                            $ln['CPF'] = mask($ln['CPF'], '##.###.###/####-##');
                        } else {
                            $ln['CPF'] = mask($ln['CPF'], '###.###.###-##');
                        }
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

                        $data_registro = $ln['data_registro'];  // A data no formato 'Y-m-d'
                        $data_registro = DateTime::createFromFormat('d-m-y', $data_registro);
                        date_default_timezone_set('America/Sao_Paulo');
                        $data = new DateTime($ln['data_registro']);
                        $dataFormatada = $data->format('d/m/Y \a\s H:i:s');
                        ?>
                        <tr>
                            <td align="center"><font size="2"; color="#000000"><?= $dataFormatada ?> </td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln['nome'] ?></td>
                            <td align="center"><font size="2"; color="#000000">  <?= $etapa ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['tipo_unidade'] ?></td>
                            <td align="left" border-collapse: collapse;><font size="2"; color="#000000"><?= $ln['ocorrencia'] ?> </td>
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