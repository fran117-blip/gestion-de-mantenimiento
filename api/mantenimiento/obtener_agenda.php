<?php
require_once __DIR__ . '/../auth/conexion.php'; 
header('Content-Type: application/json');

// Seleccionamos los datos reales de tu tabla 'mantenimientos'
$sql = "SELECT 
            m.id, 
            m.economico, 
            u.modelo, 
            m.proximo_servicio, 
            m.operador_asignado, 
            m.tipo_servicio 
        FROM mantenimientos m
        JOIN unidades u ON m.economico = u.economico
        WHERE m.proximo_servicio IS NOT NULL 
        AND m.proximo_servicio != ''
        ORDER BY m.proximo_servicio ASC";

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