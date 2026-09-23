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
include "topo.php";
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/verifica_acessoAdm.php";
//include "funcoes/calcula_dia.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
$totalHospedes = 0;
?>


<div id="conteudo">
    <div id="cont">
        <body>
            <h2>Registro de ocorrências</h2> <br>
            <hr />
            <form method="post" action="cadastrar_ocorrencia_unidade.php">
                <br>
                <table width="80%" border="0">
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                     id="txtNome" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>

                    <tr>
                        <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Etapa:</b> </td>
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
<!--                    <tr>
                        <td align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tipo de unidade:</b> </td>
                        <th align="left" scope="col">
                            <select id="tipo_unidade" name="tipo_unidade" ><font size="20"; color="#000000">
                                <option  value="" selected="selected"></option>
                                <option value="Residência">Residência</option>
                                <option value="Locação Regular (+90dias)">Locação Regular (+90dias)</option>
                                <option value="Locação Temporária">Locação Temporária</option>
                            </select>
                        </th>        
                    </tr>-->
                </table>
                <center><input type="submit" name="botao" value="Cadastrar Ocorrência"></center>
                <br />
            </form>
            <hr/>          
            <br>
            <div class="estiloTabelas table-responsive">
                <h3>Relação de Ocorrencias cadastradas</center></h3>
                <table width="85%" border="2">
                    <tr>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> CPF</b></td>
                        <td width="25%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Proprietário</b></td>
                        <td width="12%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Unidade</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nº Quartos</b></td>
                        <td width="17%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Capacidade</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="1"><font size="2"; color="#F5FFFA"><b>Documento</b></td>

                    </tr>
                    <?php
                    $sql = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
                            . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
                            . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario";
//                                echo ('$sql1 --> ' . $sql);
//                                exit();

                    $sql = mysql_query($sql);
                    $num_rows = mysql_num_rows($sql);

                    $where = Array();

                    if ($_POST['proprietario']) {
//                    $where[] = " prop.nome = '{$_POST['proprietario']}'";
                        $where[] = " prop.nome  LIKE  '%" . $_POST['proprietario'] . "%'";
                    }

                    if ($_POST['Etapa']) {
                        $where[] = " uni.etapa  LIKE  '%" . $_POST['Etapa'] . "%'";
//                    $where[] = " un.etapa = '{$_POST['Etapa']}'";
                    }

                    if ($_POST['nr_etapa']) {
//                    $where[] = " un.numero_etapa = '{$_POST['nr_etapa']}'";
                        $where[] = " uni.numero_etapa  LIKE  '%" . $_POST['nr_etapa'] . "%'";
                    }


                    $sql = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
                            . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
                            . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario";

                    if (sizeof($where)) {
                          $sql = "SELECT reg.*,  uni.*, prop.id_proprietario, prop.nome, prop.email, prop.CPF FROM registro_ocorrencia reg "
                            . " JOIN unidade uni on reg.id_unidade = uni.id_unidade  "
                            . " JOIN proprietario prop ON uni.id_proprietario = prop.id_proprietario";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . " ORDER BY prop.nome";
                    }

                    $filtro = mysql_query($sql);
                    $num_rows = mysql_num_rows($filtro);

                    if ($num_rows == 0) {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            echo "<script type=\"text/javascript\">alert(\"Não existem registros cadastrados para a consulta realizada!\");</script>";
                        }
                        echo '<tr><td colspan="12" align="center"><font size="2" color="#000000">Não existem registros cadastrados para a consulta realizada!</font></td></tr>';
                    }
//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {
                        $tamanho = strlen($ln['CPF']);
                        if ($tamanho > 11) {
                            $ln['CPF'] = mask($ln['CPF'], '##.###.###/####-##');
                        } else {
                            $ln['CPF'] = mask($ln['CPF'], '###.###.###-##');
                        }
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<img src="images/proibido-preto.svg"  title="Documento indisponível" height=20 width=20 align="middle" border="0">' .
                                '</td>';

                       
                       $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                ' <a href="visualizar_ocorrencias.php?dado=' . $ln['id_unidade'] . '" title="Visualizar ocorrências"> ' .
                                '<img src="images/documento.png"  height=20 width=20 align="middle" border="0">' .
                                '</a>' .
                                '</td>';
                        
                        ?>
                        <tr>

                            <td style="padding:5px;" align="left"><font size="2"; color="#000000"><?= $ln['CPF'] ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= strtoupper($ln['nome']) ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln['etapa'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['numero_etapa'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['qtde_quartos'] ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln['tipo_unidade'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['capacidade'] ?></td>


                            <?= $visualizarcomprovante ?>
                        </tr>
                        <?php
                    } // Fecha Loop 
                    ?>
                </table>
            </div>
            <br>
        </body>
        </html>

    </div><!-- fim div cont -->

</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>