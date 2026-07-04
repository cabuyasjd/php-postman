<?php
header('Content-Type: application/json');

require_once __DIR__ . '/config/db.php';

$pdo = $GLOBALS['pdo'] ?? null;

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

$data = json_decode(file_get_contents('php://input'), true) ?? [];
$login = trim($data['login'] ?? '');
$password = $data['password'] ?? '';

if ($login === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El usuario/correo y la contraseña son obligatorios.']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :login OR username = :login LIMIT 1');
    $stmt->execute([':login' => $login]);
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
