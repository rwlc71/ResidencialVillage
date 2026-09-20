<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
session_start();
include "../conexao.php";
include "../valida/valida_cpf.php";
include "../valida/verifica_autenticacao.php";
include "senha.php";
include "geraCodigo.php";

$cpf = $_POST['cpf'];
$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);
//echo(var_dump($_REQUEST));
//echo('$comprovante - ' . $comprovante);
//echo('$comprovante2 - ' . $comprovante2);
//exit();
//====================================
$botao = $_REQUEST['botao'];
$id_locacao = $_REQUEST['id_locacao'];
$dt_entrada = $_REQUEST['dt_entrada'];
$dt_saida = $_REQUEST['dt_saida'];
$resp_loc = $_REQUEST['resp_loc'];
$identificacao_resp_loc = $_REQUEST['identificacao_resp_loc'];
$telefone = $_REQUEST['telefone'];
$qtde_hosp = $_REQUEST['qtde_hosp'];
$complementares = $_REQUEST['complementares'];
$hr_chegada = $_REQUEST['hr_chegada'];
$parentesco = $_REQUEST['parentesco'];
$dataComparaEntrada = DateTime::createFromFormat('d/m/Y', $dt_entrada);
$dataComparaSaida = DateTime::createFromFormat('d/m/Y', $dt_saida);
//echo('vai validar $botao: ' . $botao . ' <p>');

// verificase já foi registrada a entrada
$consulta = "SELECT aud.*, uni.*, prop.id_proprietario, prop.nome, prop.CPF 
    FROM audita aud
    JOIN proprietario prop ON aud.id_proprietario = prop.id_proprietario
    JOIN unidade uni ON aud.id_unidade = uni.id_unidade
    WHERE aud.id_audita = " . intval($id_locacao); // evita injeção

$resultado = mysql_query($consulta);
$ln = mysql_fetch_array($resultado);
$dt_entrada_efetiva = $ln['dt_entrada_efetiva'];

