<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Asegúrate de que la ruta a tu conexión sea la correcta
require_once '../auth/conexion.php'; 

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->economico) &&
    !empty($data->mecanico) &&
    !empty($data->prioridad) &&
    !empty($data->sistema) &&
    !empty($data->instrucciones)
) {
    // Limpiamos espacios y pasamos a mayúsculas por si el usuario escribió "t-118" en minúsculas
    $economico = strtoupper(trim($data->economico));

    // 1. Validar que la unidad SÍ exista en nuestro inventario primero
    $checkStmt = $conn->prepare("SELECT id FROM unidades WHERE economico = ?");
    $checkStmt->bind_param("s", $economico);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "El N° Económico '$economico' no existe en la flota."]);
        exit;
    }

    // 2. Si existe, preparamos la inserción en la tabla de mantenimientos
    // Usamos las columnas exactas que vimos en tu phpMyAdmin
    $query = "INSERT INTO mantenimientos (economico, sistema, prioridad, descripcion, operador_asignado, estado, fecha_ejecucion) 
              VALUES (?, ?, ?, ?, ?, 'PENDIENTE', CURDATE())";
    
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Error SQL: " . $conn->error]);
        exit;
    }
    
    // Vinculamos los 5 datos
    $stmt->bind_param("sssss", 
        $economico, 
        $data->sistema, 
        $data->prioridad, 
        $data->instrucciones, 
        $data->mecanico
    );
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true, 
            "message" => "La orden de servicio para la unidad $economico ha sido generada con éxito."
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al guardar la orden: " . $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Por favor, llena todos los campos de la orden."]);
}
?>