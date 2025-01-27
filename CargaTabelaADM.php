<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
// Conexão com o banco de dados
$host = 'localhost';
$dbname = 'bdcasasvillage';
$user = 'root';
$password = '';


$host = 'bdcasasvillage.mysql.dbaas.com.br';
$dbname = 'bdcasasvillage';
$user = 'bdcasasvillage';
$password = 'Village@2024';

// $con = mysql_connect('bdcasasvillage.mysql.dbaas.com.br', 'bdcasasvillage', 'Village@2024');

// $con = mysql_connect('bdcasasvillage.mysql.dbaas.com.br', 'bdcasasvillage', 'Village@2024');
// $db = mysql_select_db('bdcasasvillage', $con);
//  $con = mysql_connect("localhost", "root", "");
//  $db = mysql_select_db("bdcontrolesvillage", $con);



try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// Incluindo a biblioteca PHPExcel
//require('./fpdf/fpdf.php');
require_once 'libs/Classes/PHPExcel.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $filePath = $file['tmp_name'];

        try {
            $excelReader = PHPExcel_IOFactory::createReaderForFile($filePath);
            $excelObj = $excelReader->load($filePath);
            $worksheet = $excelObj->getSheet(0); // Primeira aba
            $lastRow = $worksheet->getHighestRow();

            for ($row = 2; $row <= $lastRow; $row++) { // Ignorar o cabeçalho na linha 1
                echo "Processando linha: $row<br>";
                try {
                    $unidade = $worksheet->getCell("A$row")->getValue();
                    $tipo = $worksheet->getCell("B$row")->getValue();
                    $cpf = $worksheet->getCell("C$row")->getValue();
                    $nome = $worksheet->getCell("D$row")->getValue();
                    $endereco = $worksheet->getCell("E$row")->getValue();
                    $bairro = $worksheet->getCell("F$row")->getValue();
                    $cidade = $worksheet->getCell("G$row")->getValue();
                    $estado = $worksheet->getCell("H$row")->getValue(); // Estado diretamente da planilha
                    $cep = $worksheet->getCell("I$row")->getValue();
                    $email = $worksheet->getCell("J$row")->getValue();
                    $telefone = $worksheet->getCell("K$row")->getValue();

                    // Dividir unidade em etapa e número
                   
                    $etapa = 'Adminstrativo';
                    $numero = '1';
                    
                    // Remover pontuação do CPF
                    $cpfLimpo = preg_replace('/[\.-]/', '', $cpf);
                    if ($telefone == "") {
                        $telefone = "Nao informado";
                    }
                    if ($email == "") {
                        $email = "Nao informado";
                    }
                    $lgpd = '';
                    // Inserir dados na tabela proprietario
                    $stmt = $pdo->prepare(
                            "INSERT INTO proprietario (CPF, nome, endereco, cidade, estado, cep, email, telefone, lgpd) 
                         VALUES (:cpf, :nome, :endereco, :cidade, :estado, :cep, :email, :telefone, :lgpd)"
                    );
                    $stmt->execute([
                        ':cpf' => $cpfLimpo,
                        ':nome' => $nome,
                        ':endereco' => $endereco,
                        ':cidade' => $cidade,
                        ':estado' => $estado, // Usando estado diretamente da planilha
                        ':cep' => $cep,
                        ':email' => $email,
                        ':telefone' => $telefone,
                        ':lgpd' => $lgpd
                    ]);

//                    $idProprietario = $pdo->lastInsertId();
//                    $tipounidade = "Residência";
//                    $qtde_quartos = 2;
//                    $comprovante = "";
//                    $capacidade = "8";
//                    // Inserir dados na tabela unidade
//                    $stmt = $pdo->prepare(
//                            "INSERT INTO unidade (id_proprietario, tipo_unidade, etapa, numero_etapa, qtde_quartos, capacidade, comprovante_titularidade) 
//                         VALUES (:id_proprietario, :tipo, :etapa, :numero, :qtde, :capacidade, :comprova)"
//                    );
//                    $stmt->execute([
//                        ':id_proprietario' => $idProprietario,
//                        ':tipo' => $tipounidade,
//                        ':etapa' => $etapa,
//                        ':numero' => $numero,
//                        ':qtde' => $qtde_quartos,
//                        ':capacidade' => $capacidade,
//                        ':comprova' => $comprovante
//                    ]);
                    // Inserir dados na tabela usuarios
                    $stmt = $pdo->prepare(
                            "INSERT INTO usuarios (id_proprietario, usuario, senha, tipo_acesso) 
                         VALUES (:id_proprietario, :usuario, :senha, 'seg')"
                    );
                    $stmt->execute([
                        ':id_proprietario' => $idProprietario,
                        ':usuario' => $cpfLimpo, // Usando CPF limpo como usuário
                        ':senha' => $cpfLimpo
                    ]);
                } catch (Exception $e) {
                    echo "Erro na linha $row: " . $e->getMessage() . "<br>Registro: " . json_encode([
                        'unidade' => $unidade,
                        'tipo' => $tipounidade,
                        'cpf' => $cpf,
                        'nome' => $nome,
                        'endereco' => $endereco,
                        'bairro' => $bairro,
                        'cidade' => $cidade,
                        'estado' => $estado,
                        'cep' => $cep,
                        'email' => $email,
                        'telefone' => $telefone
                    ]) . "<br>";
                }
            }

            echo "Dados inseridos com sucesso!";
        } catch (Exception $e) {
            echo "Erro ao processar o arquivo: " . $e->getMessage();
        }
    } else {
        echo "Erro ao fazer upload do arquivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Upload de Excel</title>
    </head>
    <body>
        <h1>Upload de Arquivo Excel</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="excel_file" accept=".xls,.xlsx" required>
            <button type="submit">Enviar</button>
        </form>
    </body>
</html>
