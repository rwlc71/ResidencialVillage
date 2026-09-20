<?php
/**
 * Exporta XLSX no modelo visual da planilha anexa
 * (Premissas, Autorizações, Resumo Financeiro — fontes/cores originais).
 */
session_name('SESSAO_PHP');
session_start();
include dirname(__FILE__) . '/../conexao.php';
include dirname(__FILE__) . '/../valida/verifica_autenticacao.php';
include dirname(__FILE__) . '/../valida/verifica_acessoAdm.php';
include dirname(__FILE__) . '/planilha_financeira_lib.php';

$phpExcelPath = dirname(__FILE__) . '/../Libs/Classes/PHPExcel.php';
if (!file_exists($phpExcelPath)) {
    include dirname(__FILE__) . '/exportar_planilha_autorizacoes_csv.php';
    exit;
}

require_once $phpExcelPath;
require_once dirname(__FILE__) . '/../Libs/Classes/PHPExcel/IOFactory.php';

$valorBaixa = pf_parse_money(isset($_POST['valor_baixa']) ? $_POST['valor_baixa'] : '100', 100.0);
$valorAlta = pf_parse_money(isset($_POST['valor_alta']) ? $_POST['valor_alta'] : '150', 150.0);
$mapa = pf_montar_mapa_temporada($_POST);
$dtIni = pf_parse_br_date(isset($_POST['dt_emissao_ini']) ? $_POST['dt_emissao_ini'] : '');
$dtFim = pf_parse_br_date(isset($_POST['dt_emissao_fim']) ? $_POST['dt_emissao_fim'] : '');

$registros = pf_buscar_autorizacoes($dtIni, $dtFim);
$proc = pf_processar_linhas($registros, $valorAlta, $valorBaixa, $mapa);
$linhas = $proc['linhas'];
$totais = $proc['totais'];

/** Cores e fonte do arquivo original */
$FONT = 'Carlito';
$C_HEADER = '17365D';
$C_SUB = 'D9EAF7';
$C_EDIT_BG = 'FFF2CC';
$C_EDIT_FG = '0000FF';
$C_FOOTER = 'F2F2F2';
$C_WHITE = 'FFFFFF';
$C_BLACK = '000000';
$FMT_MONEY = '"R$" #,##0.00';
$FMT_DATE = 'dd/mm/yyyy';
$FMT_PCT = '0.00%';

function pf_xls_font($size = 11, $bold = false, $color = '000000', $name = 'Carlito')
{
    return array(
        'font' => array(
            'name' => $name,
            'size' => $size,
            'bold' => $bold,
            'color' => array('rgb' => $color)
        )
    );
}

function pf_xls_fill($rgb)
{
    return array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => $rgb)
        )
    );
}

function pf_xls_align($h = PHPExcel_Style_Alignment::HORIZONTAL_GENERAL, $wrap = false)
{
    return array(
        'alignment' => array(
            'horizontal' => $h,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
            'wrap' => $wrap
        )
    );
}

function pf_xls_date_to_excel($dmy)
{
    if ($dmy === '' || $dmy === null) {
        return null;
    }
    $dt = DateTime::createFromFormat('d/m/Y', $dmy);
    if (!$dt) {
        return $dmy;
    }
    return PHPExcel_Shared_Date::PHPToExcel($dt);
}

$xls = new PHPExcel();
$xls->getDefaultStyle()->applyFromArray(pf_xls_font(11, false, $C_BLACK, $FONT));

// ========== Premissas ==========
$s1 = $xls->getActiveSheet();
$s1->setTitle('Premissas');

$s1->mergeCells('A1:D1');
$s1->setCellValue('A1', 'PREMISSAS FINANCEIRAS — RELATÓRIO');
$s1->getStyle('A1')->applyFromArray(array_merge(
    pf_xls_font(14, true, $C_WHITE, $FONT),
    pf_xls_fill($C_HEADER),
    pf_xls_align(PHPExcel_Style_Alignment::HORIZONTAL_CENTER, true)
));
$s1->getRowDimension(1)->setRowHeight(18);

