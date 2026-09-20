<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<link rel="stylesheet" href="css/tableProprietario.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
session_name('SESSAO_PHP');
include "topo.php";
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/verifica_acessoAdm.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
$totalHospedes = 0;
?>

<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/componentes.js"></script>

<!-- Incluir jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Incluir jQuery UI -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<!-- Incluir o CSS do jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


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


<div id="conteudo">
    <div id="cont">
        <body>
            <h2>Relatório Gerencial - Proprietários</h2> <br>
            <hr />
            <form method="post" action="consulta_proprietarios.php">
                <br>
                <table width="70%" border="0">
                    <tr>
                        <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                        <td>  <font size="2"; color="#000000"><input type="text" name="cpf" 
                                                                     id="txtCPF" 
                                                                     size="20" 
                                                                     class="input_forms"
                                                                     accept=""/><br></td> 
                        </th>
                    </tr>
                    <tr>
                        <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</b> </td>
                        <td>  <font size="2"; color="#000000"><input type="text" name="proprietario" 
                                                                     id="txtNome" 
                                                                     size="60" 
                                                                     class="input_forms"
                                                                     accept=""/><br></td> 
                    </tr>
                </table>
                <br>
                <center> <input type="submit" value="Pesquisar" name="filtro" value="sim"/></center>
                <br />
            </form>
            <hr/>          
            <br>
            <div class="estiloTabelas table-responsive">
                 <h3>Relação de Proprietários Cadastrados</center></h3>
                <table width="100%" border="2">
                <tr>
                    <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> CPF/CNPJ</b></td>
                    <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Proprietário</b></td>
                    <td width="20%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Endereço</b></td>
                    <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Cidade</b></td>
                    <td width="5%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Estado</b></td>
                    <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> CEP</b></td>
                    <td width="15%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> E-mail</b></td>
                    <td width="8%" align="center" bgcolor="#191970"><font size="2"; color="#F5FFFA"><b> Telefone</b></td>
                </tr>
                <?php
                $sql = "SELECT *FROM proprietario order by nome ";
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

                if ($_POST['cpf']) {
                    $cpf = $_POST['cpf'];
                    $cpf = str_replace(".", "", $cpf);
                    $cpf = str_replace("-", "", $cpf);
                    $cpf = str_replace("/", "", $cpf);
//                    $where[] = " CPF = '{$cpf}'";
                    $where[] = " CPF LIKE  '%" . $cpf . "%'";
                }

                if ($_POST['proprietario']) {
                    $where[] = " nome LIKE  '%" . $_POST['proprietario'] . "%'";
                }

                $sql = "SELECT *FROM proprietario order by nome  ";

                if (sizeof($where)) {
                    $sql = "SELECT * FROM proprietario  ";
                    $sql .= ' WHERE ' . implode(' AND ', $where);
                    $sql = $sql . " ORDER BY nome";
                }

                $filtro = mysql_query($sql);
                $num_rows = mysql_num_rows($filtro);

                if ($num_rows == 0) {
                    echo "<meta http-equiv='refresh' content='0; URL=consulta_proprietarios.php'>
                <script type=\"text/javascript\">
                alert(\"Não existem documentos cadastrados para a consulta realizada!  \");
                </script>
                ";
                    return die;
                }
//==========================================                    
                while ($ln = mysql_fetch_array($filtro)) {
                    if ($ln['CPF'] !== '03699672000125') {
                        $tamanho = strlen($ln['CPF']);
                        if ($tamanho > 11) {
                            $cpf = mask($ln['CPF'], '##.###.###/####-##');
                        } else {
                            $cpf = mask($ln['CPF'], '###.###.###-##');
                        }
                        $ln['cep'] = mask($ln['cep'], '##.###-###');
                        $ln['telefone'] = mask($ln['telefone'], '(##)#####-#####');
                        ?>
                        <tr>
                            <td style="padding:5px;" align="center"><font size="2"; color="#000000"><?= $cpf ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= strtoupper($ln['nome']) ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln['endereco'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['cidade'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['estado'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['cep'] ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln['email'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['telefone'] ?></td>
                            
                   
                        </tr>
                        <?php
                    }
                }// Fecha Loop 
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