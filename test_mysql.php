<?php
// test_mysql.php

$host = 'host.docker.internal'; 
$dbname = 'voluntariados_universidad';
$user = 'root';
$pass = '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✔ Conexión exitosa a MySQL\n";
} catch (PDOException $e) {
    echo "Error de conexión MySQL: " . $e->getMessage() . "\n";
}
