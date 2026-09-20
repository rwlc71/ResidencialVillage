<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!--<script type="text/javascript" src="js/componentes.js" charset=utf-8"/></script>-->
<script languague="javascript">
//  Abrir uma janela com as ações am andamento no condomínio;
    let pathSegments = window.location.pathname.split("/");
    let siteName = pathSegments[1]; // Pega a primeira parte após a barra
    let urlComunicado = "/" + siteName + "/acoes.php";

    let url = new URL(urlComunicado, window.location.origin);
    //window.open(url.href, 'popup', 'width=700,height=500,scrollbars=yes');

</script>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";

//Ações em andamento no condomínio
$acoes = [
    "Reforma da área comum.",
    "Manutenção dos elevadores.",
    "Instalação de câmeras de segurança.",
    "Pintura da fachada.",
    "Limpeza geral das áreas externas."
];
$acoes_json = json_encode($acoes); // Passa as ações para o modal
//===============================================

$botao = '<input type="submit" name="botao" value="Atualizar dados cadastrais" />';
$disabled = 'disabled';
$acao = 'proprietarios.php';
$cpf = $_COOKIE['usuario'];
if ($cpf == '') {
    $cpf = $_REQUEST['dado'];
}
if ($cpf == '') {
    echo "<meta http-equiv='refresh' content='0; URL=autentica.php'>
      <script type=\"text/javascript\">
      alert(\"É preciso estar autenticado para acessar o conteúdo da pagina!\");
      </script>
     ";
    RETURN DIE;
}

//echo ('botao '.$_POST['botao']);
//echo ('$cpf '.$cpf);
//exit();
if ($_POST['botao'] != "") {
    $disabled = '';
    $botao = '<input type="submit" name="botao" value="Salvar dados alterados" />';
    $acao = 'funcoes/proprietario_salvar.php';
}
// Carrega dados do proprietario listado 
$sql = "SELECT * FROM proprietario WHERE CPF = '$cpf'";
$sql = mysql_query($sql);
$ln = mysql_fetch_array($sql);

$cpf = str_replace(".", "", $cpf);
$cpf = str_replace("-", "", $cpf);
$cpf = str_replace("/", "", $cpf);
$tamanho = strlen($cpf);
if ($tamanho > 11) {
    $cpf = mask($cpf, '##.###.###/####-##');
} else {
    $cpf = mask($cpf, '###.###.###-##');
}

$cep = $ln['cep'];
$cep = str_replace(".", "", $cep);
$cep = str_replace("-", "", $cep);
$cep = mask($cep, '##.###-###');

$telefone = $ln['telefone'];
$telefone = str_replace(".", "", $telefone);
$telefone = str_replace("-", "", $telefone);
$telefone = mask($telefone, '(##) #####-#####');
?>
<!-- Modal de Comunicação -->
<div id="comunicacao" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Ações em Andamento no Condomínio</h2>
        <ul id="acoes-list">
            <!-- As ações em andamento serão inseridas aqui dinamicamente -->
        </ul>
    </div>
</div>
<script type="text/javascript">
    // Função para abrir o modal
    function showModal(acoes) {
        var modal = document.getElementById('comunicacao');
        var acoesList = document.getElementById('acoes-list');
        var acoesArray = JSON.parse(acoes);

        // Limpa a lista antes de adicionar novos itens
        acoesList.innerHTML = "";

        // Preenche a lista de ações no modal
        acoesArray.forEach(function(acao) {
            var li = document.createElement('li');
            li.textContent = acao;
            acoesList.appendChild(li);
        });

        // Exibe o modal
        modal.style.display = "block";
    }

    // Função para fechar o modal
    function closeModal() {
        var modal = document.getElementById('comunicacao');
        modal.style.display = "none";
    }

    // Fecha o modal se o usuário clicar fora dele
    window.onclick = function(event) {
        var modal = document.getElementById('comunicacao');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Chama a função showModal ao carregar a página
    window.onload = function() {
        showModal(<?php echo $acoes_json; ?>); // Passa as ações do PHP para o JavaScript
    };
</script>

<div id="conteudo">
    <div id="cont">
        <h2>Dados cadastrais</h2>
        <hr>
        <form method="post" action= "<?= $acao ?>" enctype="multipart/form-data">
            <table width="65%" border="0">
                <tr>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                    <th width="25%" align="left" scope="col">
                        <input type="text" value="<?= $cpf ?>"  size="14" maxlength="14" onkeypress='mascaraMutuarios(this, cpf)' onblur='validaCPF(this)' disabled />
                    </th>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Nome:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= strtoupper($ln['nome']) ?>" name="nome" size="60"  <?= $disabled ?>  /></th>
                </tr>

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Endereço:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['endereco'] ?>" name="endereco" size="60" <?= $disabled ?>  /></th>
                </tr>
                <tr>
                    <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cidade:</th>
                    <td ><input type="text" value="<?= $ln['cidade'] ?>" name="cidade" size="19" <?= $disabled ?>  />
                        <font size="2"; ><b>Estado:</b> <input type="text" value="<?= $ln['estado'] ?>" name="estado" size="4" <?= $disabled ?>  />
                        <font size="2"; ><b>CEP:</b> <input type="text" value="<?= $cep ?>" name="cep" size="12" <?= $disabled ?>   />
                    </td>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-mail:</th>
                    <th width="25%" align="left" scope="col">
                        <input id="email" type="text" name="email" value="<?= $ln['email'] ?>" size="60"  <?= $disabled ?>  />
                    </th>
                </tr> 
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone:</th>
                    <th width="25%" align="left" scope="col"><input type="text"  onkeypress="aplicarMascaraTelefone(telefone)" maxlength="17" name="telefone" value="<?= $telefone ?>" size="15"  <?= $disabled ?>  /></th>
                </tr>                
                <input type="hidden" name="cpf" value="<?= $cpf ?>" />

            </table>

            <br>
            <p></p>
            <center>
                <?= $botao ?>
            </center>    
        </form>
        <?php ?>
        <br />
        <script>
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
        </script>
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
