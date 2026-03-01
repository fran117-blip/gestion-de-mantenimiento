<?php
// Datos de conexión para XAMPP estándar
$servidor = "127.0.0.1";
$usuario = "root";
$password = ""; // <--- En XAMPP esto debe estar VACÍO
$base_datos = "flota_db";

// Crear conexión
$conn = new mysqli($servidor, $usuario, $password, $base_datos);

// Verificar si hubo error
if ($conn->connect_error) {
    // Esto es importante: Si falla, enviamos un JSON de error para que JS lo entienda
    die(json_encode(["success" => false, "message" => "Fallo SQL: " . $conn->connect_error]));
}

// Configurar caracteres (tildes y ñ)
$conn->set_charset("utf8mb4");
?>