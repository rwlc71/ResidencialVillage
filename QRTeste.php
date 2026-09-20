<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluindo a biblioteca FPDF e Code128
require('fpdf/fpdf.php');
require('fpdf/code128.php');
require_once('Libs/phpqrcode/qrlib.php');


// Classe PDF personalizada para incluir código de barras
class PDF extends PDF_Code128 {

    // Função para criar o código de barras
    public function Barcode($x, $y, $code, $width = 1.5, $height = 10) {
        $this->Code128($x, $y, $code, $width * 10, $height);
    }

}

$pdf = new PDF();
$pdf->AddPage();

setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese');
$data = new DateTime();
$pdf->Image('./images/logo1.jpg', 10, 15, 30);
$pdf->SetFont('Times', 'B', 13);
$pdf->Ln(10);
$pdf->Cell(0, 10, utf8_decode('AUTORIZAÇÃO DE HOSPEDAGEM'), 0, 1, 'R');
$pdf->Ln(6);

//    $pdf->SetFont('Times', 'B', 11);
//    $pdf->Cell(0, 5, utf8_decode('RESIDENCIAL VILLAGE THERMAS DAS CALDAS'), 0, 1, 'L');
//    $pdf->SetFont('Times', '', 11);
//    $pdf->Cell(0, 5, utf8_decode('Caldas Novas - Goiás'), 0, 1, 'L');
//    $pdf->Ln(6);
//=======================================
// LINHA COM TEXTO À ESQUERDA E QRCODE À DIREITA 
// Define posição inicial Y
$yInicial = $pdf->GetY();
$pdf->SetFont('Times', 'B', 11);

// Gera QR Code
$tempDir = dirname(__FILE__) . "/temp";
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}
$host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));
$firstPart = isset($parts[0]) ? $parts[0] : '';
$url = $host . "/" . $firstPart . "/validador1.php?TESTE";

$qrContent = $url;
$arquivoQR = $tempDir . "/qr_" . uniqid() . ".png";
QRcode::png($qrContent, $arquivoQR, 'H', 4, 2);

// Tamanho do QR
$larguraQR = 25;

// Posição do QR (alinhado à direita, margem de 10)
$xQR = $pdf->GetPageWidth() - $larguraQR - 12;
$yQR = $yInicial - 7; // mesmo Y do texto

$pdf->Image($arquivoQR, $xQR, $yQR, $larguraQR, $larguraQR);
unlink($arquivoQR);

// Gerando o arquivo PDF

$pdfOutputPath = 'autorizacao_hospedagem.pdf';
$pdf->Output('F', $pdfOutputPath);

// Exibindo o PDF no navegador
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $pdfOutputPath . '"');
readfile($pdfOutputPath);

// Funções auxiliares
function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}
?>


