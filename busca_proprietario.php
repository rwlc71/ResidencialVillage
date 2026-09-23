<?php
include "conexao.php";
include "valida/verifica_autenticacao.php";

header('Content-Type: application/json');
$dadosRecebidos = json_decode(file_get_contents('php://input'), true);
if (isset($dadosRecebidos['nome'])) {
    $dadosRecebidos = $dadosRecebidos['nome'];
}

$nome = isset($_REQUEST['nome']) ? trim($_REQUEST['nome']) : '';
if ($dadosRecebidos) {
    $nome = $dadosRecebidos;
}

if (empty($nome)) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Campo nome vazio.'
    ));
    return;
}

$nomeEsc = mysql_real_escape_string($nome);
$sql = "SELECT DISTINCT nome, id_proprietario, email, CPF FROM proprietario WHERE nome like '%" . $nomeEsc . "%' LIMIT 1";
$result = mysql_query($sql);
if (!$result) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Erro no servidor: ' . mysql_error()
    ));
    return;
}

if (mysql_num_rows($result) == 0) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Proprietário não encontrado.'
    ));
    return;
}

$dados = mysql_fetch_array($result);
$id_proprietario = $dados['id_proprietario'];
$cpfProp = $dados['CPF'];
$email = $dados['email'];

$sql1 = "SELECT * FROM unidade WHERE id_proprietario = '" . mysql_real_escape_string($id_proprietario) . "'";
$stmt1 = mysql_query($sql1);
$unidades = array();
$numero_etapa = '';
$descEtapa = '';

if ($stmt1) {
    while ($row = mysql_fetch_array($stmt1)) {
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
            default:
                $etapa = $row['etapa'] . ' - Casa ' . $row['numero_etapa'];
                break;
        }
        $unidades[] = array(
            'numero_etapa' => $row['numero_etapa'],
            'etapa' => $row['etapa'],
            'tipo_unidade' => $row['tipo_unidade'],
            'id_unidade' => $row['id_unidade'],
            'etapa_unidade' => $etapa,
        );
    }
}

echo json_encode(array(
    'success' => true,
    'CPF' => $cpfProp,
    'id_proprietario' => $id_proprietario,
    'email' => $email,
    'unidade' => $numero_etapa,
    'etapa' => $descEtapa,
    'unidades' => $unidades,
));
?>
