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
date_default_timezone_set('America/Sao_Paulo');

$disabled = 'disabled';
$cpf = $_COOKIE['usuario'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);
$descParentesco = 'Vínculo';

// Carrega dados do proprietario listado 
$sql = "SELECT * FROM proprietario WHERE CPF = '$cpf'";
$sql = mysql_query($sql);
$ln = mysql_fetch_array($sql);
$id_proprietario = $ln['id_proprietario'];

//Verifica se tem unidade cadastrada
$consulta = "SELECT * FROM unidade WHERE id_proprietario = '$id_proprietario'";
$consulta = mysql_query($consulta);
if (mysql_num_rows($consulta) != true) {
    echo "<meta http-equiv='refresh' content='0; URL=cadastra_unidade.php'>
    <script type=\"text/javascript\">
    alert(\"O Proprietário não possui unidades cadastradas!\");
    alert(\"Cadastre-as primeiramente!\");
    </script>
  ";
    return die;
}

$tamanho = strlen($cpf);
if ($tamanho > 11) {
    $cpf = mask($cpf, '##.###.###/####-##');
} else {
    $cpf = mask($cpf, '###.###.###-##');
}
$listalocacao = false;
?>
<div id="conteudo">

    <div id="cont">
        <h2>Cadastro de Reservas</h2>

        <hr>
        <form method="post" action="funcoes/salvar_locacao.php" enctype="multipart/form-data">

            <table width="70%" border="0">
                <tr>
                    <!--<td colspan="2"> <b>Dados Pessoais:</b></td>-->
                </tr>
<!--                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                    <th width="25%" align="left" scope="col">
                        <input name="cpf" type="text" class="imput" id="cpf" size="14" maxlength="14" value="<?= $cpf ?>"
                               placeholder="Somente números"  disabled />
                        <input type="hidden" name="cpf" value="<?= $cpf ?>" />

                    </th>
                </tr>-->
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['nome'] ?>" name="nome" size="60" disabled/></th>
                <input type="hidden" name="cpf" value="<?= $cpf ?>" />
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidade/Etapa:</th>
                    <td>
                        <select name="id_unidade">
                            <?php
                            echo "<option value=''></option>";
                            while ($dados = mysql_fetch_array($consulta)) {
                                $registro = $dados['etapa'] . ' / Casa ' . $dados['numero_etapa'];
                                $idregistro = $dados['id_unidade'];
                                echo "<option value='$idregistro'>$registro</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Qtde Hospedes:</th>
                    <th width="25%" align="left" scope="col"><input type="text" id="qtde_hosp" name="qtde_hosp" value="" size="10" maxlength="2"  /></th>
                </tr>
                <!--==========-->
                <tr width="60%">
                    <th width="5%" align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</font></th>
                    <td width="100%">
                        <input type="text" id="dt_entrada" name="dt_entrada" size="10" maxlength="10"/>
                        <font size="2"><b>Data de saída:</b></font>
                        <input type="text" id="dt_saida" name="dt_saida" size="10" maxlength="10"/>
                    </td>
                </tr> 

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Horário previsto de chegada:</th>
                    <th width="25%" align="left" scope="col"> <input type="text" id="hr_chegada" name="hr_chegada"  value="" size="10" maxlength="5" oninput="validarHora(this)" placeholder="HH:MM"/></th>
                </tr>            
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Responsável pela locação:</font></th>
                    <td>
                        <input type="text" value="" name="resp_loc" size="60" maxlength="60"/>

                    </td>
                </tr>
                 <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Identificação:</font></th>
                    <td>
                        <input type="text" value="" name="identificacao_resp_loc" size="20" maxlength="20"/>
                        <!--                        <font size="2"><b>Vínculo: </b></font>
                                                <input type="text" id="parentesco" name="parentesco" size="26" maxlength="20" placeholder="Exemplo: pai, filho, sócio, filiado..."/>-->

                        <font size="2"><b>Vínculo: </b></font>
                        <select id="parentesco" name="parentesco">
                            <option value="">Selecione...</option>
                            <option value = "Parente até 4º Grau">Parente até 4º Grau</option>
                            <option value = "Convidado">Convidado</option>
                            <option value = "Locação por Temporada">Locação por Temporada</option>
                        </select>
					</td>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="MascaraTelefone(telefone)"  name="telefone" value="" size="20" maxlength="16"  /></th>
                </tr>
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Informações complementares:</b> </th>
                    <th width="15%" align="left" scope="col"><textarea name="complementares" cols="57" rows="5" placeholder="Exemplo: placa do carro, marca e modelo, quantidade de veículos..."></textarea></th> 
                </tr>
            </table>

            <script>
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
                <input type="submit" name="botao" value="Cadastrar locação" />
            </center>    
        </form>
        <br />
        <?php
// Carrega dados de locação 

        $sql_locacao = "SELECT * FROM locacao where id_proprietario = '$id_proprietario' order by dt_entrada desc";
        $sql_locacao = mysql_query($sql_locacao);
        if (mysql_num_rows($sql_locacao) == true) {
            $listalocacao = true;
        }
        if ($listalocacao == true) {
            ?>
            <hr>
            <h3>Relação de reservas cadastradas(s)</h3>

            <div class="estiloTabelas">
                <table  width="100%" border="2">
                    <tr>
                        <td width="2%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <!--<td width="2%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nº Quartos</b></td>-->
                        <!--<td width="2%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Capacidade</b></td>-->
                        <td width="2%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Qtde Hospedes</b></td>
                        <td width="4%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Entrada</b></td>
                        <td width="4%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Saída</b></td>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Responsável</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Contato</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="5"><font size="2"; color="#F5FFFA"><b> Ações</b></td>

                    </tr>
                    <?php
                    while ($ln_locacao = mysql_fetch_array($sql_locacao)) {
                        $id_unidade = $ln_locacao['id_unidade'];
                        $sql_unidade = "SELECT * FROM unidade where id_unidade = '$id_unidade' ";
                        $sql_unidade = mysql_query($sql_unidade);
                        $ln_unidade = mysql_fetch_array($sql_unidade);

                        $editarReserva = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<a href="editar_reserva.php?id=' . $ln_locacao['id_locacao'] . '"   title="Editar Reserva">' .
                                '<img src="images/complementar.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';

                        $incluirHospede = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<a href="cadastra_hospede.php?id=' . $ln_locacao['id_locacao'] . '"   title="Incluir Hóspede">' .
                                '<img src="images/addhospede.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';

                        $dataComparacao = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_saida']);
                        $dataComparacaoEntrada = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_entrada']);
                        $hoje = new DateTime();
                        $hoje->setTime(0, 0); // Reseta o horário para meia-noite

                        If (!isDataMaiorQueHoje($ln_locacao['dt_entrada'])) {
                            $editarReserva = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<img src="images/encerrado.png"  height=20 width=20 align="middle" border="0" title="Reserva em vigência - Não Editável">' .
                                    '</td>';
                            $incluirHospede = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<img src="images/porta.png"  height=20 width=20 align="middle" border="0" title="Reserva em vigência - Não Editável">' .
                                    '</td>';
                        }
                        If (!isDataMaiorQueHoje($ln_locacao['dt_saida'])) {
                            $editarReserva = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<img src="images/encerrado.png"  height=20 width=20 align="middle" border="0" title="Reserva expirada">' .
                                    '</td>';
                            $incluirHospede = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<img src="images/porta.png"  height=20 width=20 align="middle" border="0" title="Reserva expirada">' .
                                    '</td>';
                        }

                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<a href="autorizacao.php?id=' . $ln_locacao['id_locacao'] . '"  target="_blank" title="Emitir Autorização de hospedagem">' .
                                '<img src="images/print_black.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';

                        $situacao = $ln_locacao['id_locacao'] . '&d=d';
                        $situacaoReserva = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                ' <a href="creserva.php?dado=' . $situacao . '" title="Visualizar situação da Reserva"> ' .
                                '<img src="images/visualizar1.png"  title="Visualizar situação da Reserva" height=20 width=20 align="middle" border="0">' .
                                '</td>';

                        $dt_entrada = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_entrada'])->format('d/m/Y');
                        $dt_saida = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_saida'])->format('d/m/Y');

                        switch ($ln_unidade['etapa']) {
                            case 'Azaléia - AZ':
                                $etapa = 'AZ/' . $ln_unidade['numero_etapa'];
                                break;
                            case 'Bougainville - BO':
                                $etapa = 'BO/' . $ln_unidade['numero_etapa'];
                                break;
                            case 'Gardênia - GA':
                                $etapa = 'GA/' . $ln_unidade['numero_etapa'];
                                ;
                                break;
                            case 'Jacarandás - JAC':
                                $etapa = 'JAC/' . $ln_unidade['numero_etapa'];
                                break;
                            case 'Orquídeas - OR':
                                $etapa = 'OR/' . $ln_unidade['numero_etapa'];
                                break;
                            case 'Pitangueiras - PIT':
                                $etapa = 'PIT/' . $ln_unidade['numero_etapa'];
                                break;
                        }
                        ?>
                        <tr>
                            <td align="center"><font size="2"; color="#000000"><?= $etapa ?></td>
        <!--                            <td align="center"><font size="2"; color="#000000">  <?= $ln_unidade['numero_etapa'] ?></td>-->
                            <td align="left"><font size="2"; color="#000000"><?= $ln_unidade['tipo_unidade'] ?></td>
                            <!--<td align="center"><font size="2"; color="#000000"><?= $ln_unidade['qtde_quartos'] ?> </td>-->
                            <!--<td align="center"><font size="2"; color="#000000"><?= $ln_unidade['capacidade'] ?> </td>-->

                            <td align="center"><font size="2"; color="#000000"><?= $ln_locacao['qtde_hospedes'] ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_entrada ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_saida ?> </td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln_locacao['resp_locacao'] ?> </td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln_locacao['contato_resp'] ?> </td>

                            <?= $editarReserva ?> 
                            <?= $incluirHospede ?> 
                                <!--                            <td align="center" bgcolor="#E9E9E9"><a href="cadastra_hospede.php?id=<?= $ln_locacao['id_locacao'] ?>" title="Incluir Hóspede"> 
                                                                    <img src="images/addhospede.png"  height=20 width=20 align='middle' border="0"></a></td> comment  -->
                            <?= $visualizarcomprovante ?> 
                            <?= $situacaoReserva ?> 

                            <td align="center" bgcolor="#E9E9E9"><a href="funcoes/funcoes.php?t=loc&funcao=excluir&id=<?= $ln_locacao['id_locacao'] ?>" title="Excluir"> 
                                    <img src="images/lixeira.jpg"  height=20 width=20 align='middle' border="0"></a></td>

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

function isDataMaiorQueHoje($data) {
    $dataComparacao = new DateTime($data);
    $dataComparacao = $dataComparacao->format('Ymd'); // Converte para formato 'yyyymmaa'
    $hoje = new DateTime();
    $hoje = $hoje->format('Ymd');
    return $dataComparacao >= $hoje;
}
?>