<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/componentes.js"></script>


<!--Scripts utilizado no autocomplete-->
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.js"></script>
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
        <h2>Vincular unidades</h2>
        <br>

        <hr>
        <form method="post" action="funcoes/salvar_unidade.php" enctype="multipart/form-data">

            <table width="80%" border="0">
                <br>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                    <th width="25%" align="left" scope="col">
                        <input name="cpf" type="text" class="imput" id="cpf" size="14" maxlength="14" value=""
                               placeholder="Somente números" onkeypress='mascaraMutuarios(this, cpfCnpj)' onblur='validaCPF(this)' disabled />
                    </th>
                </tr>
                <tr>
                    <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                    <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                 id="txtNome" 
                                                                 size="60" 
                                                                 class="input_forms"
                                                                 onblur="PreencheCPF_CNPJ()"
                                                                 onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                </tr>
                <tr>
                    <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;Etapa:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <select id="Etapa" name="Etapa" ><font size="20"; color="#000000">
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
                        <select id="tipo_unidade" name="tipo_unidade" ><font size="20"; color="#000000">
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
                        <input type="hidden" name="cpf" id="idcpf" value="" />
                        <input type="hidden" name="tela" value="adm" />

                    </td>
                </tr>  
                <tr>

                    <td align="left" width="15% bgcolor="#ffffff"><font size="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;Documento de Titularidade:</b> </td>
                    <td align="left" ><input type="file" name="comprovante"  /> </td>
                </tr>
            </table>
            <br>
            <p></p>
            <center>
                <input type="submit" name="botao" value="Vincular unidade" />
            </center>   
            <br>
            <p><p>
                <b>&nbsp;&nbsp;&nbsp;&nbsp;Atenção:</b> <i>É obrigatório a apresentação e inserção do documento de titularidade.</i>

        </form>
        <br />
        </table>
    </div>
</div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>