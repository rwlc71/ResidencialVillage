
<link href="css/style.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>

<!--Scripts utilizado no autocomplete-->
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/jquery.ajaxQueue.js"></script>
<script type="text/javascript" src="jquery-autocomplete/lib/thickbox-compressed.js"></script>
<script type="text/javascript" src="jquery-autocomplete/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/jquery.autocomplete.css"/>
<link rel="stylesheet" type="text/css" href="jquery-autocomplete/lib/thickbox.css"/>
<script type="text/javascript" language=javascript>
    $(document).ready(function () {
        $("#txtNome").autocomplete("completar_nome.php", {
            width: 310,
            selectFirst: false
        });
    });

    $(document).ready(function () {
        $("#txtCPF").autocomplete("completar_cpf.php", {
            width: 310,
            selectFirst: false
        });
    });

</script>
<?php
session_name('SESSAO_PHP');
include "topo.php";
include "conexao.php";
//include "funcoes/calcula_dia.php";
include "verifica_autenticacao.php";
?>

<script >

    function MascaraData(data) {
        if (document.getElementById(data).value.length == 2)
            document.getElementById(data).value += '/';
        if (document.getElementById(data).value.length == 5)
            document.getElementById(data).value += '/';
    }


    function mascaraMutuario(o, f) {
        v_obj = o
        v_fun = f
        setTimeout('execmascara()', 1)
    }

    function execmascara() {
        v_obj.value = v_fun(v_obj.value)
    }

    function cpfCnpj(v) {

        //Remove tudo o que não é dígito
        v = v.replace(/\D/g, "")

        if (v.length <= 12) { //CPF

            //Coloca um ponto entre o terceiro e o quarto dígitos
            v = v.replace(/(\d{3})(\d)/, "$1.$2")

            //Coloca um ponto entre o terceiro e o quarto dígitos
            //de novo (para o segundo bloco de números)
            v = v.replace(/(\d{3})(\d)/, "$1.$2")

            //Coloca um hífen entre o terceiro e o quarto dígitos
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2")

        } else { //CNPJ

            //Coloca ponto entre o segundo e o terceiro dígitos
            v = v.replace(/^(\d{2})(\d)/, "$1.$2")

            //Coloca ponto entre o quinto e o sexto dígitos
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")

            //Coloca uma barra entre o oitavo e o nono dígitos
            v = v.replace(/\.(\d{3})(\d)/, ".$1/$2")

            //Coloca um hífen depois do bloco de quatro dígitos
            v = v.replace(/(\d{4})(\d)/, "$1-$2")

        }

        return v
    }

</script>


