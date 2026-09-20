<?php
// Configuração do banco de dados
//$host = 'localhost';
//$dbname = 'bdcasasvillage';
//$user = 'root';
//$password = '';

$host = 'bdcasasvillage.mysql.dbaas.com.br';
$dbname = 'bdcasasvillage';
$user = 'bdcasasvillage';
$password = 'Village@2024';
include "../valida/verifica_autenticacao.php";

// $con = mysql_connect('bdcasasvillage.mysql.dbaas.com.br', 'bdcasasvillage', 'Village@2024');
// $db = mysql_select_db('bdcasasvillage', $con);
//  $con = mysql_connect("localhost", "root", "");
//  $db = mysql_select_db("bdcontrolesvillage", $con);
// Cabeçalho para resposta JSON
header('Content-Type: application/json');
$dadosRecebidos = json_decode(file_get_contents('php://input'), true);
if (isset($dadosRecebidos['fornecedor'])) {
    $dadosRecebidos = $dadosRecebidos['fornecedor'];
}
try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebendo o valor do fornecedor
    $fornecedor = isset($_POST['fornecedor']) ? trim($_POST['fornecedor']) : '';
    if ($dadosRecebidos){
        $fornecedor = $dadosRecebidos;
    }

    if (!empty($fornecedor)) {
        // Consulta ao banco de dados
        $sql = "SELECT DISTINCT nome, contato, identificacao FROM entradas WHERE nome like '%" . $fornecedor . "%'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fornecedor', $fornecedor, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(array(
                'success' => true,
                'id' => $dados['identificacao'],
                'contato' => $dados['contato']
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Fornecedor não encontrado.'
            ));
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Campo fornecedor vazio.'
        ));
    }
} catch (Exception $e) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Erro no servidor: ' . $e->getMessage()
    ));
}
