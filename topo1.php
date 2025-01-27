<?php
$usuario = $_SESSION['usuario'];
if ($usuario == '') {
    $usuario = $_COOKIE['usuario'];
}

$sql1 = mysql_query("SELECT * FROM usuarios WHERE usuario = '$usuario'");
$ln1 = mysql_fetch_array($sql1);
if (($_COOKIE['usuario'])) {
    if ($_COOKIE['tipo_acesso'] === 'adm' || $_COOKIE['tipo_acesso'] === 'sup') {
        $menu_administrativo = '<li><a href="#">Administrativo</a>
            <ul>
                <li><a href="proprietario_cadastro.php">Cadastrar proprietário</a></li>
                <li><a href="consulta_ocorrencias.php">Consulta Ocorrências</a></li>
            </ul>
        </li>';
        $menu_seguranca = '<li><a href="seguranca.php">Segurança</a></li>';

        $menu_relatorios = '<li><a href="#">Relatórios</a>
            <ul>
                <li><a href="consulta_proprietarios.php">Busca Proprietários</a></li>
                <li><a href="consulta_dependentes.php">Busca Dependentes</a></li>
                <li><a href="consulta_unidade.php">Busca Unidades</a></li>
                <li><a href="consulta_reserva.php">Busca Reservas</a></li>
                <li><a href="consulta_pet.php">Busca Animais</a></li>
            </ul>
        </li>';
    }

    $menu_cadastro = '<li><a href="#">Cadastro</a>
        <ul>
            <li><a href="cadastra_unidade.php">Cadastro de Unidade(s)</a></li>
            <li><a href="cadastra_dependente.php">Cadastro de Dependente(s)</a></li>
            <li><a href="cadastra_pet.php">Cadastro de Animais</a></li>
            <li><a href="cadastra_reserva.php">Cadastro de Reservas</a></li>
        </ul>
    </li>';
}
?>

<div id="topo">
    <div id="logo" style="float:left;">
        <a href="home.php" title="Voltar para a página inicial">
            <img src="images/logo.jpg" alt="Logo" border="0">
        </a>
    </div>
</div>

<!-- Montagem do Menu -->
<nav class="navbar">
    <div class="menu-toggle">
        <input type="checkbox" id="menu-toggle-checkbox">
        <label for="menu-toggle-checkbox" class="menu-icon">&#9776;</label>
    </div>
    <ul id="menu">
        <li><a href="home.php">Início</a></li>
        <li><a href="autentica.php">Autenticar</a>
            <ul>
                <li><a href="password.php">Alterar senha</a></li>
            </ul>
        </li>
        <?= $menu_cadastro ?>
        <?= $menu_relatorios ?>
        <?= $menu_administrativo ?>
        <?= $menu_seguranca ?>
        <li><a href="sair.php">Sair</a></li>
    </ul>
</nav>
