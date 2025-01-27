<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
include "senha.php";

//echo(var_dump($_POST));
//exit();
$cpf = $_POST['cpf'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);
$foto_dependente = str_replace($troca, $recebe, $_FILES['foto_dependente']['name']);

//====================================

If ($_POST['nome_dependente'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo NOME DO DEPENDENTE de preenchimento obrigatório!  \");
      history.back(); 
     </script> ";
    return die;
}
If ($_POST['Parentesco'] == "") {
    echo "<meta http-equiv='refresh' content='0;'>
      <script type=\"text/javascript\">
      alert(\"Campo GRAU DE PARENTESCO de preenchimento obrigatório!  \");
      history.back(); 
      </script>";
    return die;
}
If ($_POST['id_dependente'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo DOCUMENTO DE IDENTIFICAÇÃO de preenchimento obrigatório!  \");
      history.back(); 
      </script> ";
    return die;
}
If ($foto_dependente != '') {
    if (($_FILES['foto_dependente']['size'] >= 1100000) || ($_FILES['foto_dependente']['size'] == 0)) {
        echo "<meta http-equiv='refresh' content='0; '>
                     <script type=\"text/javascript\">
                     alert(\"Tamanho máximo permitido para arquivos é de até 1MB\");
                         history.back(); 
                     </script>    ";
        return die;
    }
}

if ($_POST['botao'] == "Cadastrar dependente") {
    //================================= Gravar no banco - Inclusao dependente
    $sql = ("SELECT * FROM proprietario WHERE CPF = '$cpf'");
    $sql = mysql_query($sql);
    $ln = mysql_fetch_array($sql);
    $id_proprietario = $ln['id_proprietario'];

    $ln = mysql_fetch_array($sql);
    if (mysql_num_rows($sql)) {
        $sql1 = "SELECT * FROM dependente WHERE id_proprietario = '$id_proprietario'";

        If ($foto_dependente != '') {
            $extensao = strrchr($_FILES['foto_dependente']['name'], '.');
            $dataHoraAtual = date('Y_m_d_H_i_s');
            $foto_dependente = $id_proprietario . "_DEP_" . $dataHoraAtual . $extensao;
        }

        $sql1 = mysql_query($sql1);
        if (mysql_num_rows($sql1) == true) {
            while ($ln1 = mysql_fetch_array($sql1)) {
                if (strtoupper($ln1['nome_dependente']) == strtoupper($_POST['nome_dependente'])) {
                    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_dependente.php'>
                    <script type=\"text/javascript\">
                    alert(\"Dependente já cadastrado!  \");
                    </script>
                    ";
                    return die;
                } else {
                    $sql2 = "INSERT INTO dependente (id_dependente, id_proprietario, nome_dependente, parentesco, doc_indentificacao_dependente,foto_dependente)
                      VALUES (NULL,'" . $id_proprietario . "','" . $_POST['nome_dependente'] . "','" . $_POST['Parentesco'] . "','" . $_POST['id_dependente'] . "','" . $foto_dependente . "')";
                    mysql_query($sql2);

                    If ($foto_dependente != '') {
                        move_uploaded_file($_FILES['foto_dependente']['tmp_name'], "../documentostitularidade/" . $foto_dependente);
                    }

                    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_dependente.php'>
                    <script type=\"text/javascript\">
                    alert(\"Dependente cadastrado com sucesso!  \");
                    </script>
                    ";
                    return die;
                }
            }
        } else {
            $sql2 = "INSERT INTO dependente (id_dependente, id_proprietario, nome_dependente, parentesco, doc_indentificacao_dependente,foto_dependente)
                      VALUES (NULL,'" . $id_proprietario . "','" . $_POST['nome_dependente'] . "','" . $_POST['Parentesco'] . "','" . $_POST['id_dependente'] . "','" . $foto_dependente . "')";
            mysql_query($sql2);

            If ($foto_dependente != '') {
                move_uploaded_file($_FILES['foto_dependente']['tmp_name'], "../documentostitularidade/" . $foto_dependente);
            }
            echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_dependente.php'>
                    <script type=\"text/javascript\">
                    alert(\"Dependente cadastrado com sucesso!  \");
                    </script>
                    ";
            return die;
        }
    }
}
?>
