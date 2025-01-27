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

$id_unidade = $_REQUEST['dado'];


$consulta = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
        . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
        . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario"
        . " WHERE reg.id_unidade = " . $id_unidade;

$consulta = mysql_query($consulta);
$ln = mysql_fetch_array($consulta);
//echo (var_dump($ln));
//exit();
if (mysql_num_rows($consulta) != true) {
    echo "<meta http-equiv='refresh' content='0; URL=registrar_ocorrencia.php'>
    <script type=\"text/javascript\">
    alert(\"Unidade não possui ocorrências cadastradas!\");
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

//$dt_entrada = DateTime::createFromFormat('Y-m-d', $ln['dt_entrada'])->format('d/m/Y');
//$dt_saida = DateTime::createFromFormat('Y-m-d', $ln['dt_saida'])->format('d/m/Y');

$telefone = $ln['telefone'];
$telefone = str_replace(".", "", $telefone);
$telefone = str_replace("-", "", $telefone);
$telefone = str_replace(" ", "", $telefone);
$telefone = str_replace("(", "", $telefone);
$telefone = str_replace(")", "", $telefone);
$telefone = mask($telefone, '(##)#####-#####');
if (!is_numeric($telefone)) {
    $telefone = "Não registrado";
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
$listalocacao = false;
?>
<div id="conteudo">

    <div id="cont">
        <h2>Visualizar registro de ocorrências</h2>

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
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $telefone ?>" size="16" maxlength="16" disabled /></th>
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
            <hr><p>
            <h3>Ocorrência(s) registrada(s)</h3>
            <div class="estiloTabelas table-responsive">
                <table width="100%" border="2" border-collapse: collapse;>
                       <tr>
                        <td width="40%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Autor </b></td>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data/Hora do Registro </b></td>
                        <td width="40%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Descrição da ocorrência</b></td>

                    </tr>
                    <?php
                    $sql = "SELECT * from registro_ocorrencia where id_unidade =" . $id_unidade;
                    $consulta2 = mysql_query($sql);
                    while ($ln_locacao = mysql_fetch_array($consulta2)) {
                        date_default_timezone_set('America/Sao_Paulo');
                        $data = new DateTime($ln_locacao['dt_registro']);
                        $dataFormatada = $data->format('d/m/Y \a\s H:i:s');
                        ?>
                        <tr>
                            <td align="left"><font size="2"; color="#000000">  <?= $ln_locacao['autor'] ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp<?= $dataFormatada ?></td>
                            <td align="left" border-collapse: collapse;><font size="2"; color="#000000"><?= $ln_locacao['ocorrencia'] ?> </td>
                        </tr>
                        <?php
                    } // Fecha Loop 
                    ?>
                </table>
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