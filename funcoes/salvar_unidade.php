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

$cpf = $_POST['cpf'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);

if ($cpf == '' && isset($_POST['proprietario']) && trim($_POST['proprietario']) != '') {
    $nomeInformado = mysql_real_escape_string(trim($_POST['proprietario']));
    $sqlNomeProp = mysql_query("SELECT * FROM proprietario WHERE nome = '" . $nomeInformado . "'");
    if (mysql_num_rows($sqlNomeProp)) {
        $lnNomeProp = mysql_fetch_array($sqlNomeProp);
        $cpf = $lnNomeProp['CPF'];
        $cpf = str_replace(".", "", $cpf);
        $cpf = str_replace("-", "", $cpf);
        $cpf = str_replace("/", "", $cpf);
    }
}

//====================================

If ($_POST['Etapa'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo ETAPA de preenchimento obrigatório!  \");
      history.back(); 
     </script> ";
    return die;
}
If ($_POST['nr_etapa'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo UNIDADE de preenchimento obrigatório!  \");
      history.back(); 
     </script> ";
    return die;
}
If ($_POST['tipo_unidade'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo TIPO DE UNIDADE de preenchimento obrigatório!  \");
      history.back(); 
     </script> ";
    return die;
}
If ($_POST['qtde_quarto'] == "") {
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
If (ctype_digit($_POST['nr_etapa']) == '') {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo UNIDADE deve ser numérico!  \");
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
            alert(\"Refaça a transação: para aluguéis é necessário inserir o contrato do aluguél!\");
            history.back(); 
           </script> ";
        return die;
    }
}

if ($_POST['tela'] == 'adm') {
    if ($comprovante == '') {
        echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
            alert(\" É obrigatório a inserção do documento de titularidade!\");
            history.back(); 
           </script> ";
        return die;
    }
}
// Rotina de verificação da quantidade de unidades por etapa
$totalPiangueiras = 94;
$totalOrquideas = 99;
$totalBougainvilles = 106;
$totalAzaleias = 114;
$totalGardenias = 78;
$totalJacarandas = 58;
if ($_POST['Etapa'] == 'Orquídeas - OR') {
    if ($_POST['nr_etapa'] > $totalOrquideas) {
        echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
            alert(\"Refaça a transação:a numeração para esta etapa é de  1 à 99!\");
            history.back(); 
           </script> ";
        return die;
    }
}
if ($_POST['Etapa'] == 'Jacarandás - JAC') {
    if ($_POST['nr_etapa'] > $totalJacarandas) {
        echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
            alert(\"Refaça a transação:a numeração para esta etapa é de  1 à 58!\");
            history.back(); 
           </script> ";
        return die;
    }
}
if ($_POST['Etapa'] == 'Gardênia - GA') {
    if ($_POST['nr_etapa'] > $totalGardenias) {
        echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
            alert(\"Refaça a transação:a numeração para esta etapa é de  1 à 78!\");
            history.back(); 
           </script> ";
        return die;
    }
}
if ($_POST['Etapa'] == 'Azaléia - AZ') {
    if ($_POST['nr_etapa'] > $totalAzaleias) {
        echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
            alert(\"Refaça a transação:a numeração para esta etapa é de  1 à 114!\");
            history.back(); 
           </script> ";
        return die;
    }
}
if ($_POST['Etapa'] == 'Bougainville - BO') {
    if ($_POST['nr_etapa'] > $totalBougainvilles) {
        echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
            alert(\"Refaça a transação:a numeração para esta etapa é de  1 à 106!\");
            history.back(); 
           </script> ";
        return die;
    }
}
if ($_POST['Etapa'] == 'Pitangueiras - PIT') {
    if ($_POST['nr_etapa'] > $totalPiangueiras) {
        echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
            alert(\"Refaça a transação:a numeração para esta etapa é de  1 à 94!\");
            history.back(); 
           </script> ";
        return die;
    }
}

//if ($_POST['tipo_unidade'] == 'Locação Temporária') {
//    if ($_POST['capacidade'] > 10) {
//        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
//      <script type=\"text/javascript\">
//      alert(\"Refaça a transação: capacidade máxima para este tipo de unidade é de até 10 pessoas, conforme Art. 46º do Regimento Interno!\");
//      </script>";
//        return die;
//    }
//}
//=======================
If ($comprovante != '') {
    if (($_FILES['comprovante']['size'] >= 950000) || ($_FILES['comprovante']['size'] == 0)) {
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

if ($_POST['botao'] == "Cadastrar unidade" || $_POST['botao'] == "Vincular unidade") {

    $tipoAcessoLogado = isset($_COOKIE['tipo_acesso']) ? $_COOKIE['tipo_acesso'] : '';
    $ehAdmMaster = ($tipoAcessoLogado === 'adm' || $tipoAcessoLogado === 'sup' || $tipoAcessoLogado === 'master');
    if ($_POST['botao'] == "Cadastrar unidade" && !$ehAdmMaster) {
        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
                    <script type=\"text/javascript\">
                    alert(\"Somente usuários com perfil Administrativo ou Master podem cadastrar unidade!\");
                    </script>
                    ";
        return die;
    }

    //================================= Gravar no banco - Inclusao 
    $sql = ("SELECT * FROM proprietario WHERE CPF = '$cpf'");
    $sql = mysql_query($sql);

    $ln = mysql_fetch_array($sql);
    $id_proprietario = $ln['id_proprietario'];
    $nr_etapa_formatado = str_pad($_POST['nr_etapa'], 2, '0', STR_PAD_LEFT); // Complementa com zeros à esquerda até ter 2 dígitos
    $verificaEtapa = $_POST['Etapa'];

    if (mysql_num_rows($sql)) {
        $sql1 = "SELECT * FROM unidade WHERE etapa = '$verificaEtapa' and numero_etapa = '$nr_etapa_formatado'";
        $sql1 = mysql_query($sql1);
        if (mysql_num_rows($sql1) == true) {
            $ln1 = mysql_fetch_array($sql1);
            $id_proprietario_vigente = $ln1['id_proprietario'];

            $sql4 = ("SELECT * FROM proprietario WHERE id_proprietario = '$id_proprietario_vigente'");
            $sql4 = mysql_query($sql4);
            $ln4 = mysql_fetch_array($sql4);
            $nome_proprietario_vigente = $ln4['nome'];

            echo "<meta http-equiv='refresh' content='0; '>
               <script type=\"text/javascript\">
                alert(\"Refaça a transação: Unidade $verificaEtapa - $nr_etapa_formatado já cadastrada!\");
                alert(\"Em caso de dúvidas, procure a administração e atualize seus dados cadastrais!\");
                history.back(); 
               </script> ";
            return die;
        } else {
            $sql2 = "INSERT INTO unidade (id_unidade, id_proprietario, etapa, numero_etapa, tipo_unidade, qtde_quartos, capacidade, comprovante_titularidade)
            VALUES (NULL,'" . $id_proprietario . "','" . $_POST['Etapa'] . "','" . $nr_etapa_formatado . "','" . $_POST['tipo_unidade'] . "',"
                    . "'" . $_POST['qtde_quarto'] . "','" . $_POST['capacidade'] . "','" . $comprovante . "')";

            mysql_query($sql2);
            If ($comprovante != '') {
                move_uploaded_file($_FILES['comprovante']['tmp_name'], "../documentostitularidade/" . $comprovante);
            }
            if ($_POST['tela'] == 'adm') {
                echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade_adm.php'>
                    <script type=\"text/javascript\">
                    alert(\"Unidade cadastrada com sucesso!  \");
                    </script>
                    ";
                return die;
            }

            echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
                    <script type=\"text/javascript\">
                    alert(\"Unidade cadastrada com sucesso!  \");
                    </script>
                    ";
            return die;
        }
    }
} else {

    //================================= Gravar no banco - Alteração
    $sql = ("SELECT * FROM proprietario WHERE CPF = '$cpf'");
    $sql = mysql_query($sql);

    $ln = mysql_fetch_array($sql);
    $id_proprietario = $ln['id_proprietario'];
    $nr_etapa_formatado = str_pad($_POST['nr_etapa'], 2, '0', STR_PAD_LEFT); // Complementa com zeros à esquerda até ter 5 dígitos
    $verificaEtapa = $_POST['Etapa'];

    if (mysql_num_rows($sql)) {
        $sql1 = "SELECT * FROM unidade WHERE etapa = '$verificaEtapa' and numero_etapa = '$nr_etapa_formatado'";
        $sql1 = mysql_query($sql1);
        if (mysql_num_rows($sql1) == true) {
            $ln1 = mysql_fetch_array($sql1);
            $id_proprietario_vigente = $ln1['id_proprietario'];

            if ($id_proprietario === $id_proprietario_vigente) {
                // Update
                $sql = "SELECT * FROM proprietario WHERE CPF = '$cpf'";
                $sql = mysql_query("SELECT * FROM proprietario WHERE CPF = '$cpf'");
                $ln = mysql_fetch_array($sql);
                if (mysql_num_rows($sql)) { //Alteração
                    $camposUpdateUnidade = "etapa = '" . $verificaEtapa . "',
                            numero_etapa = '" . $nr_etapa_formatado . "',
                            tipo_unidade = '" . $_POST['tipo_unidade'] . "',
                            qtde_quartos = '" . $_POST['qtde_quarto'] . "',
                            capacidade = '" . $_POST['capacidade'] . "'";
                    if ($comprovante != '') {
                        $camposUpdateUnidade .= ", comprovante_titularidade = '" . $comprovante . "'";
                    }
                    $sql15 = "UPDATE unidade SET " . $camposUpdateUnidade . "
                            WHERE id_unidade = '" . $ln1['id_unidade'] . "'";
                    $sql5 = mysql_query($sql15);

                    If ($comprovante != '') {
                        move_uploaded_file($_FILES['comprovante']['tmp_name'], "../documentostitularidade/" . $comprovante);
                    }

                    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
                    <script type=\"text/javascript\">
                    alert(\"Atualização de unidade realizada com sucesso!  \");
                    </script>
                              ";
                    return die;
                }
            } else {
                echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                     alert(\"Refaça a transação: Unidade $verificaEtapa - $nr_etapa_formatado já está cadastrada para outro proprietário!\");
                     alert(\"Em caso de dúvidas, procure a administração e atualize seus dados cadastrais!\");
                     history.back(); 
                    </script> ";
                return die;
            }
        } else {
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Unidade $verificaEtapa - $nr_etapa_formatado não encontrada para alteração!\");
                    history.back();
                    </script> ";
            return die;
        }
    }
}
?>
