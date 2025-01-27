<div id="rodape">
    <?php
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// Pega o host (domínio)
    $host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $parts = explode('/', trim($path, '/'));
    $firstPart = isset($parts[0]) ? $parts[0] : '';
    ?>
    Todos os direitos reservados - &copy; - 2017 - <?= date('Y') ?> 
    <a href= "politicaprivacidade.php">Política de Privacidade.</a>
</div>