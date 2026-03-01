<?php
// Asegúrate de que esta ruta sea correcta según tu estructura
require_once __DIR__ . '/../auth/conexion.php';
header('Content-Type: application/json');

// Agregamos el LEFT JOIN para "pegar" la información de la unidad al mantenimiento
$sql = "SELECT 
            m.id, 
            m.economico, 
            m.sistema,
            m.prioridad,
            COALESCE(m.fecha_ejecucion, m.proximo_servicio) as fecha_principal,
            m.hora_inicio, 
            m.hora_fin, 
            m.operador_asignado, 
            m.estado,
            m.duracion,
            u.marca,
            u.modelo
        FROM mantenimientos m
        LEFT JOIN unidades u ON m.economico = u.economico
        ORDER BY m.fecha_ejecucion DESC, m.id DESC";

$result = $conn->query($sql);
$data = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Si la columna 'duracion' de la BD viene vacía, la calculamos nosotros
        if (empty($row['duracion']) || $row['duracion'] == '---') {
            $duracion_calc = "En proceso";
            if (!empty($row['hora_inicio']) && !empty($row['hora_fin'])) {
                $inicio = new DateTime($row['hora_inicio']);
                $fin = new DateTime($row['hora_fin']);
                $diff = $inicio->diff($fin);
                $duracion_calc = $diff->format('%h h %i m');
            }
            $row['duracion_calculada'] = $duracion_calc;
        } else {
            // Si ya existe en la BD, usamos esa
            $row['duracion_calculada'] = $row['duracion'];
        }

        // Normalizar el estado para que el JS lo entienda
        if (empty($row['estado'])) {
            $row['estado'] = (!empty($row['hora_fin'])) ? 'completed' : 'pending';
        }

        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>