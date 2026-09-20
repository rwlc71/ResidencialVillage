<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
//include "valida/verifica_acesso.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";

$botao = '<input type="submit" name="botao" value="Atualizar dados cadastrais" />';
$disabled = 'disabled';
$acao = 'proprietarios.php';
$cpf = $_COOKIE['usuario'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);
//echo ('botao '.$_POST['botao']);
//exit();
if ($_POST['botao'] != "") {
    $disabled = '';
    $botao = '<input type="submit" name="botao" value="Salvar dados alterados" />';
    $acao = 'funcoes/proprietario_salvar.php';
}
// Carrega dados do proprietario listado 
$sql = "SELECT * FROM proprietario WHERE CPF = '$cpf'";
$sql = mysql_query($sql);
$ln = mysql_fetch_array($sql);

$tamanho = strlen($cpf);
if ($tamanho > 11) {
    $cpf = mask($cpf, '##.###.###/####-##');
} else {
    $cpf = mask($cpf, '###.###.###-##');
}
$id_proprietario = $ln['id_proprietario'];
$listaUnidade = false;
?>
<div id="conteudo">

    <div id="cont">
        <h2>Cadastro de unidade</h2>


        <hr>
        <form method="post" action="funcoes/salvar_unidade.php" enctype="multipart/form-data">

            <table width="80%" border="0">
                <tr>
                    <!--<td colspan="2"> <b>Dados Pessoais:</b></td>-->
                </tr>

<!--                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                    <th width="25%" align="left" scope="col">
                        <input name="cpf" type="text" class="imput" id="cpf" size="14" maxlength="14" value="<?= $cpf ?>"
                               placeholder="Somente números" onkeypress='mascaraMutuarios(this, cpfCnpj)' onblur='validaCPF(this)' disabled />
                    </th>
                </tr>-->
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['nome'] ?>" name="nome" size="60" disabled/></th>
                </tr>
                <tr>
                    <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;Etapa:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <select id="Etapa" name="Etapa" ><font size="2"; color="#000000">
                            <option  value="<?= $descEtapa ?>" selected="selected"><?= $descEtapa ?></option>
                            <option value="Azaléia - AZ">Azaléia - AZ</option>
                            <option value="Bougainville - BO">Bougainville - BO</option>
                            <option value="Gardênia - GA">Gardênia - GA</option>
                            <option value="Jacarandás - JAC">Jacarandás - JAC</option>
                            <option value="Orquídeas - OR">Orquídeas - OR</option>
                            <option value="Pitangueiras - PIT">Pitangueiras - PIT</option>
                        </select>
                        &nbsp;<font size="2"; color="#000000">Unidade:
                        <input type="text" value="<?= $nrUnidade ?>" name="nr_etapa" size="3" maxlength="3" />
                    </th>        
                </tr> 
                <tr>
                    <td width="15%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;Tipo de unidade:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <select id="tipo_unidade" name="tipo_unidade" ><font size="2"; color="#000000">
                            <option  value="" selected="selected"></option>
                            <option value="Residência">Residência</option>
                            <option value="Locação Regular (+90dias)">Locação Regular (+90dias)</option>
                            <option value="Locação Temporária">Locação Temporária</option>
                        </select>
                    </th>        
                </tr> 
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;Número de quartos:</th>
                    <td ><input type="text" value="" name="qtde_quarto" size="5" maxlength="2"   />
                        <font size="2"; ><b>Capacidade da unidade:</b> <input type="text" value="" maxlength="2" name="capacidade" size="5"   />
                        <input type="hidden" name="cpf" value="<?= $cpf ?>" />

                    </td>
                </tr>  
                <tr>
                <p></p>
                <p></p>
                <td align="left" width="15% bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;Documento de Titularidade:</b> </td>
                <td align="left" ><input type="file" name="comprovante"  /> </td>
                </tr>

            </table>

            <br>
            <p></p>
            <center>
                <input type="submit" name="botao" value="Cadastrar unidade" />
                <input type="submit" name="botao" value="Alterar unidade" />
            </center>    
        </form>
        <br />
        <?php
        // Carrega dados de unidade 

        $sql1 = "SELECT * FROM unidade WHERE id_proprietario = '$id_proprietario'";
        $sql1 = mysql_query($sql1);
        if (mysql_num_rows($sql1) == true) {
            $listaUnidade = true;
        }
        if ($listaUnidade == true) {
            ?>
            <hr>
            <h3>Relação de unidade(s) cadastrada(s)</h3>
            <div class="estiloTabelas">
                <table  border="2">
                    <tr>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Unidade</b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nº Quartos</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Capacidade</b></td>
                        <!--<td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Link comprovante</b></td>-->
                        <td width="3%" align="center" bgcolor="#191970" colspan="3"><font size="2"; color="#F5FFFA"><b> Ação</b></td>

                    </tr>
                    <?php
//                $sql1 = "SELECT * FROM dependente WHERE id_proprietario = '$id_proprietario'";
                    while ($ln = mysql_fetch_array($sql1)) {
                        $visualizarcomprovante = '<td align="center"><font size="2"; color="#000000"><?=' . $ln['comprovante_titularidade'] . '?></td>';
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<a href="" title="Documento indisponível">' .
                                '<img src="images/quebra.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';
                        if ($ln['comprovante_titularidade']) {
                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="documentostitularidade/' . $ln['comprovante_titularidade'] . '"  target="_blank" title="Ver documento de titularidade">' .
                                    '<img src="images/ver.jpg"  height=20 width=20 align="middle" border="0">' .
                                    '</a>' .
                                    '</td>';
                        }
                        ?>
                        <tr>
                            <td align="center"><font size="2"; color="#000000"><?= ($ln['etapa']) ?></td>
                            <td align="center"><font size="2"; color="#000000">  <?= $ln['numero_etapa'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['tipo_unidade'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['qtde_quartos'] ?> quartos</td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['capacidade'] ?> pessoas</td>
                            <?= $visualizarcomprovante ?>
                            <td align="center" valign="middle" bgcolor="#FFFFFA">
                               <a href="editar_unidade.php?id=<?= $ln['id_unidade'] ?>" title="Editar unidade">
                                    <img src="images/complementar.png"  height=20 width=20 align="middle" border="0">
                                </a>
                            </td>
                            <td align="center" bgcolor="#E9E9E9"><a href="funcoes/funcoes.php?t=un&funcao=excluir&id=<?= $ln['id_unidade'] ?>" title="Excluir"> 
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
?>