<div id="conteudo">

    <div id="cont">

        <h1><center>Tela para o registro de pagamentos de locação</center></h1> 
        <br>
        <hr/><br>
        <font size="4"; color="#000000">

        <form method="post" action="funcoes/cadupload.php" enctype="multipart/form-data">
            <p></p>

            <b>Selecione o Imóvel:</b> </td>
            <select name="imovel">
                <font size="2">
                <option size="50" value=""></option>
                <option size="50" value="Apartamento em Águas Claras">Apartamento em Águas Claras</option>
                <option size="50" value="Apartamento em Samambaia - East Side">Apartamento em Samambaia - East Side</option>
                <option size="50" value="Apartamento em Samambaia - Viver Melhor">Apartamento em Samambaia - Viver Melhor</option>
                <option size="50" value="Apartamento no Riacho Fundo">Apartamento no Riacho Fundo</option>
                <option size="50" value="Sala Comercial - 915 Sul">Sala Comercial - 915 Sul</option>
                <option size="50" value="SHVP - Chácara 33 / Lote 2B - Apto 01">SHVP - Chácara 33 / Lote 2B - Apto 01</option>
                <option size="50" value="SHVP - Chácara 33 / Lote 2B - Apto 02">SHVP - Chácara 33 / Lote 2B - Apto 02</option>
                <option size="50" value="SHVP - Chácara 33 / Lote 2B - Apto 03">SHVP - Chácara 33 / Lote 2B - Apto 03</option>
                <option size="50" value="SHVP - Chácara 33 / Lote 2B - Apto 04">SHVP - Chácara 33 / Lote 2B - Apto 04</option>
                <option size="50" value="SHVP - Chácara 33 / Lote 2B - Apto 05">SHVP - Chácara 33 / Lote 2B - Apto 05</option>
                <option size="50" value="Documentação - SHVP - Chácara 33 - Lote 2B">Documentação - SHVP - Chácara 33 - Lote 2B</option>                                  
                </font>
            </select> 
            <p><br>
            <table  border="0">
                <tr>
                    <th  align="left" >Ano / Mês de referencia da locação:</th>
                    <th   align="left">

                        <select name="ano">

                            <option size="04" value="<?= $anoatual ?>" selected="selected"><?= $anoatual ?></option>
                            <option size="04" value="2019">2019</option>
                            <option size="04" value="2020">2020</option>
                            <option size="04" value="2021">2021</option>
                            <option size="04" value="2022">2022</option>
                            <option size="04" value="2023">2023</option>
                            <option size="04" value="2024">2024</option>
                        </select>        
                        <select name="mes">
                            <option size="10" value="<?= $mesatual ?>" selected="selected"><?= $mesatual ?></option>
                            <option size="10" value="Janeiro">Janeiro</option>
                            <option size="10" value="Fevereiro">Fevereiro</option>
                            <option size="10" value="Março">Março</option>
                            <option size="10" value="Abril">Abril</option>
                            <option size="10" value="Maio">Maio</option>
                            <option size="10" value="Junho">Junho</option>
                            <option size="10" value="Julho">Julho</option>
                            <option size="10" value="Agosto">Agosto</option>
                            <option size="10" value="Setembro">Setembro</option>
                            <option size="10" value="Outubro">Outubro</option>
                            <option size="10" value="Novembro">Novembro</option>
                            <option size="10" value="Dezembro">Dezembro</option>
                        </select> 
                    </th><p>
            </tr><tr></tr><tr></tr><tr></tr>
        <tr>
            <td align="left"><b>Data do documento</b> </td>
            <td align="left"><input name="dt_emissao" value="<?= $dataatual ?>" type="text" class="imput" id="dt_emissao" placeholder="Ex: 01/01/2018" size="14" maxlength="10" onkeyup="MascaraData(this.id)"  /></td>   
        </tr><tr></tr><tr></tr><tr></tr>

        <tr>
            <td align="left"><b>Nome do locador:</b> </td>
<!--                    <td align="left"><input name="nomefornecedor" type="text" class="imput" id="nomefornecedor" size="80" maxlength="80"  /></td>   -->
            <td align="left"><input type="text" name="nomefornecedor" 
                                    id="txtNome" 
                                    size="60" 
                                    class="input_forms"
                                    onselect="carregaCpf(this)", onblur="carregaCpf(this)"  accept=""/></td>   
        </tr><tr></tr><tr></tr><tr></tr>

        <tr>
            <td align="left"><b>CPF do locador:</b> </td>
            <td align="left"><input name="cpfcnpj" type="text" onkeypress='mascaraMutuario(this, cpfCnpj)' onblur='clearTimeout()' class="imput" id="cpfcnpj" placeholder="Somente números" size="18" maxlength="18"  /></td>  
<!--            <td align="left">
                <input name="cpfcnpj" type="text" id="txtCPF" 
                       onkeypress='mascaraMutuario(this, cpfCnpj)' 
                       onblur='clearTimeout()' 
                       value="<?= $carregaCpf ?>" 
                       class="imput" id="cpfcnpj" 
                       placeholder="Somente números" 
                       size="18" maxlength="18" 
                       class="input_forms" /></td>  -->

        </tr><tr></tr><tr></tr><tr></tr>

        <tr>
            <td align="left"><b>Valor:</b> </td>
            <td align="left"><input name="valor" type="text" class="imput" id="valor" size="9"  /></td>
        </tr><tr></tr><tr></tr><tr></tr>
        <tr>

            <td align="left"><b>Caminho do documento para upload:     </b> </td>
            <td align="left"><input type="file" name="comprovante"  /> </td>
        </tr><tr></tr><tr></tr><tr></tr>
        <tr>
            <td align="left"><b>Finalidade do documento</b></td>
            <td align="left"><textarea name="finalidade" cols="73" rows="5"  >Comprovante do pagamento da locação mensal</textarea> </td>

        </tr><tr></tr><tr></tr><tr></tr>
    </table>
    <br>
    <input type="submit" value="Salvar documento" />
</form>
<font size="3"; color="#000000">
<br>
<b>Observação</b>: todos os campos são de preenchimento obrigatório!
</body>
</html>

</div><!-- fim div cont -->

</div> <!-- fim div conteudo -->
<?php
include "rodape.php";
?>