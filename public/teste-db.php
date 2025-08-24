<?php
$host = 'db';
$port = 3306;
$dbname = 'central_ajuda';
$user = 'root';
$pass = getenv('MYSQL_ROOT_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Conexão OK!";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
