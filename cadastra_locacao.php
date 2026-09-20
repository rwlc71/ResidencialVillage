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

$disabled = 'disabled';
$cpf = $_COOKIE['usuario'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);
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

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                    <th width="25%" align="left" scope="col">
                        <input name="cpf" type="text" class="imput" id="cpf" size="14" maxlength="14" value="<?= $cpf ?>"
                               placeholder="Somente números"  disabled />
                    </th>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['nome'] ?>" name="nome" size="60" disabled/></th>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidade/Etapa:</th>
                    <td><select name="id_unidade">
                            <?php
                            echo "<option value=''></option>";
                            while ($dados = mysql_fetch_array($consulta)) {
                                $registro = $dados['etapa'] . ' / Casa ' . $dados['numero_etapa'];
                                $idregistro = $dados['id_unidade'];
                                echo "<option value='$idregistro'>$registro</option>";
                            }
                            ?>
                        </select></td>
                <p></p>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Qtde Hospedes:</th>
                    <th width="25%" align="left" scope="col"><input type="text" name="qtde_hosp" value="" size="10" maxlength="2"  /></th>
                </tr> 
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</th>
                    <td ><input type="text" value="" name="dt_entrada" size="10" maxlength="10" onkeypress="aplicarMascaraData(dt_entrada)"   />
                        <font size="2"; ><b>&nbsp;&nbsp;&nbsp;Data de saída:</b> <input type="data" value="" maxlength="10" name="dt_saida" size="10" onkeypress="aplicarMascaraData(dt_saida)" />
                        <input type="hidden" name="cpf" value="<?= $cpf ?>" />

                    </td>
                </tr> 
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Horário previsto de chegada:</th>
                    <th width="25%" align="left" scope="col"><input type="text" name="hr_chegada" value="" size="10" maxlength="10"  /></th>
                </tr>                  

                <tr>
                <p></p>
                <p></p>
                <td width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anexar Autorização:</b> </td>
                <td align="left"><input type="file" name="comprovante" id="comprovante"   /> </td>
                </tr>
                <!--            </table>
                
                            <table width="70%" border="0">-->
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Responsável pela locação:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="resp_loc" size="60"/></th>
                </tr>
                <tr>
                <p></p>
                <p></p>
                <td width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anexar identificação:</b> </td>
                <td align="left"><input type="file" name="comprovante2" id="comprovante2"   /> </td>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)"  name="telefone" value="" size="16" maxlength="16"  /></th>
                </tr>
                <tr>
                    <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Informações complementares:</b> </th>
                    <th width="15%" align="left" scope="col"><textarea name="complementares" cols="57" rows="5" placeholder="Exemplo: placa do carro, marca e modelo, quantidade de veículos..."></textarea></th> 
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

                    function aplicarMascaraTelefone(input) {
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

        $sql_locacao = "SELECT * FROM locacao where id_proprietario = '$id_proprietario' order by dt_entrada";
        $sql_locacao = mysql_query($sql_locacao);
        if (mysql_num_rows($sql_locacao) == true) {
            $listalocacao = true;
        }
        if ($listalocacao == true) {
            ?>
            <hr>
            <h3>Relação de reservas cadastradas(s)</h3>
            
            <div class="estiloTabelas">
                <table  border="2">
                    <tr>
                        <td width="3%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Unidade</b></td>
                        <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nº Quartos</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Capacidade</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Qtde Hospedes</b></td>
                        <td width="4%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Entrada</b></td>
                        <td width="4%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Data de Saída</b></td>
                        <td width="25%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Responsável</b></td>
                        <td width="25%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Contato</b></td>
                        <!--<td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Link comprovante</b></td>-->
                        <td width="3%" align="center" bgcolor="#191970" colspan="3"><font size="2"; color="#F5FFFA"><b> Ação</b></td>

                    </tr>
                    <?php
                    while ($ln_locacao = mysql_fetch_array($sql_locacao)) {

                        $id_unidade = $ln_locacao['id_unidade'];
                        $sql_unidade = "SELECT * FROM unidade where id_unidade = '$id_unidade' ";
                        $sql_unidade = mysql_query($sql_unidade);
                        $ln_unidade = mysql_fetch_array($sql_unidade);

                        $visualizarcomprovante = '<td align="center"><font size="2"; color="#000000"><?=' . $ln_locacao['autorizacao_hospedagem'] . '?></td>';
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<a href="" title="Documento indisponível">' .
                                '<img src="images/quebra.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';
                        if ($ln_locacao['autorizacao_hospedagem']) {
                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="documentostitularidade/' . $ln_locacao['autorizacao_hospedagem'] . '"  target="_blank" title="Ver Autorização de hospedagem">' .
                                    '<img src="images/ver.jpg"  height=20 width=20 align="middle" border="0">' .
                                    '</a>' .
                                    '</td>';
                        }

                        $visualizarcomprovante2 = '<td align="center"><font size="2"; color="#000000"><?=' . $ln_locacao['doc_identificacao_resp'] . '?></td>';
                        $visualizarcomprovante2 = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<a href="" title="Documento indisponível">' .
                                '<img src="images/quebra.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';
                        if ($ln_locacao['doc_identificacao_resp']) {
                            $visualizarcomprovante2 = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="documentostitularidade/' . $ln_locacao['doc_identificacao_resp'] . '"  target="_blank" title="Documento de indentificação do responsável">' .
                                    '<img src="images/person2.png"  height=20 width=20 align="middle" border="0">' .
                                    '</a>' .
                                    '</td>';
                        }


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
                            <td align="center"><font size="2"; color="#000000"><?= $ln_unidade['qtde_quartos'] ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln_unidade['capacidade'] ?> </td>

                            <td align="center"><font size="2"; color="#000000"><?= $ln_locacao['qtde_hospedes'] ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_entrada ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $dt_saida ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln_locacao['resp_locacao'] ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln_locacao['contato_resp'] ?> </td>

                            <?= $visualizarcomprovante ?> 
                            <?= $visualizarcomprovante2 ?> 
                            <td align="center" bgcolor="#E9E9E9"><a href="funcoes/funcoes.php?t=loc&funcao=excluir&id=<?= $ln_locacao['id_locacao'] ?>" title="Excluir"> 
                                    <img src="images/excluir.jpg"  height=20 width=20 align='middle' border="0"></a></td>
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