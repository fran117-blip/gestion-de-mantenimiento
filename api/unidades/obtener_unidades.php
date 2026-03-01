<?php
require_once '../auth/conexion.php';
header('Content-Type: application/json');

// Subconsulta súper robusta que busca si hay servicios activos (PENDING, EN TALLER, etc.)
$sql = "SELECT 
            u.economico, 
            u.marca, 
            u.modelo, 
            u.anio, 
            u.placas, 
            u.estado_fisico,
            (SELECT COUNT(*) FROM mantenimientos m WHERE m.economico = u.economico AND UPPER(m.estado) NOT IN ('COMPLETED', 'FINALIZADO', 'COMPLETADO')) AS en_taller
        FROM unidades u 
        ORDER BY u.economico ASC";

$result = $conn->query($sql);
$unidades = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        
        // 1. Estado original
        $estatus = empty($row['estado_fisico']) ? 'Operativo' : $row['estado_fisico'];

        // 2. ¡La conexión automática! Si está en el taller, cambiamos el texto
        if ($row['en_taller'] > 0) {
            $estatus = 'Mantenimiento';
        }
        
        // Lo mandamos limpio a JavaScript
        $row['estatus'] = $estatus;
        $unidades[] = $row;
    }
}

echo json_encode($unidades);
$conn->close();
?>