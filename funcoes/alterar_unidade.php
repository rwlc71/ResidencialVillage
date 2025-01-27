<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
include "senha.php";

$comprovante = str_replace($troca, $recebe, $_FILES['comprovante']['name']);
//echo(var_dump($_POST));
//echo($comprovante);
//echo('etapa ' . ctype_digit($_POST['nr_etapa']));
//echo('capacidade ' . ctype_digit($_POST['capacidade']));
//exit();
$id_unidade = $_POST['id_unidade'];
$tela = '';
if ($_POST['tela']) {
    $tela = $_POST['tela'];
}
$cpf = $_POST['cpf'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);

//====================================
$retornar = 'cadastra_unidade.php';

if ($tela == 'adm') {
    $retornar = 'consulta_unidade_adm.php';
}

If ($_POST['tipo_unidade'] == "") {
//    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo TIPO DE UNIDADE de preenchimento obrigatório!  \");
      history.back(); 
      </script> ";
    return die;
}
If ($_POST['qtde_quarto'] == "") {
//    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo QUANTIDADE DE QUARTOS de preenchimento obrigatório!  \");
      history.back(); 
      </script> ";
    return die;
}
If ($_POST['capacidade'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo CAPACIDADE DO IMÓVEL de preenchimento obrigatório!  \");
      history.back(); 
     </script> ";
    return die;
}

If (ctype_digit($_POST['qtde_quarto']) == '') {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo QUANTIDADE DE QUARTOS deve ser numérico!  \");
      history.back(); 
     </script> ";
    return die;
}
If (ctype_digit($_POST['capacidade']) == '') {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo CAPACIDADE DO IMÓVEL deve ser numérico!  \");
      history.back(); 
     </script> ";
    return die;
}

//Rotina de verificação entre quantidade de quartos e capacidade.
// limitada a 4 pessoas por quarto
$limite = ($_POST['qtde_quarto'] * 4);
if ($limite < $_POST['capacidade']) {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Refaça a transação: capacidade máxima da unidade é de até $limite pessoas!\");
      history.back(); 
     </script> ";
    return die;
}

if ($_POST['tipo_unidade'] == 'Locação Regular (+90dias)') {
    if ($comprovante == '') {
        echo "<meta http-equiv='refresh' content='0; '>
            <script type=\"text/javascript\">
            alert(\"Refaça a transação: para aluguéis é necessário inserir o contrato do aluguel!\");
            history.back(); 
           </script> ";
        return die;
    }
}


//=======================
If ($comprovante != '') {
    if (($_FILES['comprovante']['size'] >= 1000000) || ($_FILES['comprovante']['size'] == 0)) {
        echo "<meta http-equiv='refresh' content='0; '>
            <script type=\"text/javascript\">
            alert(\"Tamanho máximo do arquivo excedido! Máximo de 1Mb\");
            history.back(); 
           </script> ";
        return die;
    }
    $extensao = strrchr($_FILES['comprovante']['name'], '.');
    $dataHoraAtual = date('Y_m_d_H_i_s');
    $comprovante = $cpf . "_TIT_" . $dataHoraAtual . $extensao;
}

//if ($comprovante == "") {
//    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
//                <script type=\"text/javascript\">
//                alert(\"O DOCUMENTO DE TITULARIDADE DO IMÓVEL deve ser selecionado!  \");
//                </script>
//                ";
//    return die;
//}
//=============================================
if ($_POST['botao'] == "Salvar Alterações") {
    $sql = ("SELECT * FROM unidade WHERE id_unidade = '$id_unidade'");
    $sql = mysql_query($sql);
    $ln = mysql_fetch_array($sql);
    if (mysql_num_rows($sql)) { //Alteração
        $sql1 = "UPDATE unidade SET tipo_unidade = '" . $_POST['tipo_unidade'] . "',
                    qtde_quartos = '" . $_POST['qtde_quarto'] . "',
                    capacidade = '" . $_POST['capacidade'] . "',
                    comprovante_titularidade = '" . $comprovante . "'
                    WHERE id_unidade = '" . $id_unidade . "'";
//            echo($sql1);
//            exit();

        $result = mysql_query($sql1);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao atualizar dados de Unidadees: $erro   \");
                    history.back(); 
                    </script> ";
            return die;
        } else {
            If ($comprovante != '') {
                move_uploaded_file($_FILES['comprovante']['tmp_name'], "../documentostitularidade/" . $comprovante);
            }
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Unidade alterada com sucesso!  \");
                    history.back(); 
                    </script>";
            return die;
        }
    }
} else {
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Funcionalidade não encontrada!  \");
                    history.back(); 
                    </script>";
    return die;
}
?>
