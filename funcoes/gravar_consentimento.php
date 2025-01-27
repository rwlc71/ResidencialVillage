
<?php

include "../conexao.php";
$cpf = $_REQUEST['dado'];
$sql = "SELECT * FROM proprietario WHERE CPF = '$cpf'";
$result = mysql_query($sql);

if ($result) {
    date_default_timezone_set('America/Sao_Paulo');
    $dataHoje = date('Y-m-d H:i:s');
    $lgpd = '1';
    $ln = mysql_fetch_array($result);
    $id_proprietario = $ln['id_proprietario'];
    $proprietario = $ln['id_proprietario'];
    $nome = strtoupper($ln['nome']);
    $tipo_acesso = strtoupper($ln['tipo_acesso']);

    $sql1 = "UPDATE proprietario SET lgpd = '" . $lgpd . "' ,
                 dthr_consentimento = '" . $dataHoje . "'
                 WHERE id_proprietario = '" . $id_proprietario . "'";

    $result2 = mysql_query($sql1);

    if ($result2) {
        $expire_time = time() + (60 * 60);
        $_SESSION['nome_usuario'] = $nome;
        $_SESSION['tipo_acesso'] = $tipo_acesso;
        $_SESSION['usuario'] = $$cpf;
        setcookie("usuario", $cpf, $expire_time, "/");
        setcookie("nome_usuario", $nome, $expire_time, "/");
        setcookie("tipo_acesso", $tipo_acesso, $expire_time, "/");
        echo json_encode(['status' => 'success', 'message' => 'Ação realizada com sucesso!']);
    } else {
        $erro = mysql_error();
        echo json_encode(['status' => 'error', 'message' => 'Ação falhou: ' . $erro]);
    }
} else {
    $erro = mysql_error();
    echo json_encode(['status' => 'error', 'message' => 'Ação falhou: ' . $erro]);
}
?>
