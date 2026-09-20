<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
session_name('SESSAO_PHP');
include "topo.php";
include "conexao.php";
include "funcoes/calcula_dia.php";
include "verifica_autenticacao.php";

//echo ($_COOKIE['relatorio']);
//exit();
?>
<div id="conteudo">

    <div id="cont">

        <body>
            <h1>Controle Contábil</h1> <hr />
            <?php
//            echo $_POST['filtro'] ;
//              echo $_POST['ano'] ;
//                echo $_POST['mes'] ;
            //  exit();

            if ($_POST['filtro'] != "Aplicar") {
                ?>                
                <form method="post" action="relContabilidade.php">


                    <table border="0">
                        <td><center><font size="2"; color="#000000"><b>Selecione o ano e o mês para pesquisa:</b>
                            <select name="ano"><font size="2"; color="#000000">
                                <option size="04" value="<?= $anoatual ?>" selected="selected"><?= $anoatual ?></option>
                                <option size="04" value="2023">2023</option>
                                <option size="04" value="2024">2024</option>
                                <option size="04" value="2025">2025</option>
                                <option size="04" value="2026">2026</option>
                                <option size="04" value="2027">2027</option>
                                <option size="04" value="2028">2028</option>
                                <option size="04" value="2029">2029</option>
                                <option size="04" value="2030">2030</option>
                            </select></center>   </td>
                        <td><center><font size="2"; color="#000000">
                            <select name="mes"><font size="2"; color="#000000">
                                <option size="10" value="<?= $mesatual ?>" selected="selected"><?= $mesatual ?></option>
                                <option size="10" value="Janeiro">Janeiro</option>
                                <option size="10" value="Fevereiro">Fevereiro</option>
                                <option size="10" value="Março">Março</option>
                                <option size="10" value="Abril">Abril</option>
                                <option size="10" value="Maio">Maio</option>
                                <option size="10" value="Junho">Junho</option>
                                <option size="10" value="Julho">Julho</option>
                                <option size="10" value="Agosto">Agosto</option>
                                <option size="10" value="Setembro">Setembro</option>
                                <option size="10" value="Outubro">Outubro</option>
                                <option size="10" value="Novembro">Novembro</option>
                                <option size="10" value="Dezembro">Dezembro</option>
                            </select>  </center></td>

                        <td><center><b><font size="2"; color="#000000"></b><b>Pesquisar</b>
                            <input type="submit" value="Aplicar" name="filtro" />
                        </center></td>      
                        </tr>
                        </center></table>
                    <center>
    <!--                    <input type="submit" value="Aplicar filtro" />
                        <input type="hidden" name="filtro" value="sim" /> </center> -->
                </form>
                <hr />

                <?php
            } else {
                if ($_POST['mes'] == "" || $_POST['ano'] == "") {
                    echo "<meta http-equiv='refresh' content='0; URL=relContabilidade.php'>
                     <script type=\"text/javascript\">
                     alert(\"Ano e mês de referência não selecionados. Por favor, selecione-os!\");
                    </script>  ";
                    return die();
                }
                ?>

                <table  border="2"><br>
                    <font size="3"; color="#000000"><b>Relatório Contábil - Referência: <?= $_POST['mes'] ?>/<?= $_POST['ano'] ?></b><p></p>
                    <tr>

                      <td width="5%" align="center" bgcolor="#191970"><font size="3"; color="#F5FFFA"><b> Data </b></td>
                        <td width="8%" align="center" bgcolor="#191970"><font size="3"; color="#F5FFFA"><b> CPF/CNPJ</b></td>
                        <td width="16%" align="center" bgcolor="#191970"><font size="3"; color="#F5FFFA"><b> Recebedor</b></td>
                        <td width="9%" align="center" bgcolor="#191970"><font size="3"; color="#F5FFFA"><b> Valor (R$)</b></td>
                        <td width="25%"align="center" bgcolor="#191970"><font size="3"; color="#F5FFFA"><b> Finalidade</b></td>
                        <td width="8%" align="center" bgcolor="#191970"><font size="3"; color="#F5FFFA"><b> Transação</b></td>
                        <td width="1%" align="center" bgcolor="#191970"><font size="3"; color="#F5FFFA"><b> </b></td>

                    </tr>
                    <?php
                    include "conexao.php";
//                    $sql = ("SELECT * FROM tab_docfiscal where ano_doc = '" . $_POST['ano'] . "' and mes_doc = '" . $_POST['mes'] . "' order by debito ASC");
//                    echo ($sql);
//                    exit();
                    $sql = mysql_query("SELECT * FROM tab_docfiscal where ano_doc = '" . $_POST['ano'] . "' and mes_doc = '" . $_POST['mes'] . "' ORDER BY debito ASC, dt_emissao_doc ASC");
                    $num_rows = mysql_num_rows($sql);

                    if ($num_rows == 0) {
                        echo "<meta http-equiv='refresh' content='0; URL=relContabilidade.php'>
                <script type=\"text/javascript\">
                alert(\"Não existem documentos cadastrados!  \");
                </script>
                ";
                        return die;
                    }
//                echo ($_POST['filtro']);
//                echo ($_POST['situacao'] );
//                exit();
//                if ($_POST['filtro'] == 'Aplicar') {  /// montar as possibilidades atraves do array abaixo!!!
//==========================================
                    $where = Array();

                    $a = $_POST['ano'];
                    $b = $_POST['mes'];
                    $c = $_POST['cpfcnpj'];
                    $d = $_POST['nr_documento'];
                    $e = $_POST['dt_emissao'];
                    $f = $_POST['situacao'];
                    $order = " ORDER BY dt_emissao_doc ASC, debito ASC";

                    if ($a) {
                        $where[] = " `ano_doc` = '{$a}'";
                    }
                    if ($b) {
                        $where[] = " `mes_doc` = '{$b}'";
                    }
                    if ($e) {
                        $where[] = " `dt_emissao_doc` = '{$e}'";
                    }
                    if ($c) {
                        $where[] = " `cpfcnpj_fornecedor` = '{$c}'";
                    }
                    if ($d) {
                        $where[] = " `nr_documento` = '{$d}'";
                    }
                    if ($f) {
                        $where[] = " `situacao` = '{$f}'";
                    }
                    $sql = "SELECT * FROM tab_docfiscal ORDER BY debito ASC, dt_emissao_doc ASC";
                    if (sizeof($where)) {
                        $sql = "SELECT * FROM tab_docfiscal";
                        $sql .= ' WHERE ' . implode(' AND ', $where);
                        $sql = $sql . $order;
                    }
                    $filtro = mysql_query($sql);
//==========================================                    
                    while ($ln = mysql_fetch_array($filtro)) {

                        ?>
                        <tr>
                            <td align="center"><font size="2"; color="#000000"><?= $ln['dt_emissao_doc'] ?></td>
                            <td align="left"><font size="2"; color="#000000"><?= $ln['cpfcnpj_fornecedor'] ?></td>
                            <td><font size="2"; color="#000000"><?= strtoupper($ln['nome_fornecedor']) ?></td>
                            <?php
                        if ($ln['valor_doc'] == 0) {
                            $mostrar = "--";
                        } else {
                            $mostrar = str_replace(".", ",", $ln['valor_doc']);
                            $mostrar = 'R$ '.$mostrar;
                        }

                            if ($ln['debito'] !== 'Transferencia') {
                                if ($ln['debito'] !== 'Protocolo') {
                                    $total = ($ln['valor_doc'] + $total);
                                }
                            }

                            $debito = 'Transferencia de fundo de caixa';
                            if ($ln['debito'] == 'sim') {
                                $debito = 'Debito em conta';
                            } elseif ($ln['debito'] == 'nao') {
                                $debito = 'Fundo de Caixa';
                            } elseif ($ln['debito'] == 'Protocolo') {
                                $debito = 'Registro de protocolo';
                            }
                            ?>
                            <td align="center"><font size="2"; color="#000000"> <?= $mostrar ?></td>
                            <td><font size="2"; color="#000000"><?= $ln['finalidade_doc'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= strtoupper($debito) ?></td>

                            <td align="center" valign="middle" bgcolor="#FFFFFA">
                                <a href="https://1portodos.com.br/financeiroVillage/documentosfiscais/<?= $ln['comprovante'] ?>"  target="_blank" title="Ver comprovante">
                                    <img src="images/visualizar.jpg"  height=25 width=25 align='middle' border="0">
                                </a>
                            </td>
                        <form>
                            <input type="hidden" name="editar" value="1" />
                        </form>
                        </tr>
                        <?php
                    } // Fecha Loop 
                    ?>
                    <b>Total da Referência: <?= $_POST['mes'] ?>/<?= $_POST['ano'] ?>: R$ <?= number_format($total, 2, ",", ".") ?> <br> <p></p>
                        Total de documentos: <?= $num_rows ?><p></p>    
                    </b>
                </table><br>
                <hr>
            </body>
            </html>
            <?php
        }
        ?>

    </div><!-- fim div cont -->

</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>