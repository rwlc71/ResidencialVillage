<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    .pf-box { border: 1px solid #ccc; background: #fafafa; padding: 12px 14px; margin: 12px 0; }
    .pf-box h3 { margin: 0 0 8px; font-size: 14px; color: #191970; }
    .pf-note { font-size: 12px; color: #444; line-height: 1.45; }
    .pf-premissa label { display: inline-block; min-width: 160px; font-weight: bold; font-size: 12px; }
    .pf-premissa div { margin: 6px 0; }
    .pf-totais td { padding: 4px 10px; font-size: 13px; }
    .pf-totais .destaque { font-size: 15px; color: #191970; }
    table.pf-temp { border-collapse: collapse; font-size: 12px; }
    table.pf-temp th, table.pf-temp td { border: 1px solid #ccc; padding: 4px 8px; }
    table.pf-temp th { background: #e8eef5; }
    table.pf-feriados { border-collapse: collapse; font-size: 12px; width: 100%; }
    table.pf-feriados th, table.pf-feriados td { border: 1px solid #ccc; padding: 4px 6px; }
    table.pf-feriados th { background: #e8eef5; }
    table.pf-feriados input[type="text"] { font-size: 12px; }
    .pf-premissa-col { vertical-align: top; }
    .pf-fonte { font-size: 11px; color: #666; margin-top: 6px; }
</style>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/verifica_acessoAdm.php";
include "funcoes/planilha_financeira_lib.php";
include "topo.php";

$pesquisou = (isset($_POST['filtro']) && $_POST['filtro'] === 'Pesquisar');
$anoCorrente = pf_ano_corrente();
$valorBaixa = $pesquisou ? pf_parse_money($_POST['valor_baixa'], 100) : 100.0;
$valorAlta = $pesquisou ? pf_parse_money($_POST['valor_alta'], 150) : 150.0;
$dtIniStr = $pesquisou && !empty($_POST['dt_emissao_ini']) ? $_POST['dt_emissao_ini'] : '';
$dtFimStr = $pesquisou && !empty($_POST['dt_emissao_fim']) ? $_POST['dt_emissao_fim'] : '';
$dtIni = pf_parse_br_date($dtIniStr);
$dtFim = pf_parse_br_date($dtFimStr);

$mapa = $pesquisou ? pf_montar_mapa_temporada($_POST) : pf_mapa_padrao_ano($anoCorrente);
$feriadosPack = ($pesquisou && !empty($_POST['fer_data']))
    ? pf_montar_feriados_post($_POST)
    : pf_feriados_nacionais_ano($anoCorrente);
$feriados = $feriadosPack['lista'];
$feriadosFonte = $feriadosPack['fonte'];
$feriadosAtualizado = $feriadosPack['atualizado_em'];

$linhas = array();
$totais = array(
    'autorizacoes' => 0,
    'hospedes' => 0,
    'cobraveis' => 0,
    'receita' => 0.0,
    'por_mes' => array()
);
$erro = '';

if ($pesquisou) {
    if (!$dtIni || !$dtFim) {
        $erro = 'Informe o período de emissão (data inicial e final).';
    } elseif ($dtFim < $dtIni) {
        $erro = 'A data final de emissão não pode ser menor que a inicial.';
    } else {
        $registros = pf_buscar_autorizacoes($dtIni, $dtFim);
        $proc = pf_processar_linhas($registros, $valorAlta, $valorBaixa, $mapa, $feriados);
        $linhas = $proc['linhas'];
        $totais = $proc['totais'];
    }
}

function pf_render_mapa_inputs($mapa)
{
    $nomes = pf_nomes_meses();
    $html = '';
    $i = 0;
    foreach ($mapa as $ym => $tipo) {
        $p = explode('-', $ym);
        $label = (count($p) === 2 && isset($nomes[$p[1]])) ? ($nomes[$p[1]] . '/' . $p[0]) : $ym;
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($label);
        $html .= '<input type="hidden" name="temp_mes[' . $i . ']" value="' . htmlspecialchars($ym) . '" />';
        $html .= '</td>';
        $html .= '<td><select name="temp_tipo[' . $i . ']">';
        $html .= '<option value="alta"' . ($tipo === 'alta' ? ' selected' : '') . '>Alta</option>';
        $html .= '<option value="baixa"' . ($tipo === 'baixa' ? ' selected' : '') . '>Baixa</option>';
        $html .= '</select></td>';
        $html .= '</tr>';
        $i++;
    }
    return $html;
}

function pf_render_feriados_inputs($feriados)
{
    $html = '';
    $i = 0;
    foreach ($feriados as $f) {
        $periodo = pf_format_iso_br($f['alta_ini']);
        if ($f['alta_ini'] !== $f['alta_fim']) {
            $periodo .= ' a ' . pf_format_iso_br($f['alta_fim']);
        }
        $html .= '<tr>';
        $html .= '<td><input type="text" class="fer-data" name="fer_data[' . $i . ']" size="11" maxlength="10" value="' . htmlspecialchars(pf_format_iso_br($f['data'])) . '" /></td>';
        $html .= '<td><input type="text" name="fer_nome[' . $i . ']" size="32" value="' . htmlspecialchars($f['nome']) . '" /></td>';
        $html .= '<td>' . htmlspecialchars($f['weekday']) . '</td>';
        $html .= '<td align="center">' . ($f['emendado'] ? 'Sim' : 'Não') . '</td>';
        $html .= '<td align="center"><input type="checkbox" name="fer_alta[' . $i . ']" value="1"' . (!empty($f['alta']) ? ' checked="checked"' : '') . ' /></td>';
        $html .= '<td>' . htmlspecialchars($periodo) . '</td>';
        $html .= '</tr>';
        $i++;
    }
    $html .= '<tr>';
    $html .= '<td><input type="text" class="fer-data" name="fer_data[' . $i . ']" size="11" maxlength="10" value="" /></td>';
    $html .= '<td><input type="text" name="fer_nome[' . $i . ']" size="32" value="" placeholder="Incluir feriado" /></td>';
    $html .= '<td></td><td></td>';
    $html .= '<td align="center"><input type="checkbox" name="fer_alta[' . $i . ']" value="1" /></td>';
    $html .= '<td></td>';
    $html .= '</tr>';
    return $html;
}
?>
<script>
    $(function () {
        $("#dt_emissao_ini, #dt_emissao_fim, .fer-data").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });
    });
</script>
<div id="conteudo">
    <div id="cont">
        <h2>Planilha Financeira — Autorizações de Hospedagem - <?= $dbname ?></h2>
        <hr>
        <p class="pf-note">
            Modelo alinhado à planilha de referência do Village.
            <b>Cobrança:</b> 1 taxa por autorização emitida (não por hóspede).
            <b>Filtro:</b> data de emissão.
            <b>Temporada:</b> mês da data de entrada; feriados nacionais emendados com o fim de semana também são alta.
            Canceladas entram com <b>Cobrar? = Sim</b> por padrão.
        </p>

        <form method="post" action="planilha_financeira_autorizacoes.php" id="formPf">
            <div class="pf-box">
                <h3>1. Período (emissão da autorização)</h3>
                <table border="0">
                    <tr>
                        <td><b>Emissão de:</b></td>
                        <td><input type="text" id="dt_emissao_ini" name="dt_emissao_ini" size="12" maxlength="10" value="<?= htmlspecialchars($dtIniStr) ?>" /></td>
                        <td><b>&nbsp;&nbsp;até:</b></td>
                        <td><input type="text" id="dt_emissao_fim" name="dt_emissao_fim" size="12" maxlength="10" value="<?= htmlspecialchars($dtFimStr) ?>" /></td>
                    </tr>
                </table>
            </div>

            <div class="pf-box pf-premissa">
                <h3>2. Premissas (editáveis)</h3>
                <div>
                    <label>Taxa baixa temporada:</label>
                    R$ <input type="text" name="valor_baixa" size="8" value="<?= htmlspecialchars(number_format($valorBaixa, 2, ',', '.')) ?>" />
                </div>
                <div>
                    <label>Taxa alta temporada:</label>
                    R$ <input type="text" name="valor_alta" size="8" value="<?= htmlspecialchars(number_format($valorAlta, 2, ',', '.')) ?>" />
                </div>
                <p class="pf-note">
                    Calendário de temporada de <?= (int) $anoCorrente ?> (janeiro a dezembro).
                    Padrão de <b>alta</b>: janeiro, fevereiro, julho e dezembro.
                    Feriados nacionais emendados com o fim de semana também entram como <b>alta</b> na data de entrada.
                </p>
                <table width="100%" cellspacing="8">
                    <tr>
                        <td class="pf-premissa-col" width="280">
                            <p class="pf-note"><b>Temporada por mês</b></p>
                            <table class="pf-temp">
                                <thead>
                                    <tr><th>Mês/Ano</th><th>Temporada</th></tr>
                                </thead>
                                <tbody>
                                    <?= pf_render_mapa_inputs($mapa) ?>
                                </tbody>
                            </table>
                        </td>
                        <td class="pf-premissa-col">
                            <p class="pf-note">
                                <b>Feriados nacionais <?= (int) $anoCorrente ?></b> (editáveis).
                                A coluna <b>Alta</b> já vem marcada quando o feriado faz ponte com o fim de semana.
                            </p>
                            <input type="hidden" name="fer_fonte" value="<?= htmlspecialchars($feriadosFonte) ?>" />
                            <input type="hidden" name="fer_atualizado" value="<?= htmlspecialchars($feriadosAtualizado) ?>" />
                            <table class="pf-feriados">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Feriado</th>
                                        <th>Dia</th>
                                        <th>Emenda</th>
                                        <th>Alta</th>
                                        <th>Período da ponte</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= pf_render_feriados_inputs($feriados) ?>
                                </tbody>
                            </table>
                            <p class="pf-fonte">
                                Fonte: <?= htmlspecialchars($feriadosFonte !== '' ? $feriadosFonte : 'não informada') ?>.
                                <?php if ($feriadosAtualizado !== '') { ?>
                                    Atualizado em <?= htmlspecialchars($feriadosAtualizado) ?>.
                                <?php } ?>
                                Ponte: sexta–domingo, sábado–segunda, sábado–terça (emenda na segunda) ou quinta–domingo (emenda na sexta).
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <center>
                <input type="submit" name="filtro" value="Pesquisar" />
                <?php if ($pesquisou && $erro === '') { ?>
                    <button type="submit" formaction="funcoes/exportar_planilha_autorizacoes.php" formmethod="post">Download planilha (.xlsx)</button>
                <?php } ?>
            </center>
        </form>

        <?php if ($erro !== '') { ?>
            <p style="color:#b71c1c;"><b><?= htmlspecialchars($erro) ?></b></p>
        <?php } ?>

        <?php if ($pesquisou && $erro === '') { ?>
            <hr>
            <div class="pf-box">
                <h3>3. Resumo financeiro</h3>
                <table class="pf-totais" border="0">
                    <tr><td>Autorizações de hospedagem:</td><td><b><?= (int) $totais['autorizacoes'] ?></b></td></tr>
                    <tr><td>Quantidade de hóspedes:</td><td><b><?= (int) $totais['hospedes'] ?></b></td></tr>
                    <tr><td class="destaque"><b>Receita potencial:</b></td><td class="destaque"><b><?= pf_format_money($totais['receita']) ?></b></td></tr>
                </table>
            </div>

            <?php if (!empty($totais['por_mes'])) { ?>
            <div class="pf-box">
                <h3>4. Resumo por mês (data de entrada)</h3>
                <table border="2" class="estiloTabelas">
                    <tr>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Mês</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Autorizações</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Hóspedes</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Receita potencial</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>% Receita</b></font></td>
                    </tr>
                    <?php foreach ($totais['por_mes'] as $m) {
                        $pct = ($totais['receita'] > 0) ? ($m['receita'] / $totais['receita'] * 100) : 0;
                        ?>
                        <tr>
                            <td align="center"><font size="2"><?= htmlspecialchars($m['label']) ?></font></td>
                            <td align="center"><font size="2"><?= (int) $m['autorizacoes'] ?></font></td>
                            <td align="center"><font size="2"><?= (int) $m['hospedes'] ?></font></td>
                            <td align="right"><font size="2"><?= pf_format_money($m['receita']) ?></font></td>
                            <td align="center"><font size="2"><?= number_format($pct, 1, ',', '.') ?>%</font></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>

            <div class="estiloTabelas table-responsive">
                <h3>5. Autorizações</h3>
                <table border="2">
                    <tr>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>ID</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Unidade</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Proprietário</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Hóspedes</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Entrada</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Saída</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Status</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Mês</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Temporada</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Cobrar?</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Taxa</b></font></td>
                        <td align="center" bgcolor="#191970"><font size="2" color="#F5FFFA"><b>Receita</b></font></td>
                    </tr>
                    <?php if (empty($linhas)) { ?>
                        <tr><td colspan="12" align="center">Nenhuma autorização emitida no período.</td></tr>
                    <?php } else {
                        foreach ($linhas as $l) { ?>
                            <tr>
                                <td align="center"><font size="2"><?= (int) $l['seq'] ?></font></td>
                                <td align="center"><font size="2"><?= htmlspecialchars($l['unidade']) ?></font></td>
                                <td align="left"><font size="2"><?= htmlspecialchars($l['proprietario']) ?></font></td>
                                <td align="center"><font size="2"><?= (int) $l['hospedes'] ?></font></td>
                                <td align="center"><font size="2"><?= htmlspecialchars($l['entrada']) ?></font></td>
                                <td align="center"><font size="2"><?= htmlspecialchars($l['saida']) ?></font></td>
                                <td align="center"><font size="2"><?= htmlspecialchars($l['status']) ?></font></td>
                                <td align="center"><font size="2"><?= htmlspecialchars($l['mes']) ?></font></td>
                                <td align="center"><font size="2"><?= htmlspecialchars($l['temporada']) ?></font></td>
                                <td align="center"><font size="2"><?= htmlspecialchars($l['cobrar']) ?></font></td>
                                <td align="right"><font size="2"><?= pf_format_money($l['taxa']) ?></font></td>
                                <td align="right"><font size="2"><b><?= pf_format_money($l['receita']) ?></b></font></td>
                            </tr>
                        <?php }
                    } ?>
                </table>
            </div>
        <?php } ?>
    </div>
</div>
<?php include "rodape.php"; ?>
