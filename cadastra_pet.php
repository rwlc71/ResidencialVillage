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

$botao = '<input type="submit" name="botao" value="Atualizar dados cadastrais" />';
$disabled = 'disabled';
$acao = 'proprietarios.php';
$cpf = $_COOKIE['usuario'];
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
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);
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
        <h2>Cadastro de animais de estimação</h2>
        <hr>

        <form method="post" action="funcoes/salvar_pet.php" enctype="multipart/form-data">

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
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['nome'] ?>" name="nome" size="60" disabled/></th>
                </tr>

                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome do animal:</th>
                    <td ><input type="text" value="" name="nome_pet" size="20" maxlength="40"   />
                        <font size="2"; ><b>Tipo do animal:</b> <input type="text" value="" maxlength="30" name="tipo_pet" size="20" placeholder="Exemplo: Gato, cachorro, pássaro..."   />
                        <input type="hidden" name="cpf" value="<?= $cpf ?>" />

                    </td>
                </tr>  
                <tr>
                <p></p>
                <p></p>
                <td align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inserir Foto:</b> </td>
                <td align="left"><input type="file" name="comprovante"  /> </td>
                </tr>

            </table>

            <br>
            <p></p>
            <center>
                <input type="submit" name="botao" value="Cadastrar" />
<!--                <input type="submit" name="botao" value="Alterar Animal" />-->
            </center>    
            <p><p><hr>
            <b>Definição:</b> <i>Animais de estimação são animais domésticos que são cuidados por seres humanos 
                para oferecer companhia e divertimento. 
                Eles são mantidos em casa, geralmente, e podem ter uma relação de afeição, dependência, interação ou companhia com os humanos.</i>

            <hr>
        </form>
        <br />
        <?php
// Carrega dados de dependentes 

        $sql1 = "SELECT * FROM pets WHERE id_proprietario = '$id_proprietario'";
        $sql1 = mysql_query($sql1);
        if (mysql_num_rows($sql1) == true) {
            $listaUnidade = true;
        }
        if ($listaUnidade == true) {
            ?>
            <hr>
            <h3>Relação de animais(s) cadastrado(s)</h3>
            <div class="estiloTabelas">
                <table width="60%" border="2">
                    <tr>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nome do Animal</b></td>
                        <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo do Animal</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="2"><font size="2"; color="#F5FFFA"><b> Ação</b></td>

                    </tr>
                    <?php
//                $sql1 = "SELECT * FROM dependente WHERE id_proprietario = '$id_proprietario'";
                    while ($ln = mysql_fetch_array($sql1)) {
                        $visualizarcomprovante = '<td align="center"><font size="2"; color="#000000"><?=' . $ln['foto_pet'] . '?></td>';
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<a href="" title="Documento indisponível">' .
                                '<img src="images/quebra.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';
                        if ($ln['foto_pet']) {
                             $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="#" onclick="openModal(\'documentostitularidade/' . $ln['foto_pet'] . '\')" title="Visualizar foto">' .
                                    '<img src="images/visualizar1.jpg" height="20" width="20" align="middle" border="0"> </a>' .
                                    '</td>';
//                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
//                                    '<a href="documentostitularidade/' . $ln['foto_pet'] . '"  target="_blank" title="Visualizar foto">' .
////                                '<img src="documentostitularidade/' . $ln['foto_pet'] . '"  height=20 width=20 align="middle" border="0">' .
//                                    '<img src="images/visualizar1.jpg"  height=20 width=20 align="middle" border="0">' .
//                                    '</a>' .
//                                    '</td>';
                        }
                        ?>
                        <tr>
                            <td align="center"><font size="2"; color="#000000"><?= ($ln['nome_pet']) ?></td>
                            <td align="center"><font size="2"; color="#000000">  <?= $ln['tipo_pet'] ?></td>
                            <?= $visualizarcomprovante ?>
                            <td align="center" bgcolor="#E9E9E9"><a href="funcoes/funcoes.php?t=pe&funcao=excluir&id=<?= $ln['id_pet'] ?>" title="Excluir"> 
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
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="imgModal" alt="Foto do dependente">
    </div>
<?php
include "rodape.php";
?>