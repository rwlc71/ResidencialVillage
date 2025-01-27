<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
session_name('SESSAO_PHP');
include "topo.php";
include "conexao.php";
include "verifica_autenticacao.php";
//echo ($_COOKIE['relatorio']);
//exit();
$sql = " SELECT * FROM tab_docfiscal WHERE id = '" . $_GET['id'] . "'";
//echo ($sql);

$sql = mysql_query($sql);
$ln = mysql_fetch_array($sql);
?>
<div id="conteudo">

    <div id="cont">
        <body> 

            <?php
//            print ($_POST['editar']);
            ?>
            <hr />

            <?php
//            $sql2 = mysql_query(" SELECT * FROM tab_historico_documento WHERE id_doc = '" . $_GET['id'] . "' ORDER BY id DESC");
//            $ln2 = mysql_fetch_array($sql2);
//            $email = $ln['email'];
//            $sql1 = mysql_query("SELECT * FROM cadastro_vistabela WHERE email = '$email'");
//            $ln1 = mysql_fetch_array($sql1);
            ?>

            <form method="post" action="funcoes/grava_acao.php"> 


                <table  border="0">
                    <tr>
                        <td  align="left"><font size="4"; color="#000000"><b>CPF do locador</b>:</td>
                        <td width="15%" align="left"><?= $ln['cpfcnpj_fornecedor'] ?></td>

                        <td align="left"><font size="4"; color="#000000"><b>Nome do Locador</b>:</td>
                        <td width="40%" align="left"><?= $ln['nome_fornecedor'] ?></td>
                    </tr>
                    <tr>
                        <td  align="left" ><b>Ano/Mês do documento: </b></td>
                        <td  align="left" ><?= $ln['ano_doc'] ?>/<?= $ln['mes_doc'] ?></td>



                        <td></td>
                    </tr></table>
                <hr>
                <table border="0">
                    <td align="left"><b>Data de emissão</b>:</td>
                    <td width="78%" align="left"><?= $ln['dt_emissao_doc'] ?></td>
                    </tr>
                    <td></td><td></td><td></td><td></td>
                    <tr>
                        <td align="left"><strong>Valor</strong>:</td>
                        <?php
                        $mostrar = str_replace(".", ",", $ln['valor_doc']);
                        ?>
                        <td align="left"> R$ <?= $mostrar ?>

                            <?php
                            if ($ln['debito'] != "sim") {
                                $debito = "";
                            } else {
                                $debito = " - Documento cadastrado para cobrança em débito em conta";
                            }
                            // echo ($debito);
                            // echo ($ln['debito']);
                            // exit();
                            ?>
                            <?= $debito ?> 
                        </td>
                    </tr>    
                    <td></td><td></td><td></td><td></td>

                    <tr>
                        <td align="left"><strong>Finalidade</strong>:</td>
                        <td width="85%" align="left"><?= $ln['finalidade_doc'] ?></td>
                    </tr>  

                    <tr>
                        <td align="left"><strong>Imóvel</strong>:</td>
                        <td width="85%" align="left"><?= $ln['imovel'] ?></td>
                    </tr>  
                  
                        <td><b>Comprovante</b></td>
                        <td >
                            <a href="http://www.1portodos.com.br/controleLocacao/documentosfiscais/<?= $ln['comprovante'] ?>"  target="_blank" title="Ver comprovante">
                                <img src="images/ver.jpg"  height=25 width=25 align='middle' border="0">
                            </a>
                        </td>
                    </table>
                </table>  <font size="3"; color="#000000">



            </form>
        </body>
        </html>
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>