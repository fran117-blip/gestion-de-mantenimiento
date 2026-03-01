<?php
require_once 'conexion.php';
header('Content-Type: application/json');

$sql = "SELECT * FROM mantenimientos ORDER BY FIELD(prioridad, 'Alta', 'Media', 'Baja'), id DESC";
$result = $conn->query($sql);

$tareas = [];
while($row = $result->fetch_assoc()) {
    $tareas[] = [
        'id' => $row['id'],
        'unitType' => $row['tipo_unidad'],
        'year' => $row['anio'],
        'truckId' => $row['economico'],
        'system' => $row['sistema'],
        'type' => $row['tipo_servicio'],
        'operator' => $row['operador_asignado'],
        'priority' => $row['prioridad'],
        'desc' => $row['descripcion'], // Descripción de la falla (Admin)
        'desc_closure' => $row['descripcion_cierre'], // Trabajo realizado (Mecánico)
        'status' => $row['estado'],
        'date' => $row['fecha_ejecucion'],
        'startTime' => $row['hora_inicio'],
        'endTime' => $row['hora_fin'],
        'duration' => $row['duracion'],
        'image' => $row['foto_evidencia'],
        'nextService' => $row['proximo_servicio'] // <--- NUEVO CAMPO
    ];
}
echo json_encode($tareas);
$conn->close();
?>