<?php
require 'vendor/autoload.php';

try {
    $client = new MongoDB\Client("mongodb+srv://duckliingg:duckliingg@cluster0.wxx2ryx.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");

    $db = $client->selectDatabase("voluntariados_db");
    $db->command(['ping' => 1]);

    echo "✔ Conexión exitosa a MongoDB Atlas\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
