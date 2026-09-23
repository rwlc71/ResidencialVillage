<?php
$host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
// Configuração do banco de dados
if ($host == 'localhost') {
    $host = 'localhost';
    $dbname = 'bd_votavillage';
    $user = 'root';
    $password = '';
} else {
    $host = 'bd_votavillage.mysql.dbaas.com.br';
    $dbname = 'bd_votavillage';
    $user = 'bd_votavillage';
    $password = 'Village@2024';
}
// Cabeçalho para resposta JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Permite qualquer origem
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$dadosRecebidos = json_decode(file_get_contents('php://input'), true);

try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebendo o valor do fornecedor
    $usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
    $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
    if ($dadosRecebidos) {
        $usuario = $dadosRecebidos['usuario'];
        $senha = $dadosRecebidos['senha'];
    }
//    echo(var_dump($dadosRecebidos));
//    exit();

    if (!empty($dadosRecebidos['usuario'])) {
        // Consulta ao banco de dados


        $sql = "SELECT usu.*, prop.id_proprietario, prop.nome, prop.CPF FROM usuarios usu "
                . " JOIN proprietario prop ON usu.id_proprietario = prop.id_proprietario "
                . " WHERE usu.usuario = :usuario and  usu.senha = :senha";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(array(
                'success' => true,
                'CPF' => $dados['CPF'],
                'id_proprietario' => $dados['id_proprietario'],
                'perfil' => $dados['tipo_acesso'],
                'nome' => $dados['nome']
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'message' => 'Usuário ou senha inválido.'
            ));
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Campos vazios.'
        ));
    }
} catch (Exception $e) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Erro no servidor: ' . $e->getMessage()
    ));
}
