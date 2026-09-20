<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/componentes.js"></script>

<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "valida/valida_cpf.php";
include "valida/mascaraCPF.php";
include "valida/mascaras.php";
include "topo.php";

$id_locacao = $_REQUEST['id'];
$consulta = "SELECT loc.*, uni.*, prop.id_proprietario, prop.nome, prop.CPF FROM locacao loc "
        . " JOIN proprietario prop ON loc.id_proprietario = prop.id_proprietario"
        . " JOIN unidade uni on loc.id_unidade = uni.id_unidade  "
        . " WHERE loc.id_locacao = " . $id_locacao;
//echo($consulta);
//exit();

$consulta = mysql_query($consulta);
$ln = mysql_fetch_array($consulta);
//echo (var_dump($ln));
//exit();
if (mysql_num_rows($consulta) != true) {
    echo "<meta http-equiv='refresh' >
    <script type=\"text/javascript\">
    alert(\"Proprietário não possui reservas cadastradas!\");
    alert(\"Cadastre-as primeiramente!\");
    </script>
  ";
    return die;
}

$consulta2 = "SELECT * FROM hospede WHERE id_locacao = " . $id_locacao;
$consulta2 = mysql_query($consulta2);

$tamanho = strlen($ln['CPF']);
if ($tamanho > 11) {
    $cpf = mask($ln['CPF'], '##.###.###/####-##');
} else {
    $cpf = mask($ln['CPF'], '###.###.###-##');
}
$listalocacao = false;

$dt_entrada = DateTime::createFromFormat('Y-m-d', $ln['dt_entrada'])->format('d/m/Y');
$dt_saida = DateTime::createFromFormat('Y-m-d', $ln['dt_saida'])->format('d/m/Y');

$telefone = $ln['contato_resp'];
$telefone = str_replace(".", "", $telefone);
$telefone = str_replace("-", "", $telefone);
$telefone = str_replace(" ", "", $telefone);
$telefone = str_replace("(", "", $telefone);
$telefone = str_replace(")", "", $telefone);
$telefone = mask($telefone, '(##)#####-#####');

switch ($ln['etapa']) {
    case 'Azaléia - AZ':
        $etapa = 'AZ/' . $ln['numero_etapa'];
        break;
    case 'Bougainville - BO':
        $etapa = 'BO/' . $ln['numero_etapa'];
        break;
    case 'Gardênia - GA':
        $etapa = 'GA/' . $ln['numero_etapa'];
        ;
        break;
    case 'Jacarandás - JAC':
        $etapa = 'JAC/' . $ln['numero_etapa'];
        break;
    case 'Orquídeas - OR':
        $etapa = 'OR/' . $ln['numero_etapa'];
        break;
    case 'Pitangueiras - PIT':
        $etapa = 'PIT/' . $ln['numero_etapa'];
        break;
}
?>
<div id="conteudo">

    <div id="cont">
        <h2>Cadastro de hóspedes</h2>
        <hr>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>Autorização de hospedagem: </b>
        <a href="autorizacao.php?id=<?= $id_locacao ?>"  target="_blank" title="Visualizar Autorização de hospedagem">
            <img src="images/ver.jpg"  height=20 width=20 align="middle" border="0">
        </a>
        <!--        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp <b>Documento de identificação:</b>  
                <a href="documentostitularidade/<?= $ln['doc_identificacao_resp'] ?>"  target="_blank" title="Documento de indentificação do responsável">
                    <img src="images/person2.png"  height=20 width=20 align="middle" border="0">
                </a>-->
        <hr>
        <!--<form method="post" action="home.php" enctype="multipart/form-data">-->

        <table width="75%" border="0">
            <tr>
                <!--<td colspan="2"> <b>Dados Pessoais:</b></td>-->
            </tr>

<!--            <tr>
                <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPF/CNPJ:</th>
                <th width="25%" align="left" scope="col">
                    <input name="cpf" type="text" class="imput" id="cpf" size="14" maxlength="14" value="<?= $cpf ?>"
                           placeholder="Somente números"  disabled />
                </th>
            </tr>-->
            <tr>
                <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Proprietário:</th>
                <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['nome'] ?>" name="nome" size="60" disabled/></th>
            </tr>
            <tr>
                <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidade:</th>
                <td ><input type="text"  value="<?= $etapa ?>" name="unidade" size="10" maxlength="10" disabled/>
                    <font size="2"; ><b>&nbsp;&nbsp;&nbsp;Qtde Hospedes::</b> <input type="data" value="<?= $ln['qtde_hospedes'] ?>" maxlength="3" name="qtde_hosp" size="3" disabled/>
                </td>
            </tr> 
            <tr>
                <th align="left" bgcolor="#ffffff"><font size="2"; > &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data de entrada:</th>
                <td ><input type="text"  value="<?= $dt_entrada ?>" name="dt_entrada" size="10" maxlength="10" onkeypress="aplicarMascaraData($dt_entrada)"  disabled />
                    <font size="2"; ><b>&nbsp;&nbsp;&nbsp;Data de saída:</b> <input type="data" value="<?= $dt_saida ?>" maxlength="10" name="dt_saida" size="10" onkeypress="aplicarMascaraData($dt_saida)" disabled />
                    <input type="hidden" name="cpf" value="<?= $cpf ?>" />
                    <input type="hidden" name="id_unidade" value="<?= $ln['id_unidade'] ?>" />
                    <input type="hidden" name="id_audita" value="<?= $ln['id_audita'] ?>" />

                </td>
            </tr> 
            <tr>
                <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Responsável pela locação:</th>
                <th width="25%" align="left" scope="col"><input type="text" value="<?= $ln['resp_locacao'] ?>" name="resp_loc" size="60" disabled />
                </th>
            </tr>

            <tr>
                <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone do responsável:</th>
                <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="<?= $ln['contato_resp'] ?>" size="16" maxlength="16" disabled /></th>
            </tr>
            <tr>
                <th width="30%" align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Imformações Complementares:</b> </th>
                <th width="15%" align="left" scope="col"><textarea disabled name="ocorrencia" cols="57" rows="5" placeholder="Exemplo: placa do carro, marca e modelo, quantidade de veículos..."><?= $ln['complementares'] ?></textarea></th> 
            </tr>
        </table>
        <p></p><p></p>
        <!-- Tabela para adicionar novos hóspedes -->
        <div class="estiloTabelas">
            <form id="formHospedes" method="POST" enctype="multipart/form-data">
                <table width="100%" border="1">
                    <tr>
                        <td width="40%" align="center" bgcolor="#191970">
                            <b><span style="color:#F5FFFA; font-size:14px;">Nome do Hóspede</span></b>
                        </td>
                        <td width="25%" align="center" bgcolor="#191970">
                            <b><span style="color:#F5FFFA; font-size:14px;">Nº CPF / RG / Matrícula</span></b>
                            <input type="hidden" name="id_locacao" value="<?= $id_locacao ?>" />
                        </td>
                        <td width="25%" align="center" bgcolor="#191970">
                            <b><span style="color:#F5FFFA; font-size:14px;">Grau de parentesco / vínculo</span></b>
                        </td>
                        <td width="1%" align="center" bgcolor="#191970">
                            <b><span style="color:#F5FFFA; font-size:14px;">Ação</span></b>
                        </td>                        
                        <?php
                        while ($ln_hospede = mysql_fetch_array($consulta2)) {
//                            echo(var_dump($ln_hospede));
                            ?>
                        <tr>
                            <td align="center"><font size="2"; color="#000000"><?= $ln_hospede['nome_hospede'] ?></td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln_hospede['doc_hospede'] ?> </td>
                            <td align="center"><font size="2"; color="#000000"><?= $ln_hospede['parentesco_hospede'] ?> </td>
                            <td align="center" bgcolor="#E9E9E9">
                                <a href="javascript:void(0);" onclick="excluirHospede(<?= $ln_hospede['id_hospede'] ?> , <?= $ln_hospede['id_locacao'] ?>);" title="Excluir Hóspede">
                                    <img src="images/lixeira.jpg" height="20" width="20" align="middle" border="0">
                                </a>
                            </td>
                        <tr>
                            <?php
                        }
                        ?>
                    </tr>
                    <tbody id="tabelaHospedes">
                        <!-- Os hóspedes serão inseridos aqui dinamicamente -->
                    </tbody>
                </table>
                <br><center>
                    <button type="button" onclick="adicionarHospede()">Adicionar Hóspede</button>
                    <button type="button" onclick="salvarHospedes()">Salvar Hóspedes</button>
                </center>
            </form>
            <center><input type="button" value="Voltar" onclick="JavaScript: window.history.back();"></center>
        </div>



        <script>
            function adicionarHospede() {
                // Pega a referência da tabela onde os campos serão adicionados
                var tabela = document.getElementById("tabelaHospedes");

                // Cria uma nova linha na tabela
                var linha = tabela.insertRow(-1);

                // Cria a primeira célula (Nome do hóspede)
                var cell1 = linha.insertCell(0);
                cell1.innerHTML = '<input type="text" name="hospedes[nome][]" placeholder="Nome do hóspede" size="30" maxlength="30" required>';

                // Cria a segunda célula (CPF do hóspede)
                var cell2 = linha.insertCell(1);
                cell2.innerHTML = '<input type="text" name="hospedes[identificacao][]" placeholder="Identificação" size="20" maxlength="20" required>';

                var cell3 = linha.insertCell(2);
                cell3.innerHTML = '<input type="text" name="hospedes[parentesco][]" placeholder="Vínculo" size="20" maxlength="20" required>';

                var cell4 = linha.insertCell(3);

                cell4.innerHTML = '<center><a href="javascript:void(0);" onclick="desvincularHospedee(<?= $ln['id_hospede'] ?>);" title="Excluir hóspede">' +
                        ' <img src="images/lixeira.jpg" height="20" width="20" align="middle" border="0"> </a></center>';

            }

            function salvarHospedes() {
                var form = document.getElementById("formHospedes");
                var formData = new FormData(form); // Captura todos os dados do formulário

                // Enviando os dados via AJAX
                const caminhoCompleto = window.location.pathname;
                const site = '/' + caminhoCompleto.split('/')[1];
                let url = site + '/funcoes/salvar_hospedes.php';

                var xhr = new XMLHttpRequest();
                xhr.open("POST", url, true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var r = JSON.parse(xhr.responseText);
                        console.log(r);
                        if (r.status === 'success') {
                            alert("Hóspedes salvos com sucesso!");
                            window.location.href = 'cadastra_hospede.php?id=' + r.id;
//                            desabilitarCampos();
                        } else {
                            alert(r.message);
                        }
                    }
                };
                xhr.send(formData); // Envia os dados do formulário
            }

            function desabilitarCampos() {
                var form = document.getElementById("formHospedes");

                // Seleciona todos os inputs, selects e textareas dentro do formulário
                var campos = form.querySelectorAll('input, select, textarea');

                // Itera sobre todos os campos e adiciona o atributo 'disabled'
                campos.forEach(function (campo) {
                    campo.disabled = true;
                });

                // Opcionalmente, você pode também desabilitar os botões de envio
                var botoes = form.querySelectorAll('button');
                botoes.forEach(function (botao) {
                    botao.disabled = true;
                });
            }

            function excluirHospede(idHospede, id_locacao = null) {
                console.log(idHospede);
                console.log(id_locacao);
                const caminhoCompleto = window.location.pathname;
                const site = '/' + caminhoCompleto.split('/')[1];
                let url = site + '/funcoes/excluir_hospede.php';
                var formData = new FormData();
                // Adiciona o idHospede ao FormData
                formData.append('id_hospede', idHospede);

                var xhr = new XMLHttpRequest();
                xhr.open("POST", url, true);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var r = JSON.parse(xhr.responseText);
                        console.log(r);
                        if (r.status === 'success') {
                            alert("Hóspede excluído com sucesso!");
                            window.location.href = 'cadastra_hospede.php?id=' + id_locacao;
//                            desabilitarCampos();
                        } else {
                            alert(r.message);
                        }
                    }
                };
                xhr.send(formData); // Envia os dados do formulário

//                if (confirm("Confirma a desvinculação da unidade?")) {
//                    window.location.href = "../excluir_hospede.php?id_hospede=" + idHospede;
//                }
            }


        </script>
        <br>
        <p></p>

        </form>
        <br />
        </table>
    </div><!-- fim div cont -->
</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>