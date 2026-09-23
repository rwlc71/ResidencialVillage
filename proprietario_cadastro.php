<!-- Adicionar o CSS -->
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<!-- Adicionar o jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Adicionar o jQuery UI (após o jQuery) -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- Scripts adicionais -->
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/componentes.js"></script>

<!--Scripts utilizados no autocomplete--> 
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/thickbox-compressed.js"></script>
<script type="text/javascript" src="jquery-autocomplete/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/jquery.autocomplete.css"/>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/lib/thickbox.css"/>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";
$disabled = '';
$readonly = '';

$cpfret = '';
$cep = '';
$telefone = '';
$tipoacesso = '';
$perfilSel = '';
$conselho = 'não';
$nome = '';
$cor_input = '';
$alertaCampos = '';
$ln['endereco'] = '';
$ln['cidade'] = '';
$ln['estado'] = '';
$ln['email'] = '';
$tipoAcessoLogado = isset($_COOKIE['tipo_acesso']) ? $_COOKIE['tipo_acesso'] : '';
$ehSup = ($tipoAcessoLogado === 'sup' || $tipoAcessoLogado === 'master');


$botões = '<input type="submit" name="botao" value="Consultar" />
          <input type="submit" name="botao" value="Incluir dados de proprietário" />';
//echo(var_dump($_REQUEST));
$cpf = $_COOKIE['usuario'];
if ($cpf == '') {
    echo "<meta http-equiv='refresh' content='0; URL=autentica.php'>
      <script type=\"text/javascript\">
      alert(\"É preciso estar autenticado para acessar o conteúdo da pagina!\");
      </script>
     ";
    RETURN DIE;
}
if ($_REQUEST['botao'] == 'Incluir dados de proprietário' || $_REQUEST['botao'] == 'Atualizar Dados') {
    // Dados recebidos via POST
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : '';
    $cidade = isset($_POST['cidade']) ? $_POST['cidade'] : '';
    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : '';
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $cep = isset($_POST['cep']) ? $_POST['cep'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
    $perfil = isset($_POST['perfil']) ? $_POST['perfil'] : '';
    $conselho = isset($_POST['conselho']) ? $_POST['conselho'] : 'não';
    $acao = 'Incluir dados de proprietário';

    if ($_REQUEST['botao'] == 'Atualizar Dados') {
        $acao = 'Alterar';
    }
    // Validar os campos (exemplo: verificar se não estão vazios)
    if (!empty($nome) && !empty($endereco) && !empty($cidade) && !empty($cpf) && !empty($estado) && !empty($cep) && !empty($email) && !empty($telefone) && !empty($perfil)) {
        // Configurar os dados para enviar via POST
        $dados = array(
            'nome' => $nome,
            'endereco' => $endereco,
            'cidade' => $cidade,
            'cpf' => $cpf,
            'estado' => $estado,
            'cep' => $cep,
            'email' => $email,
            'telefone' => $telefone,
            'perfil' => $perfil,
            'conselho' => $conselho,
            'voltar' => 'adm',
            'botao' => $acao
        );

        $host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($path, '/'));
        $firstPart = isset($parts[0]) ? $parts[0] : '';
        $url = $host . "/" . $firstPart . "/funcoes/gravar_proprietario.php";

// Configuração e envio da requisição POST via cURL
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, // Retorna a resposta como string
            CURLOPT_POST => true, // Envia como POST
            CURLOPT_POSTFIELDS => http_build_query($dados) // Dados do formulário
        ]);

        $r = curl_exec($ch); // Executa a requisição
        curl_close($ch);

// Verifica se o JSON foi retornado corretamente
        if ($r === false) {
            // Erro na requisição cURL
            $status = 'error';
            $message = 'Erro ao se conectar ao servidor.';
        } else {
            // Tenta decodificar o JSON
            $r = trim($r); // Remove espaços extras no início e no final
            $r = preg_replace('/^[^{[]+/', '', $r); // Remove texto antes de '{' ou '['
            $response = json_decode($r, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                // JSON válido
                $status = isset($response['status']) ? $response['status'] : 'error';
                $message = isset($response['message']) ? $response['message'] : 'Erro desconhecido.';
            } else {
                // JSON inválido
                $status = 'error';
                $message = 'Resposta inválida do servidor.';
            }
        }

// Exibe os dados em uma mensagem JavaScript
        if ($status === 'error') {
            echo "<meta http-equiv='refresh' content='0; '>
                    <script type=\"text/javascript\">
                    alert(\"{$message} \");
                     history.back(); 
                    </script> ";
            return die;
        } else {
            echo "<meta http-equiv='refresh' content='0; URL=proprietario_cadastro.php'>
                <script type=\"text/javascript\">
                alert(\"{$message}  \");
                </script>
                ";
            return die;
        }
    } else {
        $alertaCampos = 'Todos os campos devem ser preenchidos';
        $cpfret = $cpf;
        $ln['endereco'] = $endereco;
        $ln['cidade'] = $cidade;
        $ln['estado'] = $estado;
        $ln['email'] = $email;
        $perfilSel = $perfil;
        if ($_REQUEST['botao'] == 'Atualizar Dados') {
            $botões = '<input type="submit" name="botao" value="Atualizar Dados" />
          <input type="submit" name="botao" value="Incluir dados de proprietário" />';
        }
    }
} else {
    if ($_REQUEST['botao'] == 'Consultar') {
        $nomeConsulta = isset($_REQUEST['nome']) ? trim($_REQUEST['nome']) : '';
        $cpfConsulta = isset($_REQUEST['cpf']) ? trim($_REQUEST['cpf']) : '';
        $cpfDigits = preg_replace('/[^0-9]/', '', $cpfConsulta);

        if ($nomeConsulta === '' && $cpfDigits === '') {
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"Informe o nome ou o CPF/CNPJ para consultar.\");
                history.back(); 
                </script> ";
            return die;
        }
        $disabled = 'disabled';
        $readonly = 'readonly ';
        $botões = '<input type="submit" name="botao" value="Atualizar Dados" />
          <input type="submit" name="botao" value="Incluir dados de proprietário" />' ;
        $cor_input = "background-color: #F5F5F5	;";

        $cpfNormSql = "REPLACE(REPLACE(REPLACE(REPLACE(CPF,'.',''),'-',''),'/',''),' ','')";
        if ($cpfDigits !== '') {
            $cpfEsc = mysql_real_escape_string($cpfDigits);
            if (strlen($cpfDigits) === 11 || strlen($cpfDigits) === 14) {
                $sql = "SELECT * FROM proprietario WHERE " . $cpfNormSql . " = '" . $cpfEsc . "'";
            } else {
                $sql = "SELECT * FROM proprietario WHERE " . $cpfNormSql . " LIKE '%" . $cpfEsc . "%'";
            }
        } else {
            $nomeEsc = mysql_real_escape_string($nomeConsulta);
            $sql = "SELECT * FROM proprietario WHERE nome = '" . $nomeEsc . "'";
        }

        $sql = mysql_query($sql);
        $ln = $sql ? mysql_fetch_array($sql) : false;
        $num_rows = $sql ? mysql_num_rows($sql) : 0;
        if ($num_rows == 0 || !$ln) {
            if ($cpfDigits !== '' && $nomeConsulta === '') {
                $msgConsulta = 'Nenhum usuário encontrado para o CPF/CNPJ informado.';
            } elseif ($cpfDigits === '' && $nomeConsulta !== '') {
                $msgConsulta = 'Nenhum usuário encontrado para o nome informado.';
            } else {
                $msgConsulta = 'Nenhum usuário encontrado para o nome ou CPF/CNPJ informado.';
            }
            echo "<meta http-equiv='refresh' content='0; '>
                <script type=\"text/javascript\">
                alert(\"" . $msgConsulta . "\");
                history.back(); 
                </script> ";
            return die;
        }
        $nome = $ln['nome'];
        $cpfret = trim($ln['CPF']);
        $cpfret = str_replace(".", "", $cpfret);
        $cpfret = str_replace("-", "", $cpfret);
        $cpfret = str_replace("/", "", $cpfret);
        $tamanho = strlen($cpfret);
        if ($tamanho > 11) {
            $cpfret = mask($cpfret, '##.###.###/####-##');
        } else {
            $cpfret = mask($cpfret, '###.###.###-##');
        }

        $cep = $ln['cep'];
        $cep = str_replace(".", "", $cep);
        $cep = str_replace("-", "", $cep);
        $cep = mask($cep, '##.###-###');

        $telefone = $ln['telefone'];
        $telefone = str_replace(".", "", $telefone);
        $telefone = str_replace("-", "", $telefone);
        $telefone = mask($telefone, '(##) #####-####');

        $idproprietario = $ln['id_proprietario'];
        $sql2 = "SELECT * FROM usuarios WHERE id_proprietario = '$idproprietario'";
        $sql2 = mysql_query($sql2);
        $ln2 = mysql_fetch_array($sql2);
        $tipoacesso = $ln2['tipo_acesso'];
        $perfilSel = $tipoacesso;
        $conselho = isset($ln2['conselho']) ? strtolower(trim($ln2['conselho'])) : 'não';
        if ($conselho !== 'sim') {
            $conselho = 'não';
        }
        switch ($tipoacesso) {
            case 'con':
                $tipoacesso = 'Proprietário';
                break;
            case 'adm':
                $tipoacesso = 'Administrativo';
                break;
            case 'seg':
                $tipoacesso = 'Segurança';
                break;
            case 'sup':
                $tipoacesso = 'Master';
                break;
        }
    }
}
?>
<script type="text/javascript">
    $(document).ready(function () {
        // Autocomplete para nome
        $("#txtNome").autocomplete("completar_nome.php", {
            width: 310,
            selectFirst: false
        });

        // Autocomplete para CPF
        $("#txtCPF").autocomplete("completar_cpf.php", {
            width: 310,
            selectFirst: false
        });

        // Inicializar o datepicker
        $("#dt_entrada, #dt_saida").datepicker({
            dateFormat: "dd/mm/yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });
    });
</script>
<!-- Reintroduzir o $.browser (script para versões mais recentes do jQuery) -->
<script>
    (function () {
        var matched, browser;

        jQuery.uaMatch = function (ua) {
            ua = ua.toLowerCase();

            var match = /(chrome)[ \/]([\w.]+)/.exec(ua) ||
                    /(webkit)[ \/]([\w.]+)/.exec(ua) ||
                    /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(ua) ||
                    /(msie) ([\w.]+)/.exec(ua) ||
                    ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(ua) ||
                    [];

            return {
                browser: match[1] || "",
                version: match[2] || "0"
            };
        };

        matched = jQuery.uaMatch(navigator.userAgent);
        browser = {};

        if (matched.browser) {
            browser[matched.browser] = true;
            browser.version = matched.version;
        }

        // Chrome is Webkit, but Webkit is also Safari.
        if (browser.chrome) {
            browser.webkit = true;
        } else if (browser.webkit) {
            browser.safari = true;
        }

        jQuery.browser = browser;
    })();
</script>
<div id="conteudo">
    <div id="cont">
        <h2>&nbsp;&nbsp;Consulta Dados cadastrais</h2><br>
        <hr><br>
        <!--<form method="post" action= "funcoes/proprietario_salvar.php" enctype="multipart/form-data">-->
        <?php if ($alertaCampos !== '') { ?>
        <script type="text/javascript">alert("<?= str_replace('"', '\\"', $alertaCampos) ?>");</script>
        <?php } ?>
        <form id="cadastro" method="post" action= "proprietario_cadastro.php" enctype="multipart/form-data">
            <table width="75%" border="0">
                <tr>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                    <th width="25%" align="left" scope="col">
                        <input name="cpf" id="txtCPF" type="text" class="input_forms" onkeypress="aplicarMascaraCpfCnpj(this)" value="<?= $cpfret ?>" size="18" maxlength="18" style="<?= $cor_input ?>" <?= $readonly ?> >

                    </th>
                </tr>
<!--                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="nome" size="60"  <?= $disabled ?>  /></th>
                </tr>-->

                <tr>
                    <td align="left"><font size="2"; color="#000000"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome:</b> </td>
                    <td>  <font size="2"; color="#000000"><input type="text" name="nome" style="<?= $cor_input ?>"
                                                                 value="<?= $nome ?>"
                                                                 id="txtNome" 
                                                                 size="60" 
                                                                 class="input_forms"
                                                                 onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept="" <?= $readonly ?>/><br></td> 
                </tr>

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Endereço:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['endereco'] ?>" name="endereco" size="60" /></th>
                </tr>
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cidade:</th>
                    <td ><input type="text" value="<?= $ln['cidade'] ?>" name="cidade" size="19"   />
                        <font size="2"; ><b>Estado:</b> <input type="text" value="<?= $ln['estado'] ?>" maxlength="2" name="estado" size="2"   />
                        <font size="2"; ><b>CEP:</b> <input type="text" onkeypress="mascaraCEP(cep)" value="<?= $cep ?>" name="cep" size="10" maxlength="9"   />
                    </td>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-mail:</th>
                    <th width="25%" align="left" scope="col">
                        <input id="email" type="text" name="email" value="<?= $ln['email'] ?>" size="60"   />
                    </th>
                </tr> 
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $telefone ?>" size="16" maxlength="16"   /></th>
                </tr>                
                <tr>
                    <td width="6%"align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Perfil de acesso:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <select id="perfil" name="perfil" ><font size="2"; color="#000000">
                            <option value="" <?= ($perfilSel === '') ? 'selected="selected"' : '' ?>>Selecione...</option>
                            <option value="adm" <?= ($perfilSel === 'adm' || $perfilSel === 'Administrativo') ? 'selected="selected"' : '' ?>>Administrativo</option>
                            <option value="con" <?= ($perfilSel === 'con' || $perfilSel === 'Proprietário') ? 'selected="selected"' : '' ?>>Proprietário</option>
                            <option value="seg" <?= ($perfilSel === 'seg' || $perfilSel === 'Segurança') ? 'selected="selected"' : '' ?>>Segurança</option>
                            <?php if ($ehSup || $perfilSel === 'sup' || $perfilSel === 'Master') { ?>
                            <option value="sup" <?= ($perfilSel === 'sup' || $perfilSel === 'Master') ? 'selected="selected"' : '' ?>>Master</option>
                            <?php } ?>
                        </select>
                    </th>        
                </tr>
                <tr>
                    <td width="6%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Conselheiro:</b> </td>
                    <th width="25%" align="left" scope="col">
                        <label><input type="radio" name="conselho" value="sim" <?= ($conselho === 'sim') ? 'checked="checked"' : '' ?> /> Sim</label>
                        &nbsp;&nbsp;
                        <label><input type="radio" name="conselho" value="nao" <?= ($conselho !== 'sim') ? 'checked="checked"' : '' ?> /> Não</label>
                    </th>
                </tr> 
            </table>

            <br>
            <p></p>
            <center>
                <?= $botões ?>
                <input type="button" value="Limpar dados" onclick="limparDadosCadastro();" />

<!--                <input type="submit" name="botao" value="Consultar" />
                <input type="submit" name="botao" value="Incluir dados de proprietário" />-->
            </center>    
        </form>
        <?php ?>
        <br />
<script>
        function limparDadosCadastro() {
            window.location.replace('proprietario_cadastro.php');
        }
</script>        
        <script>
            function aplicarMascaraCpfCnpj(input) {
                // Adiciona um event listener para o evento de input (digitação)
                input.addEventListener('input', function () {
                    // Remove todos os caracteres que não são dígitos
                    let valor = input.value.replace(/\D/g, '');

                    // Aplica a máscara de CPF (###.###.###-##) para até 11 dígitos
                    if (valor.length <= 11) {
                        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                        valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    }
                    // Aplica a máscara de CNPJ (##.###.###/####-##) para mais de 11 dígitos
                    else {
                        valor = valor.replace(/(\d{2})(\d)/, '$1.$2');
                        valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                        valor = valor.replace(/(\d{3})(\d)/, '$1/$2');
                        valor = valor.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
                    }

                    // Atualiza o valor do input com a máscara aplicada
                    input.value = valor;
                });
            }
            function aplicarMascaraTelefone(input) {
                // Adiciona um event listener para o evento de input (digitação)
                input.addEventListener('input', function () {
                    // Remove todos os caracteres que não são dígitos
                    let valor = input.value.replace(/\D/g, '');

                    if (valor.length > 0) {
                        valor = valor.replace(/^(\d{2})(\d)/g, '($1) $2'); // Adiciona o parêntese
                    }

                    if (valor.length > 9) {
                        valor = valor.replace(/(\d{5})(\d)/, '$1-$2'); // Adiciona o hífen após o quinto dígito
                    }

                    // Atualiza o valor do input com a máscara aplicada
                    input.value = valor;
                });
            }

            function mascaraCEP(input) {
                let cep = input.value;

                // Remove qualquer caractere que não seja número
                cep = cep.replace(/\D/g, "");

                // Formata o valor no formato XXXXX-XXX
                if (cep.length > 5) {
                    cep = cep.replace(/(\d{5})(\d{1,3})/, "$1-$2");
                }

                // Atualiza o valor do campo de input
                input.value = cep;
            }
        </script>
        </script>
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