$s1->setCellValue('A3', 'Premissa');
$s1->setCellValue('B3', 'Valor');
$s1->getStyle('A3:B3')->applyFromArray(array_merge(
    pf_xls_font(11, true, $C_BLACK, $FONT),
    pf_xls_fill($C_SUB)
));

$s1->setCellValue('A4', 'Taxa baixa temporada (R$)');
$s1->setCellValue('B4', $valorBaixa);
$s1->setCellValue('A5', 'Taxa alta temporada (R$)');
$s1->setCellValue('B5', $valorAlta);
$s1->getStyle('A4:A5')->applyFromArray(pf_xls_font(11, false, $C_BLACK, $FONT));
$s1->getStyle('B4:B5')->applyFromArray(array_merge(
    pf_xls_font(11, false, $C_EDIT_FG, $FONT),
    pf_xls_fill($C_EDIT_BG)
));
$s1->getStyle('B4:B5')->getNumberFormat()->setFormatCode($FMT_MONEY);

$s1->setCellValue('A7', 'Mês');
$s1->setCellValue('B7', 'Temporada');
$s1->getStyle('A7:B7')->applyFromArray(array_merge(
    pf_xls_font(11, true, $C_BLACK, $FONT),
    pf_xls_fill($C_SUB)
));

$row = 8;
foreach ($mapa as $ym => $tipo) {
    $s1->setCellValue('A' . $row, pf_label_mes_pt($ym));
    $s1->setCellValue('B' . $row, ($tipo === 'alta') ? 'Alta' : 'Baixa');
    $s1->getStyle('A' . $row)->applyFromArray(pf_xls_font(11, false, $C_BLACK, $FONT));
    $s1->getStyle('B' . $row)->applyFromArray(array_merge(
        pf_xls_font(11, false, $C_EDIT_FG, $FONT),
        pf_xls_fill($C_EDIT_BG)
    ));
    $row++;
}

$obsRow = $row + 2;
$s1->mergeCells('A' . $obsRow . ':D' . $obsRow);
$s1->setCellValue('A' . $obsRow, 'OBSERVAÇÕES');
$s1->getStyle('A' . $obsRow)->applyFromArray(array_merge(
    pf_xls_font(11, true, $C_WHITE, $FONT),
    pf_xls_fill($C_HEADER)
));

$obs = array(
    'A cobrança é calculada por autorização emitida, e não por quantidade de hóspedes.',
    'Critério de seleção do período: data de emissão da autorização.',
    "As linhas canceladas permanecem com 'Cobrar? = Sim' por padrão, em coerência com o critério de emissão.",
    'A temporada é classificada pelo mês da DATA DE ENTRADA. As classificações mensais de alta/baixa temporada são premissas editáveis.'
);
$o = $obsRow + 1;
foreach ($obs as $i => $txt) {
    $s1->setCellValue('A' . $o, ($i + 1));
    $s1->setCellValue('B' . $o, $txt);
    $s1->mergeCells('B' . $o . ':D' . $o);
    $s1->getStyle('A' . $o . ':D' . $o)->applyFromArray(array_merge(
        pf_xls_font(11, false, $C_BLACK, $FONT),
        pf_xls_align(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL, true)
    ));
    $o++;
}

$s1->getColumnDimension('A')->setWidth(25);
$s1->getColumnDimension('B')->setWidth(28);
$s1->getColumnDimension('C')->setWidth(28);
$s1->getColumnDimension('D')->setWidth(28);

// ========== Autorizações ==========
$s2 = $xls->createSheet();
$s2->setTitle('Autorizações');

$headers = array(
    'ID', 'Unidade', 'Proprietário', 'Hóspedes', 'Entrada', 'Saída',
    'Status', 'Mês', 'Temporada', 'Cobrar?', 'Taxa (R$)', 'Receita (R$)'
);
$col = 0;
foreach ($headers as $h) {
    $s2->setCellValueByColumnAndRow($col, 1, $h);
    $col++;
}
$s2->getStyle('A1:L1')->applyFromArray(array_merge(
    pf_xls_font(11, true, $C_WHITE, $FONT),
    pf_xls_fill($C_HEADER),
    pf_xls_align(PHPExcel_Style_Alignment::HORIZONTAL_CENTER, true)
));
$s2->getRowDimension(1)->setRowHeight(15);

