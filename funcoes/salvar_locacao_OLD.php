<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
include "geraCodigo.php";

//include "funcoes.php";

include "senha.php";
$comprovante = str_replace($troca, $recebe, $_FILES['comprovante']['name']);
$comprovante2 = str_replace($troca2, $recebe2, $_FILES['comprovante2']['name']);
$cpf = $_POST['cpf'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);

$dataComparaEntrada = DateTime::createFromFormat('d/m/Y', $_POST['dt_entrada']);
$dataComparaSaida = DateTime::createFromFormat('d/m/Y', $_POST['dt_saida']);

date_default_timezone_set('America/Sao_Paulo');
$dataHoje = date('Y-m-d H:i:s');
$autor = $_COOKIE['nome_usuario'];
//echo(var_dump($_POST));
//exit();
//====================================

If ($_POST['id_unidade'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
    <script type=\"text/javascript\">
        alert(\"Campo UNIDADE deve ser preenchida!  \");
        history.back(); 
      </script>
                ";
    return die;
}
If ($_POST['dt_entrada'] == "") {
    echo "<meta http-equiv='refresh' content='0;'>
      <script type=\"text/javascript\">
      alert(\"Campo DATA DE ENTRADA deve ser informada!  \");
      history.back(); 
      </script> ";
    return die;
}
If ($_POST['dt_saida'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo DATA DE SAÍDA deve ser informada!  \");
      history.back(); 
      </script> ";
    return die;
}

If (!isDataMaiorQueHoje($_POST['dt_entrada'])) {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo DATA DE ENTRADA deve ser maior que data hoje!  \");
      history.back(); 
      </script> ";
    return die;
}
If (!isDataMaiorQueHoje($_POST['dt_saida'])) {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo DATA DE SAIDA deve ser maior que data hoje!  \");
      history.back(); 
      </script> ";
    return die;
}

if ($dataComparaEntrada > $dataComparaSaida) {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo DATA DE SAÍDA deve ser maior que DATA DE ENTRADA!  \");
      history.back(); 
      </script>";
    return die;
}

//if ($comprovante == "") {
//    echo "<meta http-equiv='refresh' content='0; '>
//                <script type=\"text/javascript\">
//                alert(\"A AUTORIZAÇÃO DE HOSPEDAGEM deve ser inserida!  \");
//                history.back(); 
//                </script> ";
//    return die;
//}

If ($_POST['resp_loc'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"DEVE SER INFORMADO O NOME DO HÓSPEDE RESPONSÁVEL!  \");
                history.back(); 
                </script>  ";
    return die;
}
if ($_POST['identificacao_resp_loc'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"A INSERÇÃO DO DOCUMENTO DE IDENTIFICAÇÃO DO HÓSPEDE RESPONSÁVEL É OBRIGATÓRIO(A)!  \");
                history.back(); 
                </script> ";
    return die;
}
//If ($_POST['parentesco'] == "") {
//    echo "<meta http-equiv='refresh' content='0; '>
//      <script type=\"text/javascript\">
//      alert(\"Campo VÍNCULO deve ser informado!  \");
//      history.back(); 
//      </script> ";
//    return die;
//}
If ($_POST['telefone'] == "") {
    echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"DEVE SER INFORMADO O TELEFONE DO HÓSPEDE RESPONSÁVEL!  \");
                 history.back(); 
                </script>";
    return die;
}
//If ($comprovante != '') {
//    if (($_FILES['comprovante']['size'] >= 1100000) || ($_FILES['comprovante']['size'] == 0)) {
//        echo "<meta http-equiv='refresh' content='0; '>
//                     <script type=\"text/javascript\">
//                     alert(\"Tamanho máximo do arquivo AUTORIZAÇÃO DE HOSPEDAGEM excedido! Máximo de até 1MB\");
//                     history.back(); 
//                     </script>";
//        return die;
//    }
//    $extensao = strrchr($_FILES['comprovante']['name'], '.');
//    $dataHoraAtual = date('Y_m_d_H_i_s');
//    $comprovante = "AUTORIZACAO_" . $dataHoraAtual . $extensao;
//}
//If ($comprovante2 != '') {
//    if (($_FILES['comprovante2']['size'] >= 1100000) || ($_FILES['comprovante2']['size'] == 0)) {
//        echo "<meta http-equiv='refresh' content='0; '>
//                     <script type=\"text/javascript\">
//                     alert(\"Tamanho máximo do arquivo  de IDENTIFICAÇÃO DO HÓSPEDE PRINCIPAL excedido! Máximo de até 1MB\");
//                     history.back(); 
//                     </script> ";
//        return die;
//    }
//    $extensao = strrchr($_FILES['comprovante2']['name'], '.');
//    $dataHoraAtual = date('Y_m_d_H_i_s');
//    $comprovante2 = "RESP_LOC_" . $dataHoraAtual . $extensao;
//}



if ($_POST['botao'] == "Cadastrar locação") {
    //================================= Gravar no banco - Inclusao pet
    $sql = ("SELECT * FROM proprietario WHERE CPF = '$cpf'");

    $sql = mysql_query($sql);
    $ln = mysql_fetch_array($sql);
    $id_proprietario = $ln['id_proprietario'];

    $dt_entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dt_entrada']))); // Converte para '2024-12-10'
    $dt_saida = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dt_saida']))); // Converte para '2024-12-10'

    $id_unidade = $_POST['id_unidade'];
    $sql_unidade = "SELECT * FROM unidade where id_unidade = '$id_unidade'";
    $sql_unidade = mysql_query($sql_unidade);
    $ln_unidade = mysql_fetch_array($sql_unidade);

    if ($_POST['qtde_hosp'] > $ln_unidade['capacidade']) {
        echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"CADASTRO NÃO REALIZADO!   \");
                    alert(\"A QUANTIDADE DE HÓSPEDES INFORMADA É MAIOR QUE A CAPACIDADE DA UNIDADE  \");
                    history.back(); 
                    </script>  ";
        return die;
    }

    If (!verificaRangeDataUnidade($id_unidade, $dt_entrada, $dt_saida)) {
        echo "<meta http-equiv='refresh' content='0; '>
            <script type=\"text/javascript\">
            alert(\"CADASTRO NÃO REALIZADO!   \");
            alert(\"EXISTEM LOCAÇÕES VIGENTES PARA A UNIDADE E PERÍODO SELECIONADO!  \");
            history.back(); 
            </script> ";
        return die;
    }
// =================Insere na tabela locação e audita

    $codvalidacao = gerarCodigo();
    $codvalidacao = $id_proprietario . $codvalidacao . $_POST['id_unidade'];

    $comprovante = '';
    if (mysql_num_rows($sql)) {
        $sql2 = "INSERT INTO locacao (id_locacao, id_proprietario, id_unidade, qtde_hospedes, dt_entrada, dt_saida, chegada_prevista,
            autorizacao_hospedagem, resp_locacao, parentesco, contato_resp, doc_identificacao_resp, complementares, codvalidacao )
            VALUES (NULL,'" . $id_proprietario . "','" . $_POST['id_unidade'] . "','" . $_POST['qtde_hosp'] . "','"
                . $dt_entrada . "','" . $dt_saida . "','" . $_POST['hr_chegada'] . "','" . $comprovante . "','" . $_POST['resp_loc'] . "','" . $_POST['parentesco'] . "','"
                . $_POST['telefone'] . "','" . $_POST['identificacao_resp_loc'] . "','" . $_POST['complementares'] . "','" . $codvalidacao . "')";

//        echo($sql2);
//        exit();
        $result = mysql_query($sql2);
        $id_audit = mysql_insert_id(); // o comando pega o id gerado na inserção

        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao inserir dados da Reserva: $erro   \");
                    </script> ";
            return die;
        } else {
            $var = 'nao';
            $branco = '';
            $null = null;
            $sql3 = "INSERT INTO audita (id_audita, id_proprietario, id_unidade, qtde_hospedes, dt_entrada, dt_saida, chegada_prevista,
            autorizacao_hospedagem, resp_locacao, parentesco, contato_resp, doc_identificacao_resp, excluido_usuario, complementares, codvalidacao, dt_ultima_alteracao, autor )
            VALUES ('" . $id_audit . "','" . $id_proprietario . "','" . $_POST['id_unidade'] . "','" . $_POST['qtde_hosp'] . "','"
                    . $dt_entrada . "','" . $dt_saida . "','" . $_POST['hr_chegada'] . "','" . $comprovante . "','" . $_POST['resp_loc'] . "','" . $_POST['parentesco'] . "','"
                    . $_POST['telefone'] . "','" . $_POST['identificacao_resp_loc'] . "','" . $var . "','" . $_POST['complementares'] . "','" . $codvalidacao . "','" . $dataHoje . "','" . $autor . "')";
//        echo($sql2.'<p>');
//        echo($sql3);
//        exit();
            $result3 = mysql_query($sql3);
            if (!$result3) {
                $erro = mysql_error();
                echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao inserir dados de Auditoria: $erro   \");
                    </script> ";
                return die;
            } else {
                If ($comprovante != '') {
                    move_uploaded_file($_FILES['comprovante']['tmp_name'], "../documentostitularidade/" . $comprovante);
                }

                If ($comprovante2 != '') {
                    move_uploaded_file($_FILES['comprovante2']['tmp_name'], "../documentostitularidade/" . $comprovante2);
                }
                echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Reserva cadastrada com sucesso!  \");
                    </script>
                    ";
                return die;
            }
        }


//======================================        
    }
} else {
    echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Funcionalidade não localizada!  \");
                    </script>
                    ";
    return die;
}

//If (verificaRangeUnidade($ln_locacao['dt_entrada'], $ln_locacao['dt_saida'])) {
//    echo "<meta http-equiv='refresh' content='0; '>
//            <script type=\"text/javascript\">
//            alert(\" DELEÇÃO NÂO REALIZADA - Existe locações vigentes!  \");
//            history.back(); 
//            </script> ";
//    return die;
//}
//===================== Funções locais
function verificaRangeDataUnidade($id_unidade, $dt_entrada, $dt_saida) {

//    $sql_locacao = "SELECT COUNT(*) as total FROM locacao WHERE
//	(id_unidade = '$id_unidade' AND ((dt_entrada >= '$dt_entrada' AND dt_entrada <= '$dt_saida')
//        OR (dt_saida >= '$dt_entrada' AND dt_saida <= '$dt_saida')
//        OR (dt_entrada < '$dt_entrada' AND dt_saida > '$dt_saida')))";

    $sql_locacao = "SELECT COUNT(*) as total FROM locacao WHERE (id_unidade = '$id_unidade' and dt_entrada <= '$dt_saida' AND dt_saida > '$dt_entrada')";
//    echo($sql_locacao);
//    exit();
    $sql_locacao = mysql_query($sql_locacao);
    $resultado = (mysql_fetch_assoc($sql_locacao));
    if ($resultado['total'] == 0) {
//        echo("Não encontrou reservas no mesmo periodo");
        return true;
    } else {
//        echo("Encontrou reservas no mesmo periodo");
        return false;
    }
}

function isDataMaiorQueHoje(
        $data) {
    $dataComparacao = DateTime::createFromFormat('d/m/Y', $data);
    $hoje = new DateTime();
    $hoje->setTime(0, 0); // Reseta o horário para meia-noite
    return $dataComparacao > $hoje;
}
?>
