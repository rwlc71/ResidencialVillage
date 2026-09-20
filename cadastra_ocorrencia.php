<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/componentes.js"></script>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";

$id_audita = $_REQUEST['dado'];
$consulta = "SELECT aud.*, uni.*, prop.id_proprietario, prop.nome, prop.CPF FROM audita aud "
        . "JOIN proprietario prop ON aud.id_proprietario = prop.id_proprietario"
        . " JOIN unidade uni on aud.id_unidade = uni.id_unidade  "
        . " WHERE aud.id_audita = " . $id_audita . " order by aud.dt_entrada";

$consulta = mysql_query($consulta);
$ln = mysql_fetch_array($consulta);
//echo (var_dump($ln));
//exit();
if (mysql_num_rows($consulta) != true) {
    echo "<meta http-equiv='refresh' content='0; URL=seguranca.php'>
    <script type=\"text/javascript\">
    alert(\"Proprietário não possui reservas cadastradas!\");
    alert(\"Cadastre-as primeiramente!\");
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
        <h2>Pagina de registro de ocorrências</h2>

        <hr>
        <form method="post" action="funcoes/salvar_ocorrencia.php" enctype="multipart/form-data">

            <table width="70%" border="0">
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
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $ln['contato_resp'] ?>" size="16" maxlength="16" disabled /></th>
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
                <input type="button" value="Voltar" onClick="JavaScript: window.history.back();">
                <input type="submit" name="botao" value="Cadastrar Ocorrência" />
            </center>    
        </form>
        <br />
        <?php
// Carrega dados de locação 

        $sql_locacao = "SELECT * FROM ocorrencias where id_locacao= '$id_audita' ";

        $sql_locacao = "SELECT oco.*, loc.*, uni.*, prop.id_proprietario, prop.nome, prop.CPF FROM locacao loc "
                . "JOIN proprietario prop ON loc.id_proprietario = prop.id_proprietario"
                . " JOIN unidade uni on loc.id_unidade = uni.id_unidade  "
                . " JOIN ocorrencias oco on oco.id_locacao = loc.id_locacao  "
                . " WHERE oco.id_locacao= '$id_audita' ";
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

                        $dt_entrada = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_entrada'])->format('d/m/Y');
                        $dt_saida = DateTime::createFromFormat('Y-m-d', $ln_locacao['dt_saida'])->format('d/m/Y');

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