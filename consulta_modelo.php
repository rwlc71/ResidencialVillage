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
include "verifica_autenticacao.php";
//include "funcoes/calcula_dia.php";
$totalHospedes = 0;
?>


<div id="conteudo">
    <div id="cont">
        <body>
            <h2><center>Relatório Gerencial - Proprietários</center></h2> <br>
            <hr />
            <form method="post" action="consulta_proprietario.php">
                <br>
                <table width="70%" border="0">
                    <tr>
                        <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                        <th width="25%" align="left" scope="col">
                            <input name="cpf" id="cpf" type="text" 
                                   id="txtNome" 
                                   size="15" 
                                   class="input_forms"
                                   onkeypress="aplicarMascaraCpfCnpj(cpf)" value="" size="18" maxlength="18"  >

                        </th>
                    </tr>
<!--                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                     id="txtNome" 
                                                                     size="15" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>-->
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
                    <tr>
                        <td align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tipo de unidade:</b> </td>
                        <th align="left" scope="col">
                            <select id="tipo_unidade" name="tipo_unidade" ><font size="20"; color="#000000">
                                <option  value="" selected="selected"></option>
                                <option value="Residência">Residência</option>
                                <option value="Locação Regular (+90dias)">Locação Regular (+90dias)</option>
                                <option value="Locação Temporária">Locação Temporária</option>
                            </select>
                        </th>        
                    </tr>
                </table>
                <br>
                <center> <input type="submit" value="Pesquisar" name="filtro" value="sim"/></center>
                <br />
            </form>
            <hr/>          
            <br>
            <div class="estiloTabelas">
                <table width="70%" border="2">
                    <tr>
                        <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> CPF/CNPJ</b></td>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Proprietário</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Etapa</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Unidade</b></td>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Dependente(s)</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo de Unidade</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="1"><font size="2"; color="#F5FFFA"><b> Ação</b></td>
                    </tr>
                    <?php
                    $sql = "SELECT dep.*, uni.*, prop.id_proprietario, prop.nome FROM dependente dep "
                            . "JOIN proprietario prop ON dep.id_proprietario = prop.id_proprietario"
                            . " JOIN unidade uni on uni.id_proprietario = prop.id_proprietario order by prop.nome ";
//                                echo ('$sql1 --> ' . $sql);
                    $sql = mysql_query($sql);
                    $num_rows = mysql_num_rows($sql);

//                if ($num_rows == 0) {
//                    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
//                <script type=\"text/javascript\">
//                alert(\"Não existem dados cadastrados!  \");
//                </script>
//                ";
//                    return die;
//                }
//==========================================
                    $where = Array();

                    if ($_POST['cpf']) {
                        $cpf = trim($_POST['cpf']);
                        $cpf = str_replace(".", "", $cpf);
                        $cpf = str_replace("-", "", $cpf);
                        $cpf = str_replace("/", "", $cpf);
                        $where[] = " prop.CPF = '{$cpf}'";
                    }

                    if ($_POST['proprietario']) {
                        $where[] = " prop.nome = '{$_POST['proprietario']}'";
                    }

                    if ($_POST['Etapa']) {
                        $where[] = " uni.etapa = '{$_POST['Etapa']}'";
                    }
                    if ($_POST['nr_etapa']) {
                        $where[] = " uni.numero_etapa = '{$_POST['nr_etapa']}'";
                    }
                    if ($_POST['tipo_unidade']) {
                        $where[] = " uni.tipo_unidade = '{$_POST['v']}'";
                    }

                    $sql = "SELECT dep.*, uni.*, prop.id_proprietario, prop.nome FROM dependente dep "
                            . "JOIN proprietario prop ON dep.id_proprietario = prop.id_proprietario"
                            . " JOIN unidade uni on uni.id_proprietario = prop.id_proprietario order by prop.nome ";

                    if (sizeof($where)) {
                        $sql = "SELECT dep.*, uni.*, prop.id_proprietario, prop.nome FROM dependente dep "
                                . "JOIN proprietario prop ON dep.id_proprietario = prop.id_proprietario"
                                . "JOIN unidade uni on uni.id_proprietario = prop.id_proprietario ";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . " ORDER BY prop.nome";
                    }
//                echo ('$sql2 --> ' . $sql);

                    $filtro = mysql_query($sql);
                    $num_rows = mysql_num_rows($filtro);

//                echo ("nr2: " . $num_rows);
//                exit();
//                if ($num_rows == 0) {
//                    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
//                <script type=\"text/javascript\">
//                alert(\"Não existem documentos cadastrados para a consulta realizada!  \");
//                </script>
//                ";
//                    return die;
//                }
//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {
                        echo($sql);
                        echo(var_dump($ln));
                        exit();
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<img src="images/quebra.png"  title="Foto indisponível" height=20 width=20 align="middle" border="0">' .
                                '</td>';

                        if ($ln['foto_pet']) {
                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="documentostitularidade/' . $ln['foto_pet'] . '"  target="_blank" title="Abrir Foto">' .
                                    '<img src="images/visualizar.svg"  height=20 width=20 align="middle" border="0">' .
                                    '</a>' .
                                    '</td>';
                        }
                        ?>
                        <tr>

                            <td align="center"><font size="2"; color="#000000"><?= $ln['nome'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['nome_pet'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['tipo_pet'] ?></td>
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