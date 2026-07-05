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

$login = trim($data['login'] ?? $data['username'] ?? $data['email'] ?? '');
$password = $data['password'] ?? '';

if ($login === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El usuario/correo y la contraseña son obligatorios.']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email OR username = :username LIMIT 1');
    $stmt->execute([
        ':email' => $login,
        ':username' => $login,
    ]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas.']);
        exit;
    }

    unset($user['password']);

    echo json_encode([
        'success' => true,
        'message' => 'Inicio de sesión correcto.',
        'user' => $user,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al iniciar sesión.', 'error' => $e->getMessage()]);
}