$r = 2;
foreach ($linhas as $l) {
    $s2->setCellValue('A' . $r, $l['seq']);
    $s2->setCellValue('B' . $r, $l['unidade']);
    $s2->setCellValue('C' . $r, $l['proprietario']);
    $s2->setCellValue('D' . $r, $l['hospedes']);

    $excelEntrada = pf_xls_date_to_excel($l['entrada']);
    $excelSaida = pf_xls_date_to_excel($l['saida']);
    if (is_numeric($excelEntrada)) {
        $s2->setCellValue('E' . $r, $excelEntrada);
        $s2->getStyle('E' . $r)->getNumberFormat()->setFormatCode($FMT_DATE);
    } else {
        $s2->setCellValue('E' . $r, $l['entrada']);
    }
    if (is_numeric($excelSaida)) {
        $s2->setCellValue('F' . $r, $excelSaida);
        $s2->getStyle('F' . $r)->getNumberFormat()->setFormatCode($FMT_DATE);
    } else {
        $s2->setCellValue('F' . $r, $l['saida']);
    }

    $s2->setCellValue('G' . $r, $l['status']);
    $s2->setCellValue('H' . $r, $l['mes']);
    $s2->setCellValue('I' . $r, $l['temporada']);
    $s2->setCellValue('J' . $r, $l['cobrar']);
    $s2->setCellValue('K' . $r, $l['taxa']);
    $s2->setCellValue('L' . $r, $l['receita']);

    $s2->getStyle('A' . $r . ':I' . $r)->applyFromArray(pf_xls_font(11, false, $C_BLACK, $FONT));
    $s2->getStyle('K' . $r . ':L' . $r)->applyFromArray(pf_xls_font(11, false, $C_BLACK, $FONT));
    $s2->getStyle('H' . $r)->applyFromArray(pf_xls_align(PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
    $s2->getStyle('J' . $r)->applyFromArray(array_merge(
        pf_xls_font(11, false, $C_EDIT_FG, $FONT),
        pf_xls_fill($C_EDIT_BG)
    ));
    $s2->getStyle('K' . $r . ':L' . $r)->getNumberFormat()->setFormatCode($FMT_MONEY);
    $r++;
}

$widthsAut = array('A' => 7, 'B' => 11, 'C' => 30, 'D' => 11, 'E' => 12, 'F' => 12, 'G' => 13, 'H' => 13, 'I' => 12, 'J' => 10, 'K' => 16, 'L' => 16);
foreach ($widthsAut as $colLetter => $w) {
    $s2->getColumnDimension($colLetter)->setWidth($w);
}

// ========== Resumo Financeiro ==========
$s3 = $xls->createSheet();
$s3->setTitle('Resumo Financeiro');

$s3->mergeCells('A1:H1');
$s3->setCellValue('A1', 'RESUMO FINANCEIRO — AUTORIZAÇÕES DE HOSPEDAGEM');
$s3->getStyle('A1')->applyFromArray(array_merge(
    pf_xls_font(14, true, $C_WHITE, $FONT),
    pf_xls_fill($C_HEADER),
    pf_xls_align(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
));
$s3->getRowDimension(1)->setRowHeight(18);

$s3->setCellValue('A3', 'Indicador');
$s3->setCellValue('B3', 'Resultado');
$s3->getStyle('A3:B3')->applyFromArray(array_merge(
    pf_xls_font(11, true, $C_BLACK, $FONT),
    pf_xls_fill($C_SUB)
));

$s3->setCellValue('A4', 'Autorizações de hospedagem');
$s3->setCellValue('B4', $totais['autorizacoes']);
$s3->setCellValue('A5', 'Quantidade de hóspedes');
$s3->setCellValue('B5', $totais['hospedes']);
$s3->setCellValue('A6', 'Receita potencial (R$)');
$s3->setCellValue('B6', $totais['receita']);
$s3->getStyle('A4:B6')->applyFromArray(pf_xls_font(11, false, $C_BLACK, $FONT));
$s3->getStyle('B6')->getNumberFormat()->setFormatCode($FMT_MONEY);

$s3->setCellValue('A9', 'Mês');
$s3->setCellValue('B9', 'Autorizações');
$s3->setCellValue('C9', 'Hóspedes');
$s3->setCellValue('D9', 'Receita potencial (R$)');
$s3->setCellValue('E9', '% Receita');
$s3->getStyle('A9:E9')->applyFromArray(array_merge(
    pf_xls_font(11, true, $C_WHITE, $FONT),
    pf_xls_fill($C_HEADER)
));

$rm = 10;
$sumAut = 0;
$sumHosp = 0;
$sumRec = 0.0;
foreach ($totais['por_mes'] as $ym => $m) {
    $pct = ($totais['receita'] > 0) ? ($m['receita'] / $totais['receita']) : 0;
    $s3->setCellValue('A' . $rm, $m['label']);
    $s3->setCellValue('B' . $rm, $m['autorizacoes']);
    $s3->setCellValue('C' . $rm, $m['hospedes']);
    $s3->setCellValue('D' . $rm, $m['receita']);
    $s3->setCellValue('E' . $rm, $pct);
    $s3->getStyle('A' . $rm . ':E' . $rm)->applyFromArray(pf_xls_font(11, false, $C_BLACK, $FONT));
    $s3->getStyle('D' . $rm)->getNumberFormat()->setFormatCode($FMT_MONEY);
    $s3->getStyle('E' . $rm)->getNumberFormat()->setFormatCode($FMT_PCT);
    $sumAut += $m['autorizacoes'];
    $sumHosp += $m['hospedes'];
    $sumRec += $m['receita'];
    $rm++;
}

$totalRow = $rm;
$s3->setCellValue('A' . $totalRow, 'TOTAL');
$s3->setCellValue('B' . $totalRow, $sumAut);
$s3->setCellValue('C' . $totalRow, $sumHosp);
$s3->setCellValue('D' . $totalRow, $sumRec);
$s3->setCellValue('E' . $totalRow, ($sumRec > 0 ? 1 : 0));
$s3->getStyle('A' . $totalRow . ':E' . $totalRow)->applyFromArray(pf_xls_font(11, true, $C_BLACK, $FONT));
$s3->getStyle('A' . $totalRow . ':E' . $totalRow)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$s3->getStyle('D' . $totalRow)->getNumberFormat()->setFormatCode($FMT_MONEY);
$s3->getStyle('E' . $totalRow)->getNumberFormat()->setFormatCode($FMT_PCT);

$periodoTxt = ($dtIni ? $dtIni->format('d/m/Y') : '...') . ' a ' . ($dtFim ? $dtFim->format('d/m/Y') : '...');
$fonteRow = $totalRow + 3;
$s3->mergeCells('A' . $fonteRow . ':H' . ($fonteRow + 2));
$s3->setCellValue(
    'A' . $fonteRow,
    'Fonte: Relatório Gerencial - Reservas do Residencial Village Thermas das Caldas, com '
    . (int) $totais['hospedes'] . ' hóspedes no período apresentado (' . $periodoTxt . '). '
    . 'Valores financeiros simulados com base nas premissas editáveis de R$ '
    . number_format($valorBaixa, 2, ',', '.') . ' (baixa) e R$ '
    . number_format($valorAlta, 2, ',', '.') . ' (alta), por autorização emitida.'
);
$s3->getStyle('A' . $fonteRow)->applyFromArray(array_merge(
    pf_xls_font(9, false, $C_BLACK, $FONT),
    pf_xls_fill($C_FOOTER),
    pf_xls_align(PHPExcel_Style_Alignment::HORIZONTAL_GENERAL, true)
));

$s3->getColumnDimension('A')->setWidth(34);
$s3->getColumnDimension('B')->setWidth(20);
$s3->getColumnDimension('C')->setWidth(20);
$s3->getColumnDimension('D')->setWidth(20);
$s3->getColumnDimension('E')->setWidth(20);

$xls->setActiveSheetIndex(0);

$filename = 'Planilha_Financeira_Autorizacoes_Hospedagem_Village_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
$writer->save('php://output');
exit;
