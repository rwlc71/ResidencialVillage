
<?php

session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
//include "../valida/verifica_autenticacao.php";
include "senha.php";
//echo(var_dump($_POST));
//echo(var_dump($_COOKIE));
//echo(strlen($_POST['nome']));


$autor = $_POST['autor'];

If (trim($_POST['nome']) == '' || strlen($_POST['nome']) == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Nome deve ser preenchido!']);
    return;
}
If (trim($_POST['destino']) == '') {
    echo json_encode(['status' => 'error', 'message' => 'Destino deve ser preenchido!']);
    return;
}
If (trim($_POST['dt_entrada']) == '') {
    echo json_encode(['status' => 'error', 'message' => 'Data de entrada deve ser preenchida!']);
    return;
}
If (trim($_POST['hr_entrada']) == '') {
    echo json_encode(['status' => 'error', 'message' => 'Hora de entrada deve ser preenchida!']);
    return;
}

$dthora_entrada = DateTime::createFromFormat('d/m/Y H:i', trim($_POST['dt_entrada']) . ' ' . trim($_POST['hr_entrada']))->format('Y-m-d H:i:s');
$dthora_saida = NULL;
If (trim($_POST['dt_saida']) != '') {
    If (trim($_POST['hr_saida']) == '') {
        echo json_encode(['status' => 'error', 'message' => 'Hora de saida deve ser preenchida!']);
        return;
    } else {
        $dthora_saida = DateTime::createFromFormat('d/m/Y H:i', trim($_POST['dt_saida']) . ' ' . trim($_POST['hr_saida']))->format('Y-m-d H:i:s');
    }
}
//exit();
//================================= Gravar no banco - Inclusao de hospedes
If ($_POST['id_entrada'] != '') {
    $sql1 = "UPDATE entradas SET Nome = '" . $_POST['nome'] . "',
                    identificacao = '" . $_POST['identificacao'] . "',
                    destino = '" . $_POST['destino'] . "',
                    dthora_entrada = '" . $dthora_entrada . "',
                    dthora_saida = '" . $dthora_saida . "',
                    contato = '" . $_POST['contato'] . "',
                    anotacoes = '" . $_POST['anotacoes'] . "'
                    WHERE id_entrada = '" . $_POST['id_entrada'] . "'";
//    echo('update <p>');
//    echo($sql1);
//    exit();
    $result = mysql_query($sql1);
    if (!$result) {
        $erro = mysql_error();
        $gravou = false;
        echo json_encode(['status' => 'error', 'message' => 'Falha na alteração do registro: ' . $erro]);
        return;
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Registro alterado com sucesso!']);
        return;
    }
} else {
    $gravou = false;
    $sql2 = "INSERT INTO entradas (id_entrada, Nome, identificacao, destino, dthora_entrada, dthora_saida, contato, anotacoes, autor) 
         VALUES (NULL, '" . $_POST['nome'] . "', '" . $_POST['identificacao'] . "', '" . $_POST['destino'] . "', 
         '" . $dthora_entrada . "', " . ($dthora_saida === NULL ? "NULL" : "'$dthora_saida'") . ", '" . $_POST['contato'] . "', '" . $_POST['anotacoes'] . "', '" . $autor . "')";

//    echo($sql2 . '<p>');
//    exit();
    $result = mysql_query($sql2);
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Registro salvo com sucesso!']);
        return;
    } else {
        $erro = mysql_error();
        echo json_encode(['status' => 'error', 'message' => 'Falha na gravação do registro: ' . $erro]);
        return;
    }
}
?>
