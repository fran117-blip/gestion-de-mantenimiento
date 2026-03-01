<?php
require_once __DIR__ . '/../auth/conexion.php';
header('Content-Type: application/json');

// Solo seleccionamos lo que SÍ existe en tu tabla
$sql = "SELECT id, nombre, email, rol, estado FROM usuarios ORDER BY nombre ASC";

$result = $conn->query($sql);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>