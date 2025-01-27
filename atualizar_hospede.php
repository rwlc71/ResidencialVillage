<?php
include "conexao.php";

$id_hospede = $_POST['id_hospede'];
$nome = $_POST['nome'];
$doc = $_POST['doc'];
$parentesco = $_POST['parentesco'];

if (!empty($id_hospede) && !empty($nome) && !empty($doc) && !empty($parentesco)) {
    $query = "UPDATE hospede SET nome_hospede = '$nome', doc_hospede = '$doc', parentesco_hospede = '$parentesco' WHERE id_hospede = $id_hospede";
    $resultado = mysql_query($query);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atualizar hóspede.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
}
?>
