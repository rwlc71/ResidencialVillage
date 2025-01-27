<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/componentes.js"></script>


<?php
session_name('SESSAO_PHP');
include "conexao.php";
include "valida/verifica_autenticacao.php";
include "topo.php";

$disabled = '';
?>
<div id="conteudo">
    <div id="cont">
        <h2>Cadastro de serviços</h2>
        <hr>
        <form method="post" action= "funcoes/salva_servico.php" enctype="multipart/form-data">
            <table width="100%" border="0">
                <tr>
                </tr>
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Titulo:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="titulo" size="60" maxlength="25"   /></th>
                </tr>

                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Endereço:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="endereco" size="60" maxlength="25" /></th>
                </tr>
                 <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Breve descrição:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="descricao" size="60" maxlength="25" /></th>
                </tr>
                 <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Contato:</th>
                    <th width="25%" align="left" scope="col"><input type="text" value="" name="contato" size="60" maxlength="25" /></th>
                </tr>    
            
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;E-mail:</th>
                    <th width="25%" align="left" scope="col">
                        <input id="email" type="text" name="email" value="" size="60"   />
                    </th>
                </tr> 
                <tr>
                    <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Telefone:</th>
                    <th width="25%" align="left" scope="col"><input type="text" onkeypress="aplicarMascaraTelefone(telefone)" name="telefone" value="" size="16" maxlength="16" <?= $disabled ?>  /></th>
                </tr>                
               <tr>
                <p></p>
                <p></p>
                <td align="left" bgcolor="#ffffff"><font size="2"; ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Imagem:</b> </td>
                <td align="left"><input type="file" name="imagem"  /> </td>
                </tr>
            </table>

            <br>
            <p></p>
            <center>
                <input type="submit" name="botao" value="Incluir serviço" />
            </center>    
        </form>
        <?php ?>
        <br />
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
