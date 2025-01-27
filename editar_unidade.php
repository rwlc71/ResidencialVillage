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

$id_unidade = $_REQUEST['id'];
$tela = $_REQUEST['t'];
$consulta = "SELECT * FROM unidade WHERE id_unidade = '$id_unidade'";
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
$cpf = $_COOKIE['usuario'];
$nome = $_COOKIE['nome_usuario'];

// Quando vier da área administrativa cpf será outro
if ($tela == 'adm') {
    $id_proprietario = $ln['id_proprietario'];
    $queryProp = "SELECT * FROM proprietario WHERE id_proprietario = '$id_proprietario'";
    $queryProp = mysql_query($queryProp);
    $lnProp = mysql_fetch_array($queryProp);
    $nome = $lnProp['nome'];
    $cpf = $lnProp['CPF'];
}

$tamanho = strlen($cpf);
if ($tamanho > 11) {
    $cpf = mask($cpf, '##.###.###/####-##');
} else {
    $cpf = mask($cpf, '###.###.###-##');
}
$listalocacao = false;

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
        <h2>Alteração de unidades</h2>

        <hr>
        <form method="post" action="funcoes/alterar_unidade.php" enctype="multipart/form-data">

            <table width="80%" border="0">
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
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $nome ?>" name="nome" size="60" disabled/></th>
                </tr>
                </tr>
                <tr>
                    <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Etapa:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <input type="text"  value="<?= $ln['etapa'] ?>" name="etaoa" size="15" maxlength="15"  disabled />
                        &nbsp;<font size="2"; color="#000000">Unidade:
                        <input type="text" value="<?= $ln['numero_etapa'] ?>" name="nr_etapa" size="3" maxlength="3" disabled />
                    </th>        
                </tr>
                <tr>
                    <td width="15%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tipo de unidade:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <select id="tipo_unidade" name="tipo_unidade" ><font size="20"; color="#000000">
                            <option  value="<?= $ln['tipo_unidade'] ?>" selected="selected"><?= $ln['tipo_unidade'] ?></option>
                            <option value="Residência">Residência</option>
                            <option value="Locação Regular (+90dias)">Locação Regular (+90dias)</option>
                            <option value="Locação Temporária">Locação Temporária</option>
                        </select>
                    </th>        
                </tr>
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Número de quartos:</th>
                    <td ><input type="text" value="<?= $ln['qtde_quartos'] ?>" name="qtde_quarto" size="5" maxlength="2"   />
                        <font size="2"; ><b>Capacidade da unidade:</b> <input type="text" value="<?= $ln['capacidade'] ?>" maxlength="2" name="capacidade" size="5"   />
                    </td>
                </tr> 
                <tr>
                <p></p>
                <p></p>
                <td align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Documento de Titularidade:</b> </td>
                <td align="left"><input type="file" name="comprovante"  /> </td>
                <input type="hidden" name="id_unidade" value="<?= $id_unidade ?>" />
                <input type="hidden" name="cpf" value="<?= $cpf ?>" />
                <input type="hidden" name="tela" value="<?= $tela ?>" />


                </tr>
            </table>
            <br>
            <p></p>
            <center>
                <input type="submit" name="botao" value="Salvar Alterações" />
            </center>   
        </form>
        <br />
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>