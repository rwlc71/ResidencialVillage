<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";

include "senha.php";
$comprovante = str_replace($troca, $recebe, $_FILES['comprovante']['name']);

//echo(var_dump($_POST));
//exit();

$cpf = $_POST['cpf'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);

//====================================

If ($_POST['nome_pet'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_pet.php'>
    <script type=\"text/javascript\">
      alert(\"Campo NOME DO ANIMAL deve ser preenchido!  \");
      </script>
                ";
    return die;
}
If ($_POST['tipo_pet'] == "") {
    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_pet.php'>
      <script type=\"text/javascript\">
      alert(\"Campo TIPO DO ANIMAL deve ser preenchido!  \");
      </script>
                ";
    return die;
}

If ($comprovante != '') {
    if (($_FILES['comprovante']['size'] >= 1100000) || ($_FILES['comprovante']['size'] == 0)) {
        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_pet.php'>
                     <script type=\"text/javascript\">
                     alert(\"Tamanho máximo permitido para arquivos é de até 1MB\");
                     </script>
                    ";
        return die;
    }
 
}


if ($_POST['botao'] == "Cadastrar") {
    //================================= Gravar no banco - Inclusao pet
    $sql = ("SELECT * FROM proprietario WHERE CPF = '$cpf'");
    $sql = mysql_query($sql);
    $ln = mysql_fetch_array($sql);
    $id_proprietario = $ln['id_proprietario'];

    If ($comprovante != '') {
        $extensao = strrchr($_FILES['comprovante']['name'], '.');
        $dataHoraAtual = date('Y_m_d_H_i_s');
        $comprovante = $id_proprietario . "_PET_" . $dataHoraAtual . $extensao;
    }
    if (mysql_num_rows($sql)) {
        $sql2 = "INSERT INTO pets (id_pet, id_proprietario, nome_pet, tipo_pet, foto_pet)
            VALUES (NULL,'" . $id_proprietario . "','" . $_POST['nome_pet'] . "','" . $_POST['tipo_pet'] . "','" . $comprovante . "')";

        mysql_query($sql2);
        If ($comprovante != '') {
            move_uploaded_file($_FILES['comprovante']['tmp_name'], "../documentostitularidade/" . $comprovante);
        }
        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_pet.php'>
                    <script type=\"text/javascript\">
                    alert(\"Animal cadastrado com sucesso!  \");
                    </script>
                    ";
        return die;
    }
} else {
    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_pet.php'>
                    <script type=\"text/javascript\">
                    alert(\"Funcionalidade não localizada!  \");
                    </script>
                    ";
    return die;
}
?>
