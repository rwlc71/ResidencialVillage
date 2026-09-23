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

//    $(document).ready(function () {
//        $("#txtPet").autocomplete("completar_pet.php", {
//            width: 310,
//            selectFirst: false
//        });
//    });
//    $(document).ready(function () {
//        $("#txtCPF").autocomplete("completar_cpf.php", {
//            width: 310,
//            selectFirst: false
//        });
//    });

</script>
<?php
session_name('SESSAO_PHP');
include "topo.php";
include "conexao.php";
include "valida/verifica_autenticacao.php";
//include "funcoes/calcula_dia.php";
include "valida/verifica_acessoAdm.php";
$totalHospedes = 0;
?>


<div id="conteudo">
    <div id="cont">
        <body>
            <h2>Relatório Gerencial - Animais</h2> <br>
            <hr />
            <form method="post" action="consulta_pet.php">
                <br>
                <table width="60%" border="0">
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                     id="txtNome" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>

                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome do animal:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="nome_pet" 
                                                                     id="txtPet" 
                                                                     size="20" 
                                                                     class="input_forms"
                                                                     onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/><br></td> 
                    </tr>

                </table>
                <center> <input type="submit" value="Pesquisar" name="filtro" value="sim"/></center>
                <br />
            </form>
            <hr/>          
            <br>
            <div class="estiloTabelas table-responsive">
                 <h3>Relação de Animais Cadastrados</center></h3>

                <table width="50%" border="2">
                    <tr>
                        <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Proprietário</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Nome do animal</b></td>
                        <td width="10%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Tipo do animal</b></td>
                        <td width="1%" align="center" bgcolor="#191970" colspan="1"><font size="2"; color="#F5FFFA"><b> Foto</b></td>

                    </tr>
                    <?php
                    $sql = "SELECT pet.*, prop.id_proprietario, prop.nome FROM pets pet "
                            . "JOIN proprietario prop ON pet.id_proprietario = prop.id_proprietario ORDER BY prop.nome";
                    //                echo ('$sql1 --> ' . $sql);
                    $sql = mysql_query($sql);
                    $num_rows = mysql_num_rows($sql);

                    $where = Array();

                    if ($_POST['proprietario']) {
//                    $where[] = " prop.nome = '{$_POST['proprietario']}'";
                        $where[] = " prop.nome  LIKE  '%" . $_POST['proprietario'] . "%'";
                    }

                    if ($_POST['nome_pet']) {
//                    $where[] = " pet.nome_pet = '{$_POST['nome_pet']}'";
                        $where[] = " pet.nome_pet  LIKE  '%" . $_POST['nome_pet'] . "%'";
                    }

                    $sql = "SELECT pet.*, prop.id_proprietario, prop.nome FROM pets pet "
                            . "JOIN proprietario prop ON pet.id_proprietario = prop.id_proprietario ORDER BY prop.nome";

                    if (sizeof($where)) {
                        $sql = "SELECT pet.*, prop.id_proprietario, prop.nome FROM pets pet "
                                . "JOIN proprietario prop ON pet.id_proprietario = prop.id_proprietario";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . " ORDER BY prop.nome";
                    }
//                echo ('$sql2 --> ' . $sql);

                    $filtro = mysql_query($sql);
                    $num_rows = mysql_num_rows($filtro);

//                echo ("nr2: " . $num_rows);
//                exit();
                    if ($num_rows == 0) {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            echo "<script type=\"text/javascript\">alert(\"Não existem registros cadastrados para a consulta realizada!\");</script>";
                        }
                        echo '<tr><td colspan="12" align="center"><font size="2" color="#000000">Não existem registros cadastrados para a consulta realizada!</font></td></tr>';
                    }
//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {
                        $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                '<img src="images/quebra.png"  title="Foto indisponível" height=20 width=20 align="middle" border="0">' .
                                '</td>';

                        if ($ln['foto_pet']) {
                             $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
                                    '<a href="#" onclick="openModal(\'documentostitularidade/' . $ln['foto_pet'] . '\')" title="Visualizar foto">' .
                                    '<img src="images/visualizar1.jpg" height="20" width="20" align="middle" border="0"> </a>' .
                                    '</td>';
//                            $visualizarcomprovante = '<td align="center" valign="middle" bgcolor="#FFFFFA">' .
//                                    '<a href="documentostitularidade/' . $ln['foto_pet'] . '"  target="_blank" title="Abrir Foto">' .
//                                    '<img src="images/visualizar.svg"  height=20 width=20 align="middle" border="0">' .
//                                    '</a>' .
//                                    '</td>';
                        }
                        ?>
                        <tr>

                            <td style="padding:5px;" align="left"><font size="2"; color="#000000"><?= $ln['nome'] ?></td>
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
        <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="imgModal" alt="Foto do dependente">
    </div>
   <?php
    include "rodape.php";
    ?>