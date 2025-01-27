<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
if (!isset($_COOKIE['usuario'])) {
    echo "<meta http-equiv='refresh' content='0; URL=../autentica.php'>
      <script type=\"text/javascript\">
      alert(\"É preciso estar autenticado para acessar o conteúdo da pagina!\");
      </script>
     ";
    RETURN DIE;
}
include "../conexao.php";
//echo($_GET['funcao']);
//echo('<p>');
//echo($_GET['t']);
//echo('<p>');
//
//echo($_GET['id']);
//exit();
if ($_GET['funcao'] == "excluir") {

    if ($_GET['t'] == 'exmat') {

        $sql_prop = "SELECT * FROM proprietario where id_proprietario = '" . $_GET['id'] . "'";
        echo($sql_prop . '<p>');
        $sql_prop = mysql_query($sql_prop);

        if (mysql_num_rows($sql_prop) == true) {
            while ($ln_prop = mysql_fetch_array($sql_prop)) {
                $sql_unidade = "SELECT * FROM unidade where id_proprietario = '" . $_GET['id'] . "'";
                echo($sql_unidade . '<p>');

                $sql_unidade = mysql_query($sql_unidade);
                if (mysql_num_rows($sql_unidade) == true) {
                    while ($ln_unidade = mysql_fetch_array($sql_unidade)) {
                        $sql_loc = "SELECT * FROM locacao where id_proprietario = '" . $_GET['id'] . "'";
                        echo($sql_loc . '<p>');

                        $sql_loc = mysql_query($sql_loc);
                        if (mysql_num_rows($sql_loc) == true) {
                            while ($ln_locacao = mysql_fetch_array($sql_loc)) {
                                $sql_oco = "SELECT * FROM ocorrencias where id_locacao = '" . $ln_locacao['id_locacao'] . "'";
                                echo($sql_oco . '<p>');
//        exit();

                                $sql_oco = mysql_query($sql_oco);
                                if (mysql_num_rows($sql_oco) == true) {
                                    while ($ln_oco = mysql_fetch_array($sql_oco)) {
                                        $sql_del_oco = "DELETE FROM ocorrencias where id_locacao = '" . $ln_oco['id_locacao'] . "'";
                                        echo($sql_del_oco . '<p>');

                                        $result = mysql_query($sql_del_oco);
                                        if (!$result) {
                                            $erro = mysql_error();
                                            echo "<meta http-equiv='refresh' content='0; '>
                                                <script type=\"text/javascript\">
                                                alert(\" Falha de deleção na tabela ocorrências. Motivo: $erro!  \");
                                                history.back(); 
                                                </script>";
                                            Return die;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            echo "<meta http-equiv='refresh' content='0; URL= ../Excluir_usuario.php'>
 		<script type=\"text/javascript\">
                alert(\"Usuário não cadastrado!\");
                </script> ";
            Return die;
        }

//        exit();


        $sql_del_dep = "DELETE FROM pets where id_proprietario = '" . $_GET['id'] . "'";
        $result = mysql_query($sql_del_dep);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\" Falha de deleção na tabela PETS. Motivo: $erro!  \");
                history.back(); 
                </script>";
            Return die;
        }

        $sql_del_dep = "DELETE FROM dependente where id_proprietario = '" . $_GET['id'] . "'";
        $result = mysql_query($sql_del_dep);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\" Falha de deleção na tabela DEPENDENTES. Motivo: $erro!  \");
                history.back(); 
                </script>";
            Return die;
        }

        $sql_del_loc = "DELETE FROM locacao where id_proprietario = '" . $_GET['id'] . "'";
        $result = mysql_query($sql_del_loc);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\" Falha de deleção na tabela RESERVAS. Motivo: $erro!  \");
                history.back(); 
                </script>";
            Return die;
        }

        $sql_unidade = "SELECT * FROM unidade where id_proprietario = '" . $_GET['id'] . "'";
        $sql_unidade = mysql_query($sql_unidade);
        if (mysql_num_rows($sql_unidade) == true) {
            while ($ln_unidade = mysql_fetch_array($sql_unidade)) {
                if (file_exists("../documentostitularidade/" . $ln_unidade['comprovante_titularidade'])) {
                    unlink("../documentostitularidade/" . $ln_unidade['comprovante_titularidade']);
                }
            }
        }
        $sql_del_loc = "DELETE FROM unidade where id_proprietario = '" . $_GET['id'] . "'";
        $result = mysql_query($sql_del_loc);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\" Falha de deleção na tabela UNIDADE. Motivo: $erro!  \");
                    history.back(); 
                    </script>";
            Return die;
        }

        $sql_del_usu = "DELETE FROM usuarios where id_proprietario = '" . $_GET['id'] . "'";
        $result = mysql_query($sql_del_usu);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\" Falha de deleção na tabela USUARIOS. Motivo: $erro!  \");
                history.back(); 
                </script>";
            Return die;
        }

        $sql_del_prop = "DELETE FROM proprietario where id_proprietario = '" . $_GET['id'] . "'";
        $result = mysql_query($sql_del_prop);
        if (!$result) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\" Falha de deleção na tabela PROPRIETARIO. Motivo: $erro!  \");
                    history.back(); 
                    </script>";
            Return die;
        }


        echo "<meta http-equiv='refresh' content='0; URL= ../Excluir_usuario.php'>
 		<script type=\"text/javascript\">
	alert(\"Usuário excluído com Sucesso!\");
	</script> ";
        Return die;
    }



    if ($_GET['t'] == 'un') {

        $sql_locacao = "SELECT * FROM locacao where id_unidade = '" . $_GET['id'] . "'";
        $sql_locacao = mysql_query($sql_locacao);
//        echo(mysql_num_rows($sql_locacao));
//        exit();
        if (mysql_num_rows($sql_locacao) == true) {
            echo "<meta http-equiv='refresh' content='0; '>
 		<script type=\"text/javascript\">
                alert(\" DELEÇÃO NÃO REALIZADA - Existem reservas associadas a esta unidade!  \");
                alert(\" Remova as reservas e tente novamente!  \");
                history.back(); 
        	</script>";
            Return die;
        }


        $sql_unidade = "SELECT * FROM unidade where id_unidade = '" . $_GET['id'] . "'";
        $sql_unidade = mysql_query($sql_unidade);
        $ln_unidade = mysql_fetch_array($sql_unidade);

        $sql = ("DELETE FROM unidade WHERE id_unidade = '" . $_GET['id'] . "'");
//        echo($sql);
//        exit();
        $sql = mysql_query("DELETE FROM unidade WHERE id_unidade = '" . $_GET['id'] . "'");

        if (file_exists("../documentostitularidade/" . $ln_unidade['comprovante_titularidade'])) {
            unlink("../documentostitularidade/" . $ln_unidade['comprovante_titularidade']);
        }
        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_unidade.php'>
 		<script type=\"text/javascript\">
	alert(\"Unidade excluída com Sucesso!\");
	</script> 	
 ";
        Return die;
    }
    if ($_GET['t'] == 'de') {
        $sql = mysql_query("DELETE FROM dependente WHERE id_dependente = '" . $_GET['id'] . "'");

        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_dependente.php'>
 		<script type=\"text/javascript\">
	alert(\"Dependente excluído com Sucesso!\");
	</script> 	
 ";
        Return die;
    }
    if ($_GET['t'] == 'pe') {
        $sql = mysql_query("DELETE FROM pets WHERE id_pet = '" . $_GET['id'] . "'");

        echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_pet.php'>
 		<script type=\"text/javascript\">
	alert(\"Exclusão realizada com Sucesso!\");
	</script> 	
 ";
        Return die;
    }

    if ($_GET['t'] == 'loc') {
        $sql_locacao = "SELECT * FROM locacao where id_locacao = '" . $_GET['id'] . "'";
        $sql_locacao = mysql_query($sql_locacao);
        $ln_locacao = mysql_fetch_array($sql_locacao);

        If (verificaRange($ln_locacao['dt_entrada'], $ln_locacao['dt_saida'])) {
            echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
            <script type=\"text/javascript\">
            alert(\" DELEÇÃO NÂO REALIZADA - Existem reservas vigentes!  \");
            </script>  ";
            return die;
        }

        $sqlHosp = "DELETE FROM hospede WHERE id_locacao = '" . $_GET['id'] . "'";
        $result2 = mysql_query($sqlHosp);

        if ($result2) {
            $sql = "DELETE FROM locacao WHERE id_locacao = '" . $_GET['id'] . "'";
            $result3 = mysql_query($sql);

            if ($result3) {
                $sql1 = mysql_query("UPDATE audita SET excluido_usuario = 'sim' WHERE id_audita = '" . $_GET['id'] . "'");
                if (file_exists("../documentostitularidade/" . $ln_locacao['autorizacao_hospedagem'])) {
                    unlink("../documentostitularidade/" . $ln_locacao['autorizacao_hospedagem']);
                }
                if (file_exists("../documentostitularidade/" . $ln_locacao['doc_identificacao_resp'])) {
                    unlink("../documentostitularidade/" . $ln_locacao['doc_identificacao_resp']);
                }
                echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
 		<script type=\"text/javascript\">
                alert(\"Exclusão realizada com Sucesso!\");
                </script>  ";
                Return die;
            } else {
                $erro = mysql_error();
                error_log('Falha ao deletar reservas. Motivo:');
                error_log($erro);
                echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Não é possivel deletar essa reserva. Existem registros associados a reserva!\");
                    </script> ";
                return die;
            }
        } else {
            $erro = mysql_error();
            error_log('Falha ao deletar reservas. Motivo:');
            error_log($erro);
            echo "<meta http-equiv='refresh' content='0; URL= ../cadastra_reserva.php'>
                    <script type=\"text/javascript\">
                    alert(\"Não é possivel deletar essa reserva: $erro\");
                    </script> ";
            return die;
        }
    }

    if ($_GET['t'] == 'adm') {

        $sql_locacao = "SELECT * FROM locacao where id_unidade = '" . $_GET['id'] . "'";
        $sql_locacao = mysql_query($sql_locacao);
//        echo(mysql_num_rows($sql_locacao));
//        exit();

        if (mysql_num_rows($sql_locacao) == true) {

            while ($ln = mysql_fetch_array($sql_locacao)) {
                $sqloocorrencia = ("DELETE FROM ocorrencias WHERE id_locacao = '" . $ln['id_locacao'] . "'");
                $resultOcorr = mysql_query($sqloocorrencia);
                if (!$resultOcorr) {
                    $erro = mysql_error();
                    echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao desvincular ocorrencias da reserva - motivo:  $erro   \");
                    history.back(); 
                    </script> ";
                    return die;
                }
            }

            $sqlocacao = ("DELETE FROM locacao WHERE id_unidade = '" . $_GET['id'] . "'");
            $result = mysql_query($sqlocacao);
            if (!$result) {
                $erro = mysql_error();
                echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao desvincular reservas da unidade - motivo:  $erro   \");
                    history.back(); 
                    </script> ";
                return die;
            }
        }

        $sql_unidade = "SELECT * FROM unidade where id_unidade = '" . $_GET['id'] . "'";
        $sql_unidade = mysql_query($sql_unidade);
        $ln_unidade = mysql_fetch_array($sql_unidade);

        $sql = ("DELETE FROM unidade WHERE id_unidade = '" . $_GET['id'] . "'");
        $result2 = mysql_query($sql);
        if (!$result2) {
            $erro = mysql_error();
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"Falha ao desvincular unidade - motivo:  $erro   \");
                    history.back(); 
                    </script> ";
            return die;
        }

        if (file_exists("../documentostitularidade/" . $ln_unidade['comprovante_titularidade'])) {
            unlink("../documentostitularidade/" . $ln_unidade['comprovante_titularidade']);
        }
        echo "<meta http-equiv='refresh' content='0; URL= ../consulta_unidade_adm.php'>
 		<script type=\"text/javascript\">
                alert(\"Desvinculação realizada com Sucesso!\");
                </script>  ";
        Return die;
    }
} else {
    echo "<meta http-equiv='refresh' content='0; URL= ../proprietarios.php'>
 		<script type=\"text/javascript\">
	alert(\"Função inexistene!\");
	</script> 	
 ";
    Return die;
}

function verificaRange($dt_ini, $dt_fim) {
    date_default_timezone_set('America/Bahia');
    $datahoje = date("d-m-Y");
    $dt_ini = str_replace('-', '', $dt_ini);
    $dt_fim = str_replace('-', '', $dt_fim);
    $datahoje = implode('', array_reverse(explode('-', $datahoje)));
    $dataini = implode('', array_reverse(explode('-', $dt_ini)));
    $datafim = implode('', array_reverse(explode('-', $dt_fim)));
//    echo($datahoje . '<p>');
//    echo($dt_ini . '<p>');
//    echo($datafim . '<p>');

    if (($datahoje >= $dataini) && ( $datahoje <= $datafim)) {
//        echo ('Esta no range - verifica <p>');
        return true;
    } else {
//        echo ('Não Esta no range - verifica <p> ');
        return false;
    }
}
?>