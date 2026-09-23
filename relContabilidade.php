<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
session_name('SESSAO_PHP');
include "topo.php";
include "funcoes/calcula_dia.php";
include "conexao.php";
include "valida/verifica_autenticacao.php";

if (!isset($pdo) || !($pdo instanceof PDO)) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ));
    } catch (PDOException $e) {
        echo "<script type=\"text/javascript\">alert(\"Falha na comunicação com o banco de dados!\");</script>";
        include "rodape.php";
        return;
    }
}

$iconeVisualizar = file_exists("images/visualizar.jpg") ? "images/visualizar.jpg" : "images/visualizar.svg";
?>

<style>
    .seta {
        margin-left: 5px;
        font-size: 12px;
        color: #ccc;
    }
    th.ativo-asc .seta::after,
    td.ativo-asc .seta::after {
        content: "▲";
        color: #F5FFFA;
    }
    th.ativo-desc .seta::after,
    td.ativo-desc .seta::after {
        content: "▼";
        color: #F5FFFA;
    }
    .cabecalho-ordenavel {
        cursor: pointer;
    }
    .barra-ferramentas {
        margin: 10px 0;
        text-align: center;
    }
    .barra-ferramentas input[type="text"] {
        margin: 5px;
        padding: 6px;
        width: 260px;
    }
    .barra-ferramentas button {
        margin: 5px;
        padding: 6px 12px;
        cursor: pointer;
    }
    .resumo-totais {
        margin-top: 15px;
        border-collapse: collapse;
        width: 60%;
        min-width: 420px;
    }
    .resumo-totais th {
        background: #191970;
        color: #F5FFFA;
        padding: 8px;
        text-align: center;
    }
    .resumo-totais td {
        padding: 7px;
        border: 1px solid #999;
    }
    .resumo-totais .valor {
        text-align: right;
        font-weight: bold;
    }
    .resumo-totais .total-geral {
        background: #f2f2f2;
        font-weight: bold;
    }
</style>

