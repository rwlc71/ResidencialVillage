
<?php
$host = $_SERVER['HTTP_HOST']; // Exemplo: www.seudominio.com
// Configuração do banco de dados
if ($host == 'localhost') {
    $host = 'localhost';
    $dbname = 'bdcasasvillage';
    $user = 'root';
    $password = '';
} else {
    $host = 'bdcasasvillage.mysql.dbaas.com.br';
    $dbname = 'bdcasasvillage';
    $user = 'bdcasasvillage';
    $password = 'Village@2024';
}
try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    echo "<script type='text/javascript'>
              alert('Conexão bem-sucedida com o banco de dados: $host!');
          </script>";
} catch (PDOException $e) {
    echo "<meta http-equiv='refresh' content='0; URL=home.php'>
          <script type='text/javascript'>
              alert('Falha na comunicação com o banco de dados!');
          </script>";
    exit;
}
?>