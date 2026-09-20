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
        $("#txtDependente").autocomplete("completar_dependente.php", {
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
$totalHospedes = 0;
?>


<div id="conteudo">
    <div id="cont">
        <body>
            <h2>Relatório Gerencial - Dependentes / Associados</h2> <br>
            <hr />
            <form method="post" action="consulta_dependentes.php">
                <br>
                <table width="65%" border="0">
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                     id="txtNome" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>

                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome do dependente:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="nome_dependente" 
                                                                     id="txtDependente" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>

                </table>
                <br> 
                <center> <input type="submit" value="Pesquisar" name="filtro" value="sim"/></center>
                <br />
            </form>
            <hr/>          
            <br>
            <div class="estiloTabelas table-responsive">
                <h3>Relação de Dependentes / Associados Cadastrados</center></h3>
                <table width="80%" border="2">
                    <tr>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Proprietário</b></td>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nome do dependente / Associado</b></td>
                        <td width="13%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Identificação</b></td>
                        <td width="16%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Grau de parentesco / vínculo:</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="1"><font size="2"; color="#F5FFFA"><b> Foto</b></td>


                    </tr>
                    <?php
                    $sql = "SELECT dep.*, prop.id_proprietario, prop.nome FROM dependente dep "
                            . "JOIN proprietario prop ON dep.id_proprietario = prop.id_proprietario ORDER BY prop.nome";
                    //                echo ('$sql1 --> ' . $sql);
                    $sql = mysql_query($sql);
                    $num_rows = mysql_num_rows($sql);

                    if ($num_rows == 0) {
                        echo "<meta http-equiv='refresh' content='0; URL=home.php'>
                <script type=\"text/javascript\">
                alert(\"Não existem dados cadastrados!  \");
                </script>
                ";
                        return die;
                    }
//==========================================
                    $where = Array();

                    if ($_POST['proprietario']) {
                        $where[] = " prop.nome LIKE  '%" . $_POST['proprietario'] . "%'";
//                    $where[] = " prop.nome = '{$_POST['proprietario']}'";
                    }

                    if ($_POST['nome_dependente']) {
                        $where[] = " dep.nome_dependente LIKE  '%" . $_POST['nome_dependente'] . "%'";
//                    $where[] = " dep.nome_dependente = '{$_POST['nome_dependente']}'";
                    }

                    $sql = "SELECT dep.*, prop.id_proprietario, prop.nome FROM dependente dep "
                            . "JOIN proprietario prop ON dep.id_proprietario = prop.id_proprietario ORDER BY prop.nome";

                    if (sizeof($where)) {
                        $sql = "SELECT dep.*, prop.id_proprietario, prop.nome FROM dependente dep "
                                . "JOIN proprietario prop ON dep.id_proprietario = prop.id_proprietario";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . " ORDER BY prop.nome";
                    }
//                echo ('$sql2 --> ' . $sql);

                    $filtro = mysql_query($sql);
                    $num_rows = mysql_num_rows($filtro);

//                echo ("nr2: " . $num_rows);
//                exit();

                    if ($num_rows == 0) {
                        echo "<meta http-equiv='refresh' content='0; URL=consulta_dependentes.php'>
                <script type=\"text/javascript\">
                alert(\"Não existem registros cadastrados para a consulta realizada!  \");
                </script>
                ";
                        return die;
                    }
//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<img src="images/quebra.png"  title="Foto indisponível" height=20 width=20 align="middle" border="0">' .
                                '</td>';
                        if ($ln['foto_dependente']) {
//                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
//                                    '<a href="documentostitularidade/' . $ln['foto_dependente'] . '"  target="_blank" title="Abrir Foto">' .
//                                    '<img src="images/visualizar.svg"  height=20 width=20 align="middle" border="0">' .
//                                    '</a>' .
//                                    '</td>';
                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="#" onclick="openModal(\'documentostitularidade/' . $ln['foto_dependente'] . '\')" title="Visualizar foto">' .
                                    '<img src="images/visualizar1.jpg" height="20" width="20" align="middle" border="0"> </a>' .
                                    '</td>';
                        }
                        ?>
                        <tr>

                            <td style="padding:5px;" align="left"><font size="2"; color="#000000">&nbsp;<?= strtoupper($ln['nome']) ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp;<?= strtoupper($ln['nome_dependente']) ?></td>
                            <td align="left"><font size="2"; color="#000000">&nbsp;<?= strtoupper($ln['doc_indentificacao_dependente']) ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['parentesco'] ?></td>
                            <?= $visualizarcomprovante ?>
                        </tr>
                        <?php
                    } // Fecha Loop 
                    ?>
                </table>
                <br>
                </body>
                </html>

            </div><!-- fim div cont -->

    </div> <!-- fim div conteudo -->
    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="imgModal" alt="Foto do dependente">
    </div>
    <?php
    include "rodape.php";
    ?>