<div id="conteudo">
    <div id="cont">

        <body>
            <h1>Gestão contábil por referência (Ano/Mês)</h1>
            <hr />
            <?php
            if (!isset($_POST['filtro']) || $_POST['filtro'] != "Aplicar filtro") {
                ?>
                <form method="post" action="relContabilidade.php">
                    <table border="0">
                        <tr>
                            <th align="left">Ano referência:</th>
                            <th align="left">
                                <select name="ano">
                                    <option size="04" value="<?= $anoatual ?>" selected="selected"><?= $anoatual ?></option>
                                    <option size="04" value="2023">2023</option>
                                    <option size="04" value="2024">2024</option>
                                    <option size="04" value="2025">2025</option>
                                    <option size="04" value="2026">2026</option>
                                    <option size="04" value="2027">2027</option>
                                    <option size="04" value="2028">2028</option>
                                    <option size="04" value="2029">2029</option>
                                    <option size="04" value="2030">2030</option>
                                </select>
                            </th>

                            <th align="left">Mês referência:</th>
                            <th align="left">
                                <select name="mes">
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
                                </select>
                            </th>
                            <th>
                                <input type="submit" name="filtro" value="Aplicar filtro" />
                            </th>
                        </tr>
                    </table>
                </form>
                <hr />
                <?php
            } else {
                $mesPost = isset($_POST['mes']) ? trim($_POST['mes']) : '';
                $anoPost = isset($_POST['ano']) ? trim($_POST['ano']) : '';

                if ($mesPost == "" || $anoPost == "") {
                    echo "<meta http-equiv='refresh' content='0; URL=relContabilidade.php'>
                    <script type=\"text/javascript\">
                    alert(\"Ano e mês de referência não selecionados. Por favor, selecione-os!\");
                    </script>";
                    include "rodape.php";
                    return;
                }

                $mesesValidos = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
                $a = preg_replace('/[^0-9]/', '', $anoPost);
                $b = in_array($mesPost, $mesesValidos) ? $mesPost : '';

                if ($a == '' || strlen($a) != 4 || $b == '') {
                    echo "<meta http-equiv='refresh' content='0; URL=relContabilidade.php'>
                    <script type=\"text/javascript\">
                    alert(\"Ano ou mês inválido. Por favor, selecione uma referência válida!\");
                    </script>";
                    include "rodape.php";
                    return;
                }

                $total = 0;
                $total2 = 0;
                $totalContabil = 0;
                $totalContabildespesa = 0;
                $totalGeralDocumentos = 0;
                $totaisPorCategoria = array();
                $num_rows = 0;
                $cheques = array();
                $documentos = array();

                try {
                    $sqlCheque = "SELECT * FROM cadcheque
                                  WHERE ano_cheque = :ano AND mes_cheque = :mes
                                  ORDER BY dt_emissao_cheque ASC";
                    $stmtcadcheque = $pdo->prepare($sqlCheque);
                    $stmtcadcheque->execute(array(':ano' => $a, ':mes' => $b));
                    $cheques = $stmtcadcheque->fetchAll(PDO::FETCH_ASSOC);

                    $sqlDoc = "SELECT t.nr_documento, t.dt_emissao_doc, t.cpfcnpj_fornecedor, t.nome_fornecedor,
                                      t.valor_doc, t.comprovante, t.finalidade_doc, t.sub_categoria,
                                      s.categoria, s.nome
                               FROM tab_docfiscal t
                               LEFT JOIN sub_categoria s ON s.id = t.sub_categoria
                               WHERE t.ano_doc = :ano
                                 AND t.mes_doc = :mes
                               ORDER BY STR_TO_DATE(t.dt_emissao_doc, '%d/%m/%Y') ASC";
                    $stmtdocfiscal = $pdo->prepare($sqlDoc);
                    $stmtdocfiscal->execute(array(':ano' => $a, ':mes' => $b));
                    $documentos = $stmtdocfiscal->fetchAll(PDO::FETCH_ASSOC);
                    $num_rows = count($documentos);
                    foreach ($documentos as $lnPre) {
                        if (isset($lnPre['sub_categoria']) && (string) $lnPre['sub_categoria'] === '175') {
                            $total2 += $lnPre['valor_doc'];
                        }
                    }
                } catch (PDOException $e) {
                    echo "<script type=\"text/javascript\">alert(\"Falha ao consultar os dados contábeis.\");</script>";
                    echo "<p>Falha ao consultar os dados contábeis.</p>";
                    include "rodape.php";
                    return;
                }

                if (count($cheques) > 0) {
                    ?>

                    <div class="barra-ferramentas">
                        <input type="text" placeholder="Buscar na tabela de cheques..." onkeyup="filtrarTabela('tabela1', this.value)">
                        <button type="button" onclick="exportarExcel('tabela1', 'cheques_<?= htmlspecialchars($b, ENT_QUOTES, 'UTF-8') ?>_<?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?>.xls')">
                            Exportar cheques para Excel
                        </button>
                    </div>

                <center>
                    <table border="2" id="tabela1">
                        <tr>
                            <td class="cabecalho-ordenavel" onclick="ordenarTabela(0, 'tabela1', this)" width="8%" align="center" bgcolor="#191970">
                                <font size="2" color="#F5FFFA"><b>Ano <span class="seta"></span></b></font>
                            </td>
                            <td class="cabecalho-ordenavel" onclick="ordenarTabela(1, 'tabela1', this)" width="8%" align="center" bgcolor="#191970">
                                <font size="2" color="#F5FFFA"><b>Mês <span class="seta"></span></b></font>
                            </td>
                            <td class="cabecalho-ordenavel" onclick="ordenarTabela(2, 'tabela1', this)" width="13%" align="center" bgcolor="#191970">
                                <font size="2" color="#F5FFFA"><b>Banco <span class="seta"></span></b></font>
                            </td>
                            <td class="cabecalho-ordenavel" onclick="ordenarTabela(3, 'tabela1', this)" width="11%" align="center" bgcolor="#191970">
                                <font size="2" color="#F5FFFA"><b>Transação <span class="seta"></span></b></font>
                            </td>
                            <td class="cabecalho-ordenavel" onclick="ordenarTabela(4, 'tabela1', this)" width="8%" align="center" bgcolor="#191970">
                                <font size="2" color="#F5FFFA"><b>Valor (R$) <span class="seta"></span></b></font>
                            </td>
                            <td class="cabecalho-ordenavel" onclick="ordenarTabela(5, 'tabela1', this)" width="33%" align="center" bgcolor="#191970">
                                <font size="2" color="#F5FFFA"><b>Finalidade <span class="seta"></span></b></font>
                            </td>
                        </tr>

                        <?php
                        foreach ($cheques as $ln) {
                            $mostrar = number_format($ln['valor_cheque'], 2, ",", ".");
                            $tipoCheque = isset($ln['debito']) ? $ln['debito'] : '';
                            if ($tipoCheque != 'Transferencia' && $tipoCheque != 'Protocolo' && $tipoCheque != 'Procedimentos internos' && $tipoCheque != 'Contratos') {
                                $total += $ln['valor_cheque'];
                            }
                            ?>
                            <tr>
                                <td align="center"><font size="2" color="#000000"><?= $ln['ano_cheque'] ?></font></td>
                                <td align="center"><font size="2" color="#000000"><?= $ln['mes_cheque'] ?></font></td>
                                <td align="center"><font size="2" color="#000000"><?= $ln['banco_cheque'] ?></font></td>
                                <td align="center"><font size="2" color="#000000"><?= $ln['nr_cheque'] ?></font></td>
                                <td align="center"><font size="2" color="#000000">R$ <?= $mostrar ?></font></td>
                                <td><font size="2" color="#000000"><?= $ln['finalidade_cheque'] ?></font></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </center>

                    <br>

                    <center>
                        <table border="0">
                            <tr>
                                <td width="70%" align="left"><font size="3"><b>Somatório de transações para <?= htmlspecialchars($b, ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?>:</b></font></td>
                                <td><b>R$ <?= number_format($total, 2, ",", ".") ?></b></td>
                            </tr>
                            <tr>
                                <td align="left"><font size="3"><b>Somatório de lançamentos de fundo de caixa para <?= htmlspecialchars($b, ENT_QUOTES, 'UTF-8') ?>/<?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?>:</b></font></td>
                                <td><b>R$ <?= number_format($total2, 2, ",", ".") ?></b></td>
                            </tr>
                            <tr>
                                <td align="left"><font size="3"><b>Saldo em caixa:</b></font></td>
                                <?php
                                $saldo = $total - $total2;
                                if ($saldo > 0) {
                                    ?>
                                    <td><b><font size="3" color="#000066">R$ <?= number_format($saldo, 2, ",", ".") ?></font></b></td>
                                    <?php
                                } else {
                                    ?>
                                    <td><b><font size="3" color="#CC0000">R$ <?= number_format($saldo, 2, ",", ".") ?></font></b></td>
                                    <?php
                                }
                                ?>
                            </tr>
                        </table>
                    </center>

                    <hr>
                    <?php
                }
                ?>

            <div class="barra-ferramentas">
                <input type="text" placeholder="Buscar na tabela de documentos..." onkeyup="filtrarTabela('tabela2', this.value)">
                <button type="button" onclick="exportarExcel('tabela2', 'documentos_<?= htmlspecialchars($b, ENT_QUOTES, 'UTF-8') ?>_<?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?>.xls')">Exportar documentos para Excel</button>
            </div>

            <center><font size="3" color="#000000"><b>Relatório Contábil - <?= strtoupper(htmlspecialchars($b, ENT_QUOTES, 'UTF-8')) ?>/<?= htmlspecialchars($a, ENT_QUOTES, 'UTF-8') ?></b></font></center>
            <br>
            <table border="2" id="tabela2">
                <tr>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(0, 'tabela2', this)" width="10%" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Nº Documento <span class="seta"></span></b></font></td>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(1, 'tabela2', this)" width="10%" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Categoria <span class="seta"></span></b></font></td>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(2, 'tabela2', this)" width="10%" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Sub-Categoria <span class="seta"></span></b></font></td>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(3, 'tabela2', this)" width="10%" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Data <span class="seta"></span></b></font></td>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(4, 'tabela2', this)" width="15%" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>CPF/CNPJ <span class="seta"></span></b></font></td>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(5, 'tabela2', this)" width="20%" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Recebedor <span class="seta"></span></b></font></td>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(6, 'tabela2', this)" width="9%" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Valor (R$) <span class="seta"></span></b></font></td>
                    <td class="cabecalho-ordenavel" onclick="ordenarTabela(7, 'tabela2', this)" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Descrição <span class="seta"></span></b></font></td>
                    <td class="no-export" align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>&nbsp;</b></font></td>
                </tr>
                <?php
                if ($num_rows == 0) {
                    echo '<tr><td colspan="9" align="center"><font size="2" color="#000000">Não existem documentos cadastrados para ' . htmlspecialchars($b, ENT_QUOTES, 'UTF-8') . '/' . htmlspecialchars($a, ENT_QUOTES, 'UTF-8') . '.</font></td></tr>';
                }

                foreach ($documentos as $ln) {
                    $nr_documento = "Não informado";
                    if (isset($ln['nr_documento']) && $ln['nr_documento'] !== "S/N" && $ln['nr_documento'] !== '') {
                        $nr_documento = $ln['nr_documento'];
                    }
                    $data = DateTime::createFromFormat('d/m/Y', $ln['dt_emissao_doc']);
                    if ($ln['valor_doc'] == 0) {
                        $mostrar = "--";
                    } else {
                        $mostrar = 'R$ ' . number_format($ln['valor_doc'], 2, ",", ".");
                    }

                    $categoriaDoc = isset($ln['categoria']) ? strtolower($ln['categoria']) : '';
                    if ($categoriaDoc == 'receita') {
                        $totalContabil += $ln['valor_doc'];
                    }
                    if ($categoriaDoc == 'despesa') {
                        $totalContabildespesa += $ln['valor_doc'];
                    }

                    $categoriaResumo = trim(isset($ln['categoria']) ? $ln['categoria'] : '');
                    if ($categoriaResumo == '') {
                        $categoriaResumo = 'Sem categoria';
                    }
                    $categoriaResumo = strtoupper($categoriaResumo);

                    if (!isset($totaisPorCategoria[$categoriaResumo])) {
                        $totaisPorCategoria[$categoriaResumo] = 0;
                    }
                    $totaisPorCategoria[$categoriaResumo] += $ln['valor_doc'];
                    $totalGeralDocumentos += $ln['valor_doc'];

                    $arquivoComprovante = isset($ln['comprovante']) ? $ln['comprovante'] : '';
                    $hrefLocal = 'documentosfiscais/' . $arquivoComprovante;
                    $hrefRemoto = 'https://1portodos.com.br/financeiroVillage/documentosfiscais/' . $arquivoComprovante;
                    $hrefComprovante = ($arquivoComprovante !== '' && file_exists($hrefLocal)) ? $hrefLocal : $hrefRemoto;

                    $dataOrdenacao = '';
                    $dataExibicao = $ln['dt_emissao_doc'];
                    if ($data) {
                        $dataExibicao = $data->format('d/m/Y');
                        $dataOrdenacao = $data->format('Ymd');
                    }
                    ?>
                    <tr>
                        <td align="center"><font size="2" color="#000000"><?= htmlspecialchars($nr_documento, ENT_QUOTES, 'UTF-8') ?></font></td>
                        <td align="center"><font size="2" color="#000000"><?= strtoupper(htmlspecialchars(isset($ln['categoria']) ? $ln['categoria'] : '', ENT_QUOTES, 'UTF-8')) ?></font></td>
                        <td align="center"><font size="2" color="#000000"><?= strtoupper(htmlspecialchars(isset($ln['nome']) ? $ln['nome'] : '', ENT_QUOTES, 'UTF-8')) ?></font></td>
                        <td align="center" data-order="<?= $dataOrdenacao ?>">
                            <font size="2" color="#000000"><?= htmlspecialchars($dataExibicao, ENT_QUOTES, 'UTF-8') ?></font>
                        </td>
                        <td align="left"><font size="2" color="#000000"><?= htmlspecialchars($ln['cpfcnpj_fornecedor'], ENT_QUOTES, 'UTF-8') ?></font></td>
                        <td><font size="2" color="#000000"><?= strtoupper(htmlspecialchars($ln['nome_fornecedor'], ENT_QUOTES, 'UTF-8')) ?></font></td>
                        <td align="center"><font size="2" color="#000000"><?= $mostrar ?></font></td>
                        <td><font size="2" color="#000000"><?= htmlspecialchars($ln['finalidade_doc'], ENT_QUOTES, 'UTF-8') ?></font></td>
                        <td class="no-export" align="center" valign="middle" bgcolor="#FFFFFA">
                            <?php if ($arquivoComprovante !== '') { ?>
                            <a href="<?= htmlspecialchars($hrefComprovante, ENT_QUOTES, 'UTF-8') ?>" target="_blank" title="Ver comprovante">
                                <img src="<?= $iconeVisualizar ?>" height="25" width="25" align="middle" border="0">
                            </a>
                            <?php } else { ?>
                            <img src="images/proibido-preto.svg" height="20" width="20" align="middle" border="0" title="Documento indisponível">
                            <?php } ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br>

            <center>
                <table class="resumo-totais">
                    <tr>
                        <th colspan="2">Resumo por categoria</th>
                    </tr>
                    <?php
                    if (sizeof($totaisPorCategoria) > 0) {
                        ksort($totaisPorCategoria);
                        foreach ($totaisPorCategoria as $categoriaResumo => $valorCategoria) {
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($categoriaResumo, ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="valor">R$ <?= number_format($valorCategoria, 2, ",", ".") ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="2" align="center">Nenhum documento encontrado.</td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr class="total-geral">
                        <td>Total geral de documentos</td>
                        <td class="valor">R$ <?= number_format($totalGeralDocumentos, 2, ",", ".") ?></td>
                    </tr>
                    <tr class="total-geral">
                        <td>Total de documentos</td>
                        <td class="valor"><?= $num_rows ?></td>
                    </tr>
                </table>
            </center>
            <br>

            <b>Somatório de receitas: R$ <?= number_format($totalContabil, 2, ",", ".") ?> <br><p></p>
                <b>Somatório de despesas: R$ <?= number_format($totalContabildespesa, 2, ",", ".") ?> <br><p></p>
                </b>

                <?php
            }
            ?>
            </body>
    </div>
</div>

<script>
    function normalizarValorParaNumero(valor) {
        valor = valor.replace(/R\$\s?/g, '')
                .replace(/\./g, '')
                .replace(',', '.')
                .replace(/^\s+|\s+$/g, '');

        if (valor === '' || valor === '--')
            return null;

        var numero = parseFloat(valor);
        return isNaN(numero) ? null : numero;
    }

    function ordenarTabela(coluna, tabelaId, th) {
        var tabela = document.getElementById(tabelaId);
        if (!tabela)
            return;

        var linhas = Array.prototype.slice.call(tabela.rows, 1);
        var asc = th.className.indexOf('ativo-asc') === -1;

        var cabecalhos = tabela.rows[0].cells;
        for (var i = 0; i < cabecalhos.length; i++) {
            cabecalhos[i].className = cabecalhos[i].className
                    .replace('ativo-asc', '')
                    .replace('ativo-desc', '');
        }

        linhas.sort(function (a, b) {
            var celA = a.cells[coluna];
            var celB = b.cells[coluna];

            var A = celA ? celA.getAttribute('data-order') || celA.textContent : '';
            var B = celB ? celB.getAttribute('data-order') || celB.textContent : '';

            A = A.replace(/^\s+|\s+$/g, '');
            B = B.replace(/^\s+|\s+$/g, '');

            var numA = normalizarValorParaNumero(A);
            var numB = normalizarValorParaNumero(B);

            if (numA !== null && numB !== null) {
                return asc ? numA - numB : numB - numA;
            }

            A = A.toLowerCase();
            B = B.toLowerCase();

            if (A < B)
                return asc ? -1 : 1;
            if (A > B)
                return asc ? 1 : -1;
            return 0;
        });

        for (var j = 0; j < linhas.length; j++) {
            tabela.appendChild(linhas[j]);
        }

        th.className += asc ? ' ativo-asc' : ' ativo-desc';
    }

    function filtrarTabela(tabelaId, valor) {
        var filtro = valor.toLowerCase();
        var linhas = document.querySelectorAll('#' + tabelaId + ' tr');
        for (var i = 1; i < linhas.length; i++) {
            var texto = linhas[i].innerText.toLowerCase();
            linhas[i].style.display = texto.indexOf(filtro) !== -1 ? '' : 'none';
        }
    }

    function exportarExcel(tabelaId, nomeArquivo) {
        var tabela = document.getElementById(tabelaId);
        if (!tabela)
            return;

        var tabelaClone = tabela.cloneNode(true);
        var colunas = tabelaClone.querySelectorAll(".no-export");
        for (var i = 0; i < colunas.length; i++) {
            if (colunas[i].parentNode) {
                colunas[i].parentNode.removeChild(colunas[i]);
            }
        }

        var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
                'xmlns:x="urn:schemas-microsoft-com:office:excel">' +
                '<head><meta charset="UTF-8"></head><body>' +
                tabelaClone.outerHTML +
                '</body></html>';

        var blob = new Blob([html], {type: 'application/vnd.ms-excel'});
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = nomeArquivo;
        a.click();
        URL.revokeObjectURL(url);
    }
</script>

<?php
include "rodape.php";
?>
