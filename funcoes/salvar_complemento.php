<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
//====================================
$id_unidade = $_POST['id_unidade'];
$id_audita = $_POST['id_audita'];
//echo(var_dump($_POST));
//If ($_POST['ocorrencia'] == "") {
//    echo "<meta http-equiv='refresh' content='0; URL= ../creserva.php?dado=$id_audita'>
//    <script type=\"text/javascript\">
//        alert(\"Campo de COMPLEMENTO deve ser preenchido!  \");
//      </script>
//                ";
//    return die;
//}
//exit();
date_default_timezone_set('America/Sao_Paulo');
$dataHoje = date('Y-m-d H:i:s');
$autor = $_COOKIE['nome_usuario'];

if ($_POST['botao'] == "Salvar") {
// =================Insere nas tabelas
    $dt_entrada_efetiva = $_POST['dt_entrada_efetiva'] . ' ' . $_POST['hr_entrada_efetiva'];
    $dt_saida_efetiva = $_POST['dt_saida_efetiva'] . ' ' . $_POST['hr_saida_efetiva'];

    $sql = "UPDATE audita 
        SET complementares = '" . $_POST['ocorrencia'] . "', 
            dt_entrada_efetiva = STR_TO_DATE('" . $dt_entrada_efetiva . "', '%d/%m/%Y %H:%i'), 
            dt_saida_efetiva = STR_TO_DATE('" . $dt_saida_efetiva . "', '%d/%m/%Y %H:%i'), 
            dt_ultima_alteracao = '" . $dataHoje . "',
            autor = '" . $autor . "'
        WHERE id_audita = '" . $id_audita . "'";
//       echo($sql);
//    exit();
    mysql_query($sql);

    $sql2 = "UPDATE locacao 
        SET complementares = '" . $_POST['ocorrencia'] . "', 
            dt_entrada_efetiva = STR_TO_DATE('" . $dt_entrada_efetiva . "', '%d/%m/%Y %H:%i'), 
            dt_saida_efetiva = STR_TO_DATE('" . $dt_saida_efetiva . "', '%d/%m/%Y %H:%i') 
        WHERE id_locacao = '" . $id_audita . "'";
//    echo($sql2);
//    exit();
    mysql_query($sql2);

//======================================        
    echo "<meta http-equiv='refresh' content='0; URL= ../creserva.php?dado=$id_audita'>
                    <script type=\"text/javascript\">
                    alert(\"Registro atualizado com sucesso!  \");
                    </script>
                    ";
    return die;
} else {
    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Funcionalidade não localizada!  \");
                    </script>
                    ";
    return die;
}
?>
