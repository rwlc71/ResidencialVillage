<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */


// Incluindo a biblioteca FPDF
require('fpdf/fpdf.php');


include "conexao.php";
include "valida/verifica_autenticacao.php";

$id = $_REQUEST['id']; // ID do registro desejado
setlocale(LC_TIME, 'pt_BR.UTF-8', 'portuguese');
$data = new DateTime();

$sql = "SELECT loc.*, uni.*, prop.* FROM locacao loc "
        . "JOIN proprietario prop ON loc.id_proprietario = prop.id_proprietario"
        . " JOIN unidade uni on loc.id_unidade = uni.id_unidade "
        . " WHERE loc.id_locacao = '" . $id . "' ";
//echo($sql);
//exit();
$filtro = mysql_query($sql);
$num_rows = mysql_num_rows($filtro);
while ($ln = mysql_fetch_array($filtro)) {
//    echo (var_dump($ln));
    $cpf = $ln['CPF'];
    $tamanho = strlen($cpf);
    if ($tamanho > 11) {
        $cpf = mask($cpf, '##.###.###/####-##');
    } else {
        $cpf = mask($cpf, '###.###.###-##');
    }

    $ln['telefone'] = mask($ln['telefone'], '(##) #####-#####');

//    $dt_entrada = new DateTime($ln['dt_entrada']);
//    $dt_saida = new DateTime($ln['dt_saida']);

    date_default_timezone_set('America/Sao_Paulo');
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
    $dt_entrada = strftime('%d de %B de %Y', strtotime($ln['dt_entrada'])); // Saída: 01 de novembro de 2024
    $dt_saida = strftime('%d de %B de %Y', strtotime($ln['dt_saida'])); // Saída: 01 de novembro de 2024
//    exit();
//
//
//
//    echo($dt_entrada);
//    echo($dt_saida);
//    echo(var_dump($ln));
//    exit();
// Criando um novo documento PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->Image('./images/logo1.jpg', 10, 15, 30);  // Exemplo: 30 de largura
    // Definindo a posição do texto alinhado à direita
    $pdf->SetFont('Times', 'B', 13);

// Usando SetXY para definir a posição do texto no topo à direita
//    $pdf->SetXY(0, 10);  // Posição X = 150 (ajuste conforme necessário), Posição Y = 10 (mesma altura da imagem)
// Texto alinhado à direita
    $pdf->Ln(10);
    $pdf->Cell(0, 10, utf8_decode('AUTORIZAÇÃO DE HOSPEDAGEM'), 0, 1, 'R');

    $pdf->Ln(6);
    // Definindo a fonte e o layout
    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(0, 5, utf8_decode('RESIDENCIAL VILLAGE THERMAS DAS CALDAS'), 0, 1, 'L');
    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(0, 5, utf8_decode('Caldas Novas - Goiás'), 0, 1, 'L');
    $pdf->Ln(6);

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(13, 6, 'CASA:'); // Texto "Casa" em negrito
    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(8, 6, utf8_decode($ln['numero_etapa']));  // Número da casa em fonte normal
    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(16, 6, 'ETAPA:');
    $pdf->SetFont('Times', '', 11);
    $etapaLimpa = strstr($ln['etapa'], '-', true);
    $pdf->Cell(38, 6, utf8_decode(strtoupper($etapaLimpa)));
//    $pdf->Cell(38, 6, utf8_decode(strtoupper($ln['etapa'])));
    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(33, 6, utf8_decode('PROPRIETÁRIO:'));
    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(15, 6, utf8_decode($ln['nome']));
    $pdf->Ln(6);

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(10, 6, utf8_decode('CPF:'));
    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(35, 6, utf8_decode($cpf));

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(17, 6, utf8_decode('Telefone:'));
    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(40, 6, utf8_decode($ln['telefone']));

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(10, 6, utf8_decode('Data:'));
    $pdf->SetFont('Times', '', 11);
    $dt_hoje = strtoupper($data->format('d') . ' de ' . strftime('%B') . ' de ' . $data->format('Y') . '.');
    $pdf->Cell(15, 6, utf8_decode($dt_hoje));

//    $pdf->SetFont('Times', 'B', 11);
//    $pdf->Cell(28, 10, utf8_decode('Endereço:'));
//    $pdf->SetFont('Times', '', 11);
//    $pdf->Cell(15, 10, utf8_decode(strtoupper($ln['endereco'])));
//    $pdf->Ln(6);

    $pdf->Ln(10);
    $pdf->SetFont('Times', '', 11);
    $texto = '          Autorizo as pessoas abaixo relacionadas, sob a responsabilidade do primeiro mencionado, a utilizar a casa de minha propriedade no período de ';
//    $dt_entrada = strtoupper($dt_entrada->format('d') . ' de ' . strftime('%B') . ' de ' . $dt_entrada->format('Y') . '');
//    $dt_saida = strtoupper($dt_saida->format('d') . ' de ' . strftime('%B') . ' de ' . $dt_saida->format('Y') . '.');
    $texto2 = strtoupper($dt_entrada . ' à ' . $dt_saida . '.');
    $pdf->MultiCell(0, 6, utf8_decode($texto . $texto2)); // O parâmetro 0 define a largura como toda a página
//    $pdf->Ln(6);

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(28, 10, utf8_decode('Usuários:'));
    $pdf->Ln(8);

    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(36, 6, utf8_decode('Nome do responsável:'));
    $pdf->SetFont('Times', '', 11);
    $texto_limitado = substr($ln['resp_locacao'], 0, 23);
    $pdf->Cell(55, 6, utf8_decode(strtoupper($texto_limitado)));

    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(15, 6, utf8_decode('CPF/RG:'));
    $pdf->SetFont('Times', '', 11);
    $texto_limitado = substr($ln['doc_identificacao_resp'], 0, 20);
    $pdf->Cell(40, 6, utf8_decode($texto_limitado));

    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(19, 6, utf8_decode('Vínculo:'));
    $pdf->SetFont('Times', '', 11);
    $texto_limitado = substr($ln['parentesco'], 0, 26);
    $pdf->Cell(44, 6, utf8_decode($texto_limitado));

    $pdf->Ln(6);

    $consulta2 = "SELECT * FROM hospede WHERE id_locacao = " . $id;
    $consulta2 = mysql_query($consulta2);
    $linha = 0;
    $pdf->SetFont('Times', '', 11);

    while ($ln_hospede = mysql_fetch_array($consulta2)) {
        $nomehospede = substr($ln_hospede['nome_hospede'], 0, 23);
        $doc_hospede = substr($ln_hospede['doc_hospede'], 0, 15);
        $parentesco_hospede = substr($ln_hospede['parentesco_hospede'], 0, 13);
//          $texto = '123456789012345678901234567890123456789012345678901234567890';
//         $parentesco_hospede = substr($texto, 0, 17);
        $linha = $linha + 1;
        $pdf->Cell(91, 5, $linha . '. Acompanhante: ' . strtoupper(utf8_decode($nomehospede)));
        $pdf->Cell(55, 5, 'CPF/RG: ' . strtoupper($doc_hospede));
        $pdf->Cell(55, 5, utf8_decode('Vínculo: ') . strtoupper(utf8_decode($parentesco_hospede)), 0, 1);
    }
    $linha = $linha + 1;
    $pdf->Ln(3);

    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(40, 8, 'Quantidade de pessoas: ');
    $pdf->SetFont('Times', '', 11);
    $pdf->Cell(80, 8, utf8_decode($linha) . ' pessoa(s).');  // Número da casa em fonte normal
    $pdf->Cell(55, 8, utf8_decode('Tipo de Unidade: ') . utf8_decode($ln['tipo_unidade']), 0, 1);

    $pdf->Ln(5);

    $pdf->SetFont('Times', '', 11);
    $texto3 = '          Os usuários acima nomeados comprometem-se a cumprir e a respeitar as Normas Internas e os Regulamentos do Condomínio.';
    $pdf->MultiCell(0, 6, utf8_decode($texto3)); // O parâmetro 0 define a largura como toda a página
// Assinaturas e Observações
    $pdf->Cell(0, 5, utf8_decode('________________________________'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('Assinatura do Proprietário'), 0, 1, 'C');
    $pdf->Ln(6);

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(0, 10, 'V. Termo de Compromisso no verso:', 0, 1);
    $pdf->SetFont('Times', '', 11);
    $pdf->MultiCell(0, 6, utf8_decode('1. O proprietário da Unidade assume civilmente a responsabilidade pelos atos dos usuários acima indicados;'));
    $pdf->MultiCell(0, 6, utf8_decode('2. Não serão aceitas autorizações assinadas que tragam lacunas e trechos em branco;'));
    $pdf->MultiCell(0, 6, utf8_decode('3. Somente as pessoas relacionadas na presente autorização terão acesso às dependências do condomínio;'));
    $pdf->MultiCell(0, 6, utf8_decode('4. Nos períodos de grande movimentação, como feriados prolongados e temporadas de férias, para cada Unidade do Condomínio será permitida a entrada de apenas 3 (três) veículos de usuários. Veículos extras deverão permanecer estacionados fora da área restrita da etapa e não poderão circular nas ruas internas. Esta medida não se estende aos proprietários;'));
    $pdf->MultiCell(0, 6, utf8_decode('5. Até o quarto grau de parentesco do proprietário não é cobrado convite para a área de lazer, no entanto os demais convidados/amigos sem a presença do mesmo assim como casa de aluguel são cobrados R$ 30,00 por pessoa e por dia, exceto pessoas acima de 60 anos ou abaixo de 12 anos.'));
    $pdf->Ln(6);

    $pdf->Cell(0, 5, utf8_decode('________________________________'), 0, 1, 'C');
    $pdf->Cell(0, 4, utf8_decode('Assinatura do Proprietário'), 0, 1, 'C');
    $pdf->Ln(20);

    $pdf->AddPage(); // Nova página
    $pdf->SetFont('Times', 'B', 13);

// Usando SetXY para definir a posição do texto no topo à direita
//    $pdf->SetXY(0, 10);  // Posição X = 150 (ajuste conforme necessário), Posição Y = 10 (mesma altura da imagem)
// Texto alinhado à direita
    $pdf->Ln(10);
    $pdf->Cell(0, 10, utf8_decode('AUTORIZAÇÃO DE HOSPEDAGEM'), 0, 1, 'R');
    $pdf->Ln(10);
    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(0, 10, utf8_decode('TERMO DE COMPROMISSO'), 0, 1, 'C');
    $pdf->SetFont('Times', '', 11);
    $pdf->MultiCell(0, 6, utf8_decode('	   Ao assinar o presente termo, atesto que tomei conhecimento das disposições internas vigentes e abaixo relacionadas que regulam o funcionamento do Residencial Village Thermas das Caldas, as quais comprometo-me a acatar e a fazer respeitar.'));
    $pdf->Ln(6);

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(40, 10, utf8_decode('Caldas Novas - GO, '));
    $pdf->SetFont('Times', '', 11);
    $dt_hoje = strtoupper($data->format('d') . ' de ' . strftime('%B') . ' de ' . $data->format('Y') . '.');
    $pdf->Cell(15, 10, utf8_decode($dt_hoje));
    $pdf->Ln(20);

    // Assinaturas e Observações
    $pdf->Cell(0, 5, utf8_decode('________________________________'), 0, 1, 'C');
    $pdf->Cell(0, 5, utf8_decode('Assinatura do Titular           '), 0, 1, 'C');
    $pdf->Ln(20);

    $pdf->SetFont('Times', 'B', 11);
    $pdf->Cell(0, 10, utf8_decode('O titular que, em nome dos usuários acompanhantes, a esta subscreve compromete-se à:'), 0, 1);
    $pdf->Ln(5);

    $pdf->SetFont('Times', '', 11);
    $pdf->MultiCell(0, 5, utf8_decode('1. Acondicionar todo o lixo residencial em invólucros plásticos separados, de acordo com sua natureza (papéis, vidros, plásticos e restos orgânicos), e a depositá-los no local a isso destinado (container).'));
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, utf8_decode('2. Manter os sons emitidos na residência em níveis adequados e restrito ao ambiente interno, respeitando o silêncio após às 22:00 horas.'));
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, utf8_decode('3. Conduzir veículos automotores que rodam no interior do condomínio sempre em velocidade compatível com o local (velocidade máxima: 20 Km/hora).'));
    $pdf->Ln(5);
    $pdf->MultiCell(0, 5, utf8_decode('4. Comportar-se recatadamente em público e manter-se segundo as normas aceitas pela comunidade.'));
    $pdf->Ln(15);
