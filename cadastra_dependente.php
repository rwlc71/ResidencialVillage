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
$sql = "SELECT * FROM proprietario WHERE CPF = '$cpf' ";
$sql = mysql_query($sql);
$ln = mysql_fetch_array($sql);

$tamanho = strlen($cpf);
if ($tamanho > 11) {
    $cpf = mask($cpf, '##.###.###/####-##');
    $select = '<option  value="" selected="selected"></option>'
            . '<option value="Filiado, sócio ou membro">Filiado, sócio ou membro</option>';
} else {
    $cpf = mask($cpf, '###.###.###-##');
    $select = '<option  value="" selected="selected"></option>'
            . '<option value="Esposa / Marido">Esposa / Marido</option>'
            . '<option value="1º Grau">1º Grau</option>'
            . '<option value="2º Grau">2º Grau</option>'
            . '<option value="3º Grau">3º Grau</option>'
            . '<option value="4º Grau">4º Grau</option>';
}



$id_proprietario = $ln['id_proprietario'];
$listaDependente = false;
?>
<div id="conteudo">

    <div id="cont">
        <h2>Cadastro de Dependente(s) / Filiado(s)</h2>
        <hr>
        <form method="post" action="funcoes/salvar_dependente.php" enctype="multipart/form-data">

            <table width="95%" border="0">
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
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= strtoupper($ln['nome']) ?>" name="nome" size="60" disabled/></th>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome do dependente:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="nome_dependente" size="60"    /></th>
                </tr>

                <tr>
                    <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Grau de parentesco/vínculo:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <select id="Etapa" name="Parentesco" ><font size="20"; color="#000000">
                            <?= $select ?>
                            <!--                            <option  value="" selected="selected"></option>
                            <option value="Esposa / Marido">Esposa / Marido</option>
                            <option value="1º Grau">1º Grau</option>
                            <option value="2º Grau">2º Grau</option>
                            <option value="3º Grau">3º Grau</option>
                            <option value="4º Grau">4º Grau</option>-->
                        </select>
                        <input type="hidden" name="cpf" value="<?= $cpf ?>" />
                    </th>        
                </tr> 
                <tr>
                    <th width="7%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Documento de identificação:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="id_dependente" size="30"    /></th>
                </tr>
                <tr>
                <p></p>
                <td align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inserir Foto:</b> </td>
                <td align="left"><input type="file" name="foto_dependente"  /> </td>
                </tr>

            </table>
            <br>
            <table width="95%" border="0">
                <div id="msg">
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ATENÇÃO</b>:<p>
                    <dt><dd> Conforme previsão regimental, são considerados dependentes todos os familiares consanguíneos e 
                        por afinidade até o 4º grau, em linha reta ou colateral:</dd></dt> 
                    <dt><dd><ol style="list-style-type: disc">
                            <li>Esposa / Marido</li>
                            <li>1º Grau: Pais e Filhos</li>
                            <li>2º Grau: Avós, Irmãos, Netos, Cunhados(as), Genro e Nora</li>
                            <li>3º Grau: Bisavós, Tios, Sobrinhos e Bisnetos</li>
                            <li>4º Grau: Trisavós, Primos e Trinetos</li>
                        </ol></dd></dt> 

                    <dt><dd> Considera-se usufrutuário todos os filiados, sócios, associados ou membros, 
                        cuja propriedade esteja registrada em nome de uma entidade juridica.</dd></dt> 

                </div>
            </table>
            <br>
            <p></p>
            <center>
                <input type="submit" name="botao" value="Cadastrar dependente" />
            </center>    
        </form>
        <?php
        // Carrega dados de dependentes 

        $sql1 = "SELECT * FROM dependente WHERE id_proprietario = '$id_proprietario' order by nome_dependente";
        $sql1 = mysql_query($sql1);
        if (mysql_num_rows($sql1) == true) {
            $listaDependente = true;
        }
        if ($listaDependente == true) {
            ?>
            <hr>
            <h3>Relação de dependente(s) cadastrado(s)</h3>
            <div class="estiloTabelas">
                <table width="60%" border="2">
                    <tr>
                        <td width="30%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nome</b></td>
                        <td width="13%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Identificação</b></td>
                        <td width="13%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Grau de parentesco / vínculo:</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="2"><font size="2"; color="#F5FFFA"><b> Ação</b></td>

                    </tr>
                    <?php
//                $sql1 = "SELECT * FROM dependente WHERE id_proprietario = '$id_proprietario'";
                    while ($ln = mysql_fetch_array($sql1)) {
                        if ($ln['foto_dependente']) {


                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="#" onclick="openModal(\'documentostitularidade/' . $ln['foto_dependente'] . '\')" title="Visualizar foto">' .
                                    '<img src="images/visualizar1.jpg" height="20" width="20" align="middle" border="0"> </a>' .
                                    '</td>';
//                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
//                                    '<a href="documentostitularidade/' . $ln['foto_dependente'] . '"  target="_blank" title="Visualizar foto">' .
////                                '<img src="documentostitularidade/' . $ln['foto_pet'] . '"  height=20 width=20 align="middle" border="0">' .
//                                    '<img src="images/visualizar1.jpg"  height=20 width=20 align="middle" border="0">' .
//                                    '</a>' .
//                                    '</td>';
                        }
                        ?>
                        <tr>
                            <td align=""><font size="2"; color="#000000">&nbsp;&nbsp;<?= strtoupper($ln['nome_dependente']) ?></td>
                            <td align=""><font size="2"; color="#000000">&nbsp;&nbsp;<?= strtoupper($ln['doc_indentificacao_dependente']) ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['parentesco'] ?></td>
                            <?= $visualizarcomprovante ?>

                            <td align="center" bgcolor="#E9E9E9"><a href="funcoes/funcoes.php?t=de&funcao=excluir&id=<?= $ln['id_dependente'] ?>" title="Excluir"> 
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