<?php
// 1. INICIAR SESIÓN 
session_start();

// 2. RUTA CORREGIDA 
require_once __DIR__ . '/conexion.php';
header('Content-Type: application/json');

// RECIBIR DATOS
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "mensaje" => "Por favor llena todos los campos"]);
    exit;
}

// CONSULTA SEGURA
$sql = "SELECT id, nombre, email, rol FROM usuarios WHERE email = ? AND password = ? AND estado = 'Activo'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$resultado = $stmt->get_result();

if ($fila = $resultado->fetch_assoc()) {
    
    // 3. GUARDAMOS LA MEMORIA DEL USUARIO EN EL SERVIDOR
    $_SESSION['usuario_id'] = $fila['id'];
    $_SESSION['nombre']     = $fila['nombre'];
    $_SESSION['rol']        = $fila['rol'];

    echo json_encode([
        "success" => true,
        "mensaje" => "Bienvenido",
        "usuario" => $fila
    ]);
} else {
    echo json_encode([
        "success" => false,
        "mensaje" => "Correo o contraseña incorrectos"
    ]);
}

$stmt->close();
$conn->close();
?>