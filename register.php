<?php
// Establece la respuesta como JSON
header('Content-Type: application/json');

// Incluye la conexión a la base de datos
require_once __DIR__ . '/config/db.php';

$pdo = $GLOBALS['pdo'] ?? null;

// Solo acepta peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

if (!$pdo) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'No hay conexión a la base de datos.']);
    exit;
}

// Lee los datos enviados en formato JSON o formulario
$rawInput = file_get_contents('php://input');
$data = [];

if (!empty($rawInput)) {
    $decoded = json_decode($rawInput, true);
    if (is_array($decoded)) {
        $data = $decoded;
    }
}

if (empty($data) && !empty($_POST)) {
    $data = $_POST;
}

if (empty($data)) {
    $data = $_REQUEST;
}

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido.']);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->execute([
        ':username' => $username,
        ':email' => $email,
        ':password' => $hashedPassword,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Usuario registrado correctamente.',
        'user' => [
            'id' => (int) $pdo->lastInsertId(),
            'username' => $username,
            'email' => $email,
        ],
    ]);
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'El usuario o el correo ya existen.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.', 'error' => $e->getMessage()]);
    }
}
