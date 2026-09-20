<?php

// Incluindo a biblioteca FPDF e Code128
require('fpdf/fpdf.php');
require('fpdf/code128.php');
require_once('Libs/phpqrcode/qrlib.php');

$cpf = $_COOKIE['usuario'];

include "conexao_validar.php";
$pdo->exec("SET NAMES utf8");
$pdo->exec("SET character_set_connection=utf8");
$pdo->exec("SET character_set_client=utf8");
$pdo->exec("SET character_set_results=utf8");
include "valida/verifica_autenticacao.php";
$usuario = $cpf;
$texto = strtoupper($_REQUEST['texto']);

// Classe PDF personalizada para incluir código de barras
class PDF extends PDF_Code128 {

    // Função para criar o código de barras
    public function Barcode($x, $y, $code, $width = 1.5, $height = 10) {
        $this->Code128($x, $y, $code, $width * 10, $height);
    }

}

$pdf = new PDF();
$pdf->AddPage();

//$id = $_REQUEST['id']; // ID do registro desejado
setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese');
$data = new DateTime();
// Consulta usando Prepared Statement

$sql1 = "SELECT * from usuarios WHERE usuario = :usuario";
$stmt = $pdo->prepare($sql1);
$stmt->execute([':usuario' => $usuario]);
$num_rows = $stmt->rowCount();

if ($num_rows == 0) {
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
          <script type='text/javascript'>
              alert('Usuário não cadastrado!');
          </script>";
    exit;
}


$yInicial = $pdf->GetY();
$pdf->SetFont('Times', 'B', 20);

// Gera QR Code
$tempDir = dirname(__FILE__) . "/temp";
if (!file_exists($tempDir)) {
    mkdir($tempDir, 0777, true);
}
$host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parts = explode('/', trim($path, '/'));
$firstPart = isset($parts[0]) ? $parts[0] : '';

// Remove acentos e caracteres inválidos para QR Motorola
$texto_limpo = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
$texto_limpo = preg_replace('/[^A-Za-z0-9\-_]/', '', $texto_limpo);
$texto_limpo = trim($texto_limpo);

// Monta URL final SEM caracteres estranhos
//$url = "https://www.1portodos.com.br/qrcode_app/leitorqrcode.php?local=".$texto_limpo;
$url = $host . "/" . $firstPart . "/leitorqrcode.php?local=".$texto_limpo;;

// Remove possíveis BOMs
$url = preg_replace('/\x{FEFF}/u', '', $url);

// Largura máxima permitida (margem de 20 px = 10 de cada lado)
$maxWidth = $pdf->GetPageWidth() - 20;
// Fonte base
$fontSize = 20;
$pdf->SetFont('Times', 'B', $fontSize);

// Diminui até caber
// ---------- Função para dividir texto em 2 linhas equilibradas ----------
function dividirEmDuasLinhas($texto) {
    $palavras = explode(" ", $texto);
    $totalPalavras = count($palavras);

    if ($totalPalavras <= 2) {
        return [$texto, ""]; // Não divide
    }

    // Melhor ponto de quebra (aproximadamente no meio)
    $meio = intval($totalPalavras / 2);

    $linha1 = implode(" ", array_slice($palavras, 0, $meio));
    $linha2 = implode(" ", array_slice($palavras, $meio));

    return [$linha1, $linha2];
}

// Divide em duas linhas
list($linha1, $linha2) = dividirEmDuasLinhas($empresa);

// ---------- Ajusta o tamanho da fonte para caber nas duas linhas ----------
while (
    ($pdf->GetStringWidth(utf8_decode($linha1)) > $maxWidth ||
     $pdf->GetStringWidth(utf8_decode($linha2)) > $maxWidth)
    && $fontSize > 8
) {
    $fontSize--;
    $pdf->SetFont('Times', 'B', $fontSize);
}

$linha1 = 'Residencial Village Thermas das Caldas';
// ---------- Impressão das duas linhas ----------
$pdf->Ln(35);
$pdf->Cell(0, 10, utf8_decode($linha1), 0, 1, 'C');
$pdf->Cell(0, 10, utf8_decode($linha2), 0, 1, 'C');


$qrContent = $url;
$arquivoQR = $tempDir . "/qr_" . uniqid() . ".png";
// Gerar QRCode — com ECC H e pixel grande (fix Motorola)
QRcode::png($qrContent, $arquivoQR, QR_ECLEVEL_H, 8, 1);
//QRcode::png($qrContent, $arquivoQR, 'H', 4, 2);

// === DIMENSÕES DA PÁGINA ===
$larguraPagina = $pdf->GetPageWidth();
$alturaPagina = $pdf->GetPageHeight();

// === TAMANHO DO QR CODE (80% da largura da página) ===
$larguraQR = $larguraPagina * 0.75;   // 80% da página
$alturaQR = $larguraQR;              // quadrado
// === CENTRALIZAÇÃO HORIZONTAL ===
$xQR = ($larguraPagina - $larguraQR) / 2;

// === CENTRALIZAÇÃO VERTICAL ===
$yQR = ($alturaPagina - $alturaQR) / 2;

// === INSERE O QR CODE CENTRALIZADO ===
$pdf->Image($arquivoQR, $xQR, $yQR, $larguraQR, $alturaQR);
unlink($arquivoQR);
$pdf->Ln(180);

//    $pdf->AddPage(); // Nova página
$pdf->SetFont('Times', 'B', 18);
// Usando SetXY para definir a posição do texto no topo à direita
//    $pdf->SetXY(0, 10);  // Posição X = 150 (ajuste conforme necessário), Posição Y = 10 (mesma altura da imagem)
// Texto alinhado à direita
$pdf->Ln(5);
$pdf->Cell(0, 10, utf8_decode($texto), 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Times', 'B', 11);

$pdfOutputPath = 'qrCode.pdf';
$pdf->Output('F', $pdfOutputPath);

// Exibindo o PDF no navegador
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="' . $pdfOutputPath . '"');
readfile($pdfOutputPath);
//}
// Funções auxiliares
?>
