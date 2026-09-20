<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
include "topo.php";
include "conexao.php";

if (!$_POST['botao']) {
    ?>
    <div id="conteudo">
        <div id="cont">
            <h2>Alteração de senha</h2>
            <hr>
            <form action='password.php' method='post' >
                <table width="75%" border="0">
                    <tr>
                        <th width="155" align="left" scope="col">Usuário:</th>
                        <th width="417" align="left"  scope="col"><input type="text" name="usuario" size="35"/></th>
                    </tr>
                    <tr>
                        <td align="left"><b>Senha atual:</b> </td>
                        <td align="left"><input type="password" name="senha" size="35" /></td>
                    </tr>
                    <tr>
                        <td align="left"><b>Nova senha:</b> </td>
                        <td align="left"><input type="password" name="newsenha" size="35"/></td>
                    </tr>
                    <tr>
                        <td align="left"><b>Confirme a nova senha:</b> </td>
                        <td align="left"><input type="password" name="repeatnewsenha" size="35"/></td>
                    </tr>
                </table>
                <p></p>

                <b> Observação:</b> Caso tenha esquecido sua senha, clique no botão "<b>Esqueci minha senha</b>". 
                Será enviado para seu endereço de e-mail uma nova senha de acesso.
                <br />
                <br />
                <input type="submit" name="botao" value="Atualizar senha" />
                <input type="submit" name="botao" value="Esqueci minha senha" />
            </form>
        </div><!-- fim div cont -->
    </div> <!-- fim div conteudo -->
    <?php
} else {
    if ($_POST['botao'] == "Esqueci minha senha") {
        include "conexao.php";
        include "funcoes/senha.php";
        $usuario = $_POST['usuario'];

        $sql = ("SELECT * FROM usuarios WHERE usuario = '$usuario' ");
//        echo(($sql));

        $sql = mysql_query($sql);
//        echo(mysql_num_rows($sql).'<p>');
//        echo(var_dump($_POST));
//        exit();
        if ($_POST['usuario'] == "") {
            echo "<meta http-equiv='refresh' content='0; URL=password.php'>
		<script type=\"text/javascript\">
		alert(\"Preencha os dados de usuário!\");
		</script>
                ";
            return die;
        }
        if (mysql_num_rows($sql) == false) {
            echo "<meta http-equiv='refresh' content='0; URL=password.php'>
		<script type=\"text/javascript\">
		alert(\"Usuário não cadastrado!\");
		</script> ";
            return die;
        } else {
//            header("Location: funcoes/gerarnovasenha.php?usuario=$usuario");
//            die;
            // Protege a variável (para evitar problemas de injeção)
            $usuario = mysql_real_escape_string($usuario);
            // Chama o script diretamente, sem interromper o fluxo
            include 'funcoes/gerarnovasenha.php';
        }
    } else if ($_POST['botao'] == "Atualizar senha") {
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];
        $newsenha = $_POST['newsenha'];
        $repeatnewsenha = $_POST['repeatnewsenha'];

        if (!$usuario || !$senha || !$newsenha || !$repeatnewsenha) {
            echo "<meta http-equiv='refresh' content='0; URL=password.php'>
		<script type=\"text/javascript\">
                alert(\"Todos os campos devem ser preenchidos\");
		</script>
	";
            return die();
        }
        $sql = "SELECT id_proprietario FROM usuarios WHERE usuario = '$usuario' and  senha = '$senha'";
//        echo('sql: ' . $sql . "<p>");

        $sql = mysql_query("SELECT id_proprietario FROM usuarios WHERE usuario = '$usuario' and  senha = '$senha'");
//        $ln = mysql_fetch_array($sql);
        $rows = mysql_num_rows($sql);
        if ($rows == '0') {
            echo "<meta http-equiv='refresh' content='0; URL=password.php'>
		<script type=\"text/javascript\">
                alert(\"Usuário e senha não encontrados. Clique em esqueci minha a senha!\");
		</script>
	";
            return die();
        }

        if ($sql) {
//            echo('rows:' . $rows);
//            echo('Consulta realizada com sucesso!');
//            exit();
            // Se você deseja manipular os resultados, pode fazer isso aqui
            while ($linha = mysql_fetch_assoc($sql)) {
                $id_proprietario = $linha['id_proprietario'];
//                echo($id_proprietario);
//                exit();
            }
        } else {
//            echo('Consulta NÂO realizada com sucesso!');
//            exit();
            if (mysql_error()) {
                $erro = mysql_error();
                echo "<meta http-equiv='refresh' content='0; URL=password.php'>
		<script type=\"text/javascript\">
                alert(\"Erro na consulta ao banco de dados: $erro\");
		</script>
	";
            } else {
                echo "<meta http-equiv='refresh' content='0; URL=password.php'>
		<script type=\"text/javascript\">
		alert(\"Usuário ou senha inválidos\");
		</script>
	";
            }
        }

        if ($newsenha !== $repeatnewsenha || $newsenha == "") {
            echo "<meta http-equiv='refresh' content='0; URL=password.php'>
		<script type=\"text/javascript\">
		alert(\"As senhas digitadas não são idênticas ou estão em branco\");
		</script>
	";
        } else {
            if (mysql_num_rows($sql) == true) {
//                echo('Chegou aqui');
//                exit();
                $sql = "UPDATE usuarios SET senha = '" . $newsenha . "' WHERE id_proprietario = '" . $id_proprietario . "'";
                mysql_query($sql);
                echo "<meta http-equiv='refresh' content='0; URL=proprietarios.php'>
		<script type=\"text/javascript\">
		alert(\"Atualizacão realizada com sucesso!\");
		</script>
	";
            }
        }
    }
}
include "rodape.php";
?>