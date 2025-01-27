<?php
$usuario = $_SESSION['usuario'];
if ($usuario == '') {
    $usuario = $_COOKIE['usuario'];
}
$menu_master = '';
//echo('Usuario:' . $_COOKIE['usuario']);
if ($_COOKIE['tipo_acesso'] == 'master') {
    $menu_master = '<li><a href="">Master</a>'
            . '<ul><li><a href="Excluir_usuario.php">Excluir usuários</a></li></ul>'
            . '</li>';
}
$menu_administrativo = '<li><a href="">Administrativo</a>'
        . '<ul><li><a href="proprietario_cadastro.php">Consultar/Cadastrar usuários</a></li></ul>'
        . '<ul><li><a href="consulta_unidade_adm.php">Consultar por unidade</a></li></ul>'
        . '<ul><li><a href="cadastra_unidade_adm.php">Vincular unidade</a></li></ul>'
        . '<ul><li><a href="seguranca.php">Consultar Reservas</a></li></ul>'
        . '<ul><li><a href="consulta_entradas.php">Consultar Entradas</a></li></ul>'
        . '<ul><li><a href="cadastra_ocorrencias.php">Consultar Ocorrências</a></li></ul>'
        . '<ul><li><a href="validar.php">Validar acesso</a></li></ul>'
        . '</li>';

$menu_seguranca = '<li><a href="">Segurança</a>'
        . '<ul><li><a href="seguranca.php">Consultar Reservas</a></li></ul>'
//        . '<ul><li><a href="consulta_proprietarios.php">Consultar Proprietários</a></li></ul>'
        . '<ul><li><a href="entradas.php">Registrar Entradas/Saídas</a></li></ul>'
        . '<ul><li><a href="cadastra_ocorrencias.php">Registrar Ocorrências</a></li></ul>'
        . '<ul><li><a href="validar_res.php">Validar acesso</a></li></ul>'
//                . '<ul><li><a href="chegadas.php">Lista de chegadas</a></li></ul>'
        . '</li>';

$menu_relatorios = '<li><a href="">Relatórios</a> <ul>'
        . ' <li><a href="consulta_proprietarios.php">Proprietários</a></li></ul>'
        . ' <ul><li><a href="consulta_unidade.php">Unidades</a></li> </ul>'
        . ' <ul><li><a href="consulta_reserva.php">Reservas</a></li> </ul>'
        . ' <ul><li><a href="consulta_dependentes.php">Dependentes/Filiados</a></li> </ul>'
        . ' <ul><li><a href="consulta_pet.php">Animais</a></li> </ul></li> ';

$menu_cadastro = '<li><a href="proprietarios.php">Cadastro</a> <ul>
                <li><a href="cadastra_unidade.php">Cadastrar Unidade(s)</a></li> </ul>
            <ul><li><a href="cadastra_reserva.php">Cadastrar Reservas</a></li></ul>
            <ul><li><a href="cadastra_dependente.php">Cadastrar Dependente(s)/Filiados</a></li></ul>
            <ul><li><a href="cadastra_pet.php">Cadastrar Animais</a></li></ul></li>  ';

$menu_documentacao = '<li><a href="">Documentos</a>'
        . '<ul><li><a href="http://www.1portodos.com.br/ResidencialVillage/normas/Regimento_Interno.pdf" target="_blank">Regimento Interno</a></li></ul>'
        . '<ul><li><a href="http://www.1portodos.com.br/ResidencialVillage/normas/CONVENCAO_2019.pdf" target="_blank">Convenção do Condomínio</a></li></ul>'
        . '<ul><li><a href="https://www.brcondominio.com.br/br1/app/login.html" target="_blank">Emissão de Boletos</a></li></ul>'
        . '</li>';

$menu_parceria = '<li><a href = "convenios.php">Convênios e Serviços</a></li>';

$menu_parceria = '<li><a href="convenios.php">Parcerias e Convênios</a>'
//        . '  <ul><li><a href="convenios.php">Parceiros</a></li></ul>'
//          .'  <ul><li><a href="cservico.php">Cadastrar Serviços</a></li></ul>'
        . ' </li>';
if (($_COOKIE['usuario'])) {
    if ($_COOKIE['tipo_acesso'] !== 'sup') {
        if ($_COOKIE['tipo_acesso'] === 'adm') {
            $menu_seguranca = '';
            $menu_cadastro = '';
            $menu_master = '';
        }
        if ($_COOKIE['tipo_acesso'] === 'con') {
            $menu_administrativo = '';
            $menu_seguranca = '';
            $menu_relatorios = '';
            $menu_master = '';
        }
        if ($_COOKIE['tipo_acesso'] === 'seg') {
            $menu_administrativo = '';
            $menu_cadastro = '';
            $menu_documentacao = '';
            $menu_parceria = '';
            $menu_master = '';
        }
        if ($_COOKIE['tipo_acesso'] === 'cord') {
            $menu_seguranca = '';
            $menu_cadastro = '';
            $menu_master = '';
        }
    }
} else {
    $menu_administrativo = '';
    $menu_seguranca = '';
    $menu_relatorios = '';
    $menu_cadastro = '';
    $menu_documentacao = '';
}

if ($usuario == '' || $usuario = null) {
    $menu_administrativo = '';
    $menu_seguranca = '';
    $menu_relatorios = '';
    $menu_cadastro = '';
    $menu_documentacao = '';
    $menu_master = '';
}
?>
<div id="topo">
    <div id="logo" style="float:left;">
        <a href="home.php" title="Voltar para a página incial">
            <!-- <img src="images/logo.gif" border="0"> -->
            <img src="images/logo.jpg" 
                 border="0">
        </a></div>
</div>

<!-- Montagem do Menu -->

<div class="menu">
    <ul id="menu">
        <!--  <li><a href="index.php?p=cadastrar">Inicio</a> -->
        <li><a href="home.php" >Início</a></li>
        <li><a href="autentica.php">Autenticar</a>
            <ul>
                <!--<li><a href="index.php?p=trocarsenha">Alterar senha</a></li>--> 
                <li><a href="password.php">Alterar senha</a></li> 
            </ul>
        </li> 

        <?= $menu_cadastro ?>
        <?= $menu_relatorios ?>
        <?= $menu_administrativo ?>
        <?= $menu_seguranca ?>
        <?= $menu_documentacao ?>
        <?= $menu_parceria ?> 
        <?= $menu_master ?> 
        <li><a href="sair.php">Sair</a>

    </ul>
</ul>
</div><!-- fim class menu -->