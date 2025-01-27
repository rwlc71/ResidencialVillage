<?php

//=================== Coloca mácaras em no CPF, data, CNPJ, etc....
function mask($val, $mask) {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k]))
                $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
                $maskared .= $mask[$i];
        }
    }
    return $maskared;
}
?>

<?php

/**
 * Função para formatar Telefone, CEP, CPF, CNPJ e RG
 *
 * Escolher tipo de formatação ( fone, cep, cpf, cnpj ou rg) 
 * Lembrar de colocar em lowercase
 * @param $tipo  string
 *   
 * Enviar string que para ser formata ex: 13974208014;
 * @param $string  string   
 *
 * Quantidade de caracteres a serem formatados, 
 * só serve para o telefone 10 para o padrão antigo e 11 para novo padrão com 9
 * @param $size  integer  
 *
 *
 * Valor formatado do padrão escolhido
 * @return $string  string   
 */
function formatar($tipo = "", $string, $size = 10) {
    $string = ereg_replace("[^0-9]", "", $string);

    switch ($tipo) {
        case 'fone':
            if ($size === 10) {
                $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 4)
                        . '-' . substr($tipo, 6);
            } else {
                if ($size === 11) {
                    $string = '(' . substr($tipo, 0, 2) . ') ' . substr($tipo, 2, 5)
                            . '-' . substr($tipo, 7);
                }
            }
            break;

        case 'cep':
            $string = substr($string, 0, 5) . '-' . substr($string, 5, 3);
            break;

        case 'cpf':
            $string = substr($string, 0, 3) . '.' . substr($string, 3, 3) .
                    '.' . substr($string, 6, 3) . '-' . substr($string, 9, 2);
            break;

        case 'cnpj':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) .
                    '.' . substr($string, 5, 3) . '/' .
                    substr($string, 8, 4) . '-' . substr($string, 12, 2);
            break;

        case 'rg':
            $string = substr($string, 0, 2) . '.' . substr($string, 2, 3) .
                    '.' . substr($string, 5, 3);
            break;

        default:
            $string = 'É ncessário definir um tipo(fone, cep, cpg, cnpj, rg)';
            break;
    }
    return $string;
}
?>
<?php

function DataMaiorQueHoje($data) {
    $dataComparacao = DateTime::createFromFormat('d/m/Y', $data);
    $hoje = new DateTime();
    $hoje->setTime(0, 0); // Reseta o horário para meia-noite
    return $dataComparacao > $hoje;
}
?>



<script language="JavaScript">
    /* Formatação para qualquer mascara */

    function formatar(src, mask)
    {
        var i = src.value.length;
        var saida = mask.substring(0, 1);
        var texto = mask.substring(i)
        if (texto.substring(0, 1) != saida)
        {
            src.value += texto.substring(0, 1);
        }
    }

    /* Valida Data */

    var reDate4 = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
    var reDate = reDate4;

    function doDateVenc(Id, pStr, pFmt) {
        d = document.getElementById(Id);
        if (d.value != "") {
            if (d.value.length < 10) {
                alert("Data Inválida!\nDigite corretamente a data: dd/mm/aaaa !");
                d.value = "";
                d.focus();
                return false;
            } else {

                eval("reDate = reDate" + pFmt);
                if (reDate.test(pStr)) {
                    return false;
                } else if (pStr != null && pStr != "") {
                    alert("ALERTA DE ERRO!!\n\n" + pStr + " NÃO é uma data válida.");
                    d.value = "";
                    d.focus();
                    return false;
                }
            }
        } else {
            return false;
        }
    }
</script>