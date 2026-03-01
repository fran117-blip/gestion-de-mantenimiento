<?php
// 1. Desactivamos la visualización de errores para el entorno de producción
// pero los dejamos aquí comentados por si necesitas depurar más.
error_reporting(0); 
header("Content-Type: application/json");

// 2. RUTA CORREGIDA:
require_once dirname(__DIR__) . '/auth/conexion.php'; 

// 3. Obtener los datos del cuerpo de la petición (JSON)
$data = json_decode(file_get_contents("php://input"));

if ($data && isset($data->id)) {
    $id = intval($data->id);

    // Preparamos la consulta (Asegúrate que la tabla se llame 'mantenimientos')
    $stmt = $conn->prepare("DELETE FROM mantenimientos WHERE id = ?");
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(["success" => true, "message" => "Registro eliminado exitosamente."]);
            } else {
                echo json_encode(["success" => false, "message" => "No se encontró el registro con ID: " . $id]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Error al ejecutar: " . $conn->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Error en la consulta: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "ID no proporcionado o formato inválido."]);
}

$conn->close();
?>