//    $pdf->MultiCell(0, 5, utf8_decode('5. Freqüentar o clube do Condomínio sempre portando convites fornecidos pelo proprietário da casa ocupada.'));

    $pdf->SetFont('Times', 'B', 8);
    $pdf->Cell(0, 15, utf8_decode(' ') . utf8_decode($ln['codvalidacao']), 0, 0, 'R'); // Código centralizado no rodapé
    

// Gerando o arquivo PDF
    $pdfOutputPath = 'autorizacao_hospedagem.pdf';
    $pdf->Output('F', $pdfOutputPath);

// Exibindo o PDF no navegador
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $pdfOutputPath . '"');
    readfile($pdfOutputPath);
}

/////////////////////////
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

function formatar($tipo = "", $string, $size = 10) {
    $string = ereg_replace("[^0-9]", "", $string);

    switch ($tipo) {
        case 'fone':
            if ($size === 10) {
                $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 4)
                        . '-' . substr($tipo, 6);
            } else {
                if ($size === 11) {
                    $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 5)
                            . '-' . substr($tipo, 7);
                }
            }
            break;

        case 'cep':
            $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);
            break;

        case 'cpf':
            $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) .
                    '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
            break;

        case 'cnpj':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) .
                    '.' . substr($string, 5, 3) . '/' .
                    substr($string, 8, 4) . '-' . substr($string, 12, 2);
            break;

        case 'rg':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) .
                    '.' . substr($string, 5, 3);
            break;

        default:
            $string = 'É ncessário definir um tipo(fone, cep, cpg, cnpj, rg)';
            break;
    }
}
?>