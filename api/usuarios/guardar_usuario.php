<?php
require_once '../auth/conexion.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$pass = "1234"; // Contraseña fija para simplificar

$sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $data['name'], $data['email'], $pass, $data['role']);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}
$conn->close();
?>