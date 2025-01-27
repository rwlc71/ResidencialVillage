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
?>
<div id="conteudo">
    <div id="cont">
        <h2>Validar Autorização de Hospedagem<h2>
                <hr>
                <form method="post" action="creserva.php" enctype="multipart/form-data">
                    <table width="75%" border="0">
                        <tr>
                            <th width="6%" align="left" bgcolor="#ffffff"><font size="2"; >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Código da autorização:</th>
                            <th width="25%" align="left" scope="col"><input type="text"  name="codigo" value="" size="30" maxlength="30"  /></th>
                        </tr>
                    </table>
                    <center>
                        <input type="submit" name="botao" value="Consultar" />
                    </center>    
                </form>
                <hr/>          
                </div><!-- fim div cont -->
                </div> <!-- fim div conteudo -->
                <?php
                include "rodape.php";
                ?>