<?php
// app/Core/MongoDatabase.php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Collection;

class MongoDatabase {
    private static $client = null;
    private static $database = null;
    
    public static function connect(): Database {
        if (self::$database === null) {
            try {
                // Cargar configuración desde mongodb.php
                $config = require __DIR__ . '/../../config/mongodb.php';
                
                echo "Conectando a MongoDB Atlas...\n";
                
                $clientOptions = [
                    'serverSelectionTimeoutMS' => 10000,
                    'connectTimeoutMS' => 10000
                ];
                
                // Crear cliente MongoDB con la URI correcta
                self::$client = new Client($config['uri'], $clientOptions);
                
                // Seleccionar base de datos correcta
                self::$database = self::$client->selectDatabase($config['database']);
                
                // Verificar conexión
                self::$database->command(['ping' => 1]);
                
                echo "MongoDB Atlas conectado correctamente\n";
                echo "Base de datos: " . $config['database'] . "\n";
                
            } catch (Exception $e) {
                echo "Error conectando a MongoDB: " . $e->getMessage() . "\n";
                throw new Exception("Error de conexión a MongoDB: " . $e->getMessage());
            }
        }
        
        return self::$database;
    }
    
    public static function getCollection(string $collectionName): Collection {
        $database = self::connect();
        return $database->selectCollection($collectionName);
    }
}