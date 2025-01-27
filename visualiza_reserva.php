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
?>
<div id="conteudo">

    <div id="cont">
        <h2>Dados da Reserva</h2>
        <hr>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>Autorização de hospedagem: </b>
        <a href="documentostitularidade/<?= $ln['autorizacao_hospedagem'] ?>"  target="_blank" title="Visualizar autorização de hospedagem">
            <img src="images/ver.jpg"  height=20 width=20 align="middle" border="0">
        </a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>Documento de identificação:</b>  
        <a href="documentostitularidade/<?= $ln['doc_identificacao_resp'] ?>"  target="_blank" title="Documento de indentificação do responsável">
            <img src="images/person2.png"  height=20 width=20 align="middle" border="0">
        </a>
        <hr>
        <form method="post" action="home.php" enctype="multipart/form-data">

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
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['resp_locacao'] ?>" name="resp_loc" size="60" disabled />
                    </th>
                </tr>

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $ln['contato_resp'] ?>" size="16" maxlength="16" disabled /></th>
                </tr>
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Imformações Complementares:</b> </th>
                    <th width="15%" align="left" scope="col"><textarea disabled name="ocorrencia" cols="57" rows="5" placeholder="Exemplo: placa do carro, marca e modelo, quantidade de veículos..."><?= $ln['complementares'] ?></textarea></th> 
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

        </form>
        <br />
        </table>
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>