If ($dt_entrada_efetiva) {
       echo "<meta http-equiv='refresh' content='0; '>
        <script type=\"text/javascript\">
        alert(\"Atenção: não é mais permitido fazer alterações na reserva - AUTORIZAÇÃO COM REGISTRO DE ENTRADA JÁ REALIZADO!   \");
        history.back(); 
      </script> ";
    return die;
}

If ($dt_entrada === "") {
    echo "<meta http-equiv='refresh' content='0; '>
     <script type=\"text/javascript\">
      alert(\"Campo DATA DE ENTRADA deve ser informada!  \");
      history.back(); 
      </script> ";
    return die;
}

If ($dt_saida === "") {
    echo "<meta http-equiv='refresh' content='0; '>
      <script type=\"text/javascript\">
      alert(\"Campo DATA DE SAÍDA deve ser informada!  \");
      history.back(); 
      </script> ";
    return die;
}

$dtEntradaConvertida = implode('', array_reverse(explode('/', $dt_entrada)));
$dtSaidaConvertida = implode('', array_reverse(explode('/', $dt_saida)));
$dataHoje = date('Ymd');
//echo('vai validar $dtEntradaConvertida: ' . $dtEntradaConvertida . ' <p>');
//echo('vai validar $dtSaidaConvertida: ' . $dtSaidaConvertida . ' <p>');
//echo('vai validar $dataHoje: ' . $dataHoje . ' <p>');
//exit();


if ($dataHoje > $dtEntradaConvertida) {
    echo "<meta http-equiv='refresh' content='0; '>
     <script type=\"text/javascript\">
      alert(\"Campo DATA DE ENTRADA deve ser maior que data hoje!  \");
      history.back(); 
      </script> ";
    return die;
}

if ($dataHoje > $dtSaidaConvertida) {
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


If ($resp_loc == "") {
    echo "<meta http-equiv='refresh' content='0; '>
          <script type=\"text/javascript\">
          alert(\"DEVE SER INFORMADO O NOME DO HÓSPEDE RESPONSÁVEL!  \");
          history.back(); 
          </script>  ";
    return die;
}

If ($parentesco == "") {
    echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"DEVE SER INFORMADO O TIPO DE VÍNCULO DO HÓSPEDE RESPONSÁVEL!  \");
                history.back(); 
		        </script>  ";
    return die;
}

if ($identificacao_resp_loc == "") {
    echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"A INSERÇÃO DO DOCUMENTO DE IDENTIFICAÇÃO DO HÓSPEDE RESPONSÁVEL É OBRIGATÓRIO(A)!  \");
                history.back(); 
                </script> ";
    return die;
}
$cpfValido = validarCPF($_POST['identificacao_resp_loc']);

If (!$cpfValido) {
    echo "<meta http-equiv='refresh' content='0; '>
    <script type=\"text/javascript\">
        alert(\"Número do CPF inválido. Informe um CPF válido!  \");
        history.back(); 
      </script> ";
    return die;
}
If ($telefone == "") {
    echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"DEVE SER INFORMADO O TELEFONE DO HÓSPEDE RESPONSÁVEL!  \");
                history.back(); 
               </script>";
    return die;
}

if ($botao == "Salvar Alterações") {
    $sql = ("SELECT * FROM locacao WHERE id_locacao = '$id_locacao'");
    $sql = mysql_query($sql);
    $ln = mysql_fetch_array($sql);
    $id_unidade = $ln['id_unidade'];
    $dt_entrada = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dt_entrada']))); // Converte para '2024-12-10'
    $dt_saida = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['dt_saida']))); // Converte para '2024-12-10'

    if (mysql_num_rows($sql)) { //Alteração
//        If (!verificaRangeDataUnidadeLocacao($id_unidade, $dt_entrada, $dt_saida, $id_locacao)) {
//            echo "<meta http-equiv='refresh' content='0; URL= ../editar_reserva.php?id=$id_locacao'>
//            <script type=\"text/javascript\">
//            alert(\"CADASTRO NÃO REALIZADO!   \");
//            alert(\"EXISTEM LOCAÇÕES VIGENTES PARA A UNIDADE E PERÍODO SELECIONADO!  \");
//            </script> ";
//            return die;
//        }

    $codvalidacao = gerarCodigo();
    $codvalidacao = 'AlT' . $codvalidacao . $id_locacao;
    
        $sql1 = "UPDATE locacao SET qtde_hospedes = '" . $qtde_hosp . "',
                    dt_entrada = '" . $dt_entrada . "',
                    dt_saida = '" . $dt_saida . "',
                    chegada_prevista = '" . $hr_chegada . "',
                    resp_locacao = '" . $resp_loc . "',
                    parentesco = '" . $parentesco . "',
                    contato_resp = '" . $telefone . "',
                    doc_identificacao_resp = '" . $identificacao_resp_loc . "',
                    complementares = '" . $complementares . "',
                    codvalidacao = '" . $codvalidacao . "'
                    WHERE id_locacao = '" . $id_locacao . "'";
//            echo($sql1);
//            exit();

        $result = mysql_query($sql1);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; URL= ../editar_reserva.php?id=$id_locacao'>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao atualizar dados de Reservas: $erro   \");
                    </script> ";
            return die;
        } else {
            date_default_timezone_set('America/Sao_Paulo');
            $datacad = date('Y-m-d H:i:s');
            $autor = $_COOKIE['nome_usuario'];
            $sql3 = "UPDATE audita SET qtde_hospedes = '" . $qtde_hosp . "',
                    dt_entrada = '" . $dt_entrada . "',
                    dt_saida = '" . $dt_saida . "',
                    chegada_prevista = '" . $hr_chegada . "',
                    resp_locacao = '" . $resp_loc . "',
                    parentesco = '" . $parentesco . "',
                    contato_resp = '" . $telefone . "',
                    doc_identificacao_resp = '" . $identificacao_resp_loc . "',
                    complementares = '" . $complementares . "',
                    codvalidacao = '" . $codvalidacao . "',
                    dt_ultima_alteracao = '" . $datacad . "',
                    autor = '" . $autor . "'
                    WHERE id_audita = '" . $id_locacao . "'";
//            echo($sql3);
//            exit();
            $result2 = mysql_query($sql3);
            if (!$result2) {
                $erro = mysql_error();
                echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao atualizar dados de Auditoria: $erro   \");
                    history.back(); 
                    </script> ";
                return die;
            } else {
//                echo "<meta http-equiv='refresh' content='0; URL= ../editar_reserva.php?id=$id_locacao'>
                echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Atualização da Reserva realizada com sucesso!  \");
                    </script>";
                return die;
            }
        }
    }
}

//===================== Funções locais
function verificaRangeDataUnidadeLocacao($id_unidade, $dt_entrada, $dt_saida, $id_locacao) {

//    $sql_locacao = "SELECT COUNT(*) as total FROM locacao WHERE
//	(id_unidade = '$id_unidade' AND ((dt_entrada >= '$dt_entrada' AND dt_entrada <= '$dt_saida')
//        OR (dt_saida >= '$dt_entrada' AND dt_saida <= '$dt_saida')
//        OR (dt_entrada < '$dt_entrada' AND dt_saida > '$dt_saida')))";
//    echo($sql_locacao);
//    echo('<p>');
// 
    $sql_locacao = "SELECT * FROM locacao WHERE (id_unidade = '$id_unidade' and dt_entrada < '$dt_saida' AND dt_saida > '$dt_entrada')";

//    echo('<p>');
//    echo($id_locacao);
//    echo('<p>');
//    echo($sql_locacao);
//    echo('<p>');

    $sql_locacao = mysql_query($sql_locacao);
    $verifica = 'true';
    while ($registros = mysql_fetch_array($sql_locacao)) {
        if ($registros['id_locacao'] !== $id_locacao) {
            $verifica = 'false';
        }
    }
    if ($verifica == 'true') {
        return true;
    } else {
        return false;
    }
}
?>
