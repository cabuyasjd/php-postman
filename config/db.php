<?php
// Configuración de conexión a la base de datos MySQL
$host = 'localhost';
$dbname = 'api_conexion';
$username = 'root';
$password = '';

// Opciones de PDO para manejar errores y resultados de forma segura
$pdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    // Intentamos conectar al servidor MySQL y crear la base de datos si no existe
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, $pdoOptions);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo = null;

    // Conectamos a la base de datos específica y creamos la tabla de usuarios si no existe
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, $pdoOptions);
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'No se pudo conectar a la base de datos.',
        'error' => $e->getMessage(),
    ]);
    exit;
}

$GLOBALS['pdo'] = $pdo;
