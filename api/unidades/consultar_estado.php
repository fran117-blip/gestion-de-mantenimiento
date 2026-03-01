<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../auth/conexion.php'; 

if (isset($_GET['unidad']) && !empty($_GET['unidad'])) {
    $unidad_buscada = $_GET['unidad'];

    // CORRECCIÓN AQUÍ: Usamos 'fecha_ejecucion' y 'sistema' de tu base de datos real
    // y los mandamos al frontend disfrazados como 'fecha_ingreso' y 'tipo_servicio'
    $query = "SELECT 
            u.economico, 
            u.marca, 
            u.modelo, 
            u.estado_fisico,
            m.fecha_ejecucion AS fecha_ingreso,
            m.sistema AS tipo_servicio
        FROM unidades u 
        LEFT JOIN mantenimientos m ON u.economico = m.economico AND UPPER(m.estado) NOT IN ('COMPLETED', 'FINALIZADO', 'COMPLETADO')
        WHERE u.economico = ? OR u.placas = ?
        ORDER BY m.fecha_ejecucion DESC LIMIT 1"; 

    $stmt = $conn->prepare($query);
    
    if(!$stmt) {
        echo json_encode(["success" => false, "message" => "Error SQL: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ss", $unidad_buscada, $unidad_buscada);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 1. Estado original
        $estatus = empty($row['estado_fisico']) ? 'Operativo' : $row['estado_fisico'];
        
        $fecha_ingreso = null;
        $tipo_servicio = null;

        // 2. Si tiene fecha_ingreso, es que el LEFT JOIN encontró un mantenimiento activo
        if (!empty($row['fecha_ingreso'])) {
            $estatus = 'En Mantenimiento';
            // Tomamos solo la fecha por si acaso trae horas
            $fecha_ingreso = explode(' ', $row['fecha_ingreso'])[0]; 
            // Mostramos el sistema afectado (Motor, Suspensión, etc.)
            $tipo_servicio = empty($row['tipo_servicio']) ? 'Revisión General' : $row['tipo_servicio'];
        }

        // Enviamos la respuesta de éxito
        echo json_encode([
            "success" => true,
            "data" => [
                "economico" => $row['economico'],
                "marca" => $row['marca'],
                "modelo" => $row['modelo'],
                "estatus" => $estatus,
                "fecha_ingreso" => $fecha_ingreso,
                "tipo_servicio" => $tipo_servicio
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "No se encontró ninguna unidad con ese N° Económico o Placas."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Por favor, ingresa un valor de búsqueda."]);
}
?>