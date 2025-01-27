<?php
require('./fpdf/fpdf.php');
require_once 'libs/Classes/PHPExcel.php';


function lerPDF($arquivoPDF) {
    // Lê o conteúdo de um PDF usando `FPDF`
    $conteudo = '';
    
    if (!file_exists($arquivoPDF)) {
        die("Arquivo PDF não encontrado!");
    }

    // Simula a leitura do PDF (use bibliotecas adicionais como pdftotext para extração real)
    $conteudo = file_get_contents($arquivoPDF); // Para simulação
    return $conteudo;
}

function exportarParaExcel($conteudo, $arquivoExcel) {
    // Cria uma nova planilha
    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0);

    // Adiciona o conteúdo à planilha (linha por linha)
    $linhas = explode("\n", $conteudo); // Divide o conteúdo por linhas
    $linhaAtual = 1;

    foreach ($linhas as $linha) {
        $excel->getActiveSheet()->setCellValue('A' . $linhaAtual, $linha);
        $linhaAtual++;
    }

    // Salva o arquivo Excel
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    $writer->save($arquivoExcel);
}

// Caminho para o arquivo PDF
$arquivoPDF = 'arquivo.pdf';

// Caminho para salvar o arquivo Excel
$arquivoExcel = 'saida.xlsx';

try {
    // Lê o conteúdo do PDF
    $conteudoPDF = lerPDF($arquivoPDF);

    // Exporta o conteúdo para Excel
    exportarParaExcel($conteudoPDF, $arquivoExcel);

    echo "Arquivo Excel gerado com sucesso: $arquivoExcel";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
