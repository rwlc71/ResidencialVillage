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

// Cabeçalho para resposta JSON
header('Content-Type: application/json');
$dadosRecebidos = json_decode(file_get_contents('php://input'), true);
if (isset($dadosRecebidos['nome'])) {
    $dadosRecebidos = $dadosRecebidos['nome'];
}
try {
    // Conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recebendo o valor do fornecedor
    $nome = isset($_REQUEST['nome']) ? trim($_REQUEST['nome']) : '';
    if ($dadosRecebidos) {
        $nome = $dadosRecebidos;
    }

    if (!empty($nome)) {
        // Consulta ao banco de dados
        $sql = "SELECT DISTINCT nome, id_proprietario, email, CPF FROM proprietario WHERE nome like '%" . $nome . "%'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($dados) {
                $id_proprietario = $dados['id_proprietario'];
                $cpfProp = $dados['CPF'];
                
                $email = $dados['email'];
                $sql1 = "SELECT * FROM unidade WHERE id_proprietario = '.$id_proprietario.'";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->bindParam(':id_proprietario', $id_proprietario, PDO::PARAM_INT);
                $stmt1->execute();

                if ($stmt1->rowCount() > 0) {
                    $unidades = [];

                    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                        $descEtapa = $row['etapa'];
                        $numero_etapa = $row['numero_etapa'];
                        $etapa = '';
                        switch ($row['etapa']) {
                            case 'Azaléia - AZ':
                                $etapa = 'Azaléia - Casa ' . $row['numero_etapa'];
                                break;
                            case 'Bougainville - BO':
                                $etapa = 'Bougainville - Casa ' . $row['numero_etapa'];
                                break;
                            case 'Gardênia - GA':
                                $etapa = 'Gardênia - Casa ' . $row['numero_etapa'];
                                ;
                                break;
                            case 'Jacarandás - JAC':
                                $etapa = 'Jacarandás - Casa ' . $row['numero_etapa'];
                                break;
                            case 'Orquídeas - OR':
                                $etapa = 'Orquídeas - Casa ' . $row['numero_etapa'];
                                break;
                            case 'Pitangueiras - PIT':
                                $etapa = 'Pitangueiras - Casa ' . $row['numero_etapa'];
                                break;
                        }
                        $unidades[] = [
                            'numero_etapa' => $row['numero_etapa'],
                            'etapa' => $row['etapa'],
                            'tipo_unidade' => $row['tipo_unidade'],
                            'id_unidade' => $row['id_unidade'],
                            'etapa_unidade' => $etapa,
                        ];
                    }

                    echo json_encode(array(
                        'success' => true,
                        'CPF' => $cpfProp,
                        'id_proprietario' => $id_proprietario,
                        'email' => $email, // Inclui as unidades no JSON
                        'unidade' => $numero_etapa, // Inclui as unidades no JSON
                        'etapa' => $descEtapa, // Inclui as unidades no JSON
                        'unidades' => $unidades, // Inclui as unidades no JSON
                    ));
                }
            } else {
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Proprietário não encontrado.'
                ));
            }
        }
    } else {
        echo json_encode(array(
            'success' => false,
            'message' => 'Campo nome vazio.'
        ));
    }
} catch (Exception $e) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Erro no servidor: ' . $e->getMessage()
    ));
}
