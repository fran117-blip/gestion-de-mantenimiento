<?php
require_once '../auth/conexion.php';
header('Content-Type: application/json');

// Tu consulta ya está perfecta para traer a todos
$sql = "SELECT id, nombre FROM usuarios WHERE rol IN ('Mecanico', 'Operador', 'Electrico', 'Mensajero', 'Chofer')";
$result = $conn->query($sql);

$mecanicos = [];
if ($result) {
    while($row = $result->fetch_assoc()) {
        $mecanicos[] = $row; // Aquí quitamos el error del corchete
    }
}

echo json_encode($mecanicos);
$conn->close();
?>