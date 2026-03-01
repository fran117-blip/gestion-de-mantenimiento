<?php
require_once __DIR__ . '/../auth/conexion.php';
header('Content-Type: application/json');

// Contamos cuántos servicios hay por cada tipo (Preventivo vs Correctivo)
$sql = "SELECT tipo_servicio, COUNT(*) as total 
        FROM mantenimientos 
        GROUP BY tipo_servicio";

$result = $conn->query($sql);
$data = ['Preventivo' => 0, 'Correctivo' => 0];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Normalizamos el nombre para que coincida con nuestras etiquetas
        $tipo = (strpos(strtolower($row['tipo_servicio']), 'prev') !== false) ? 'Preventivo' : 'Correctivo';
        $data[$tipo] += (int)$row['total'];
    }
}

echo json_encode($data);
$conn->close();
?>