<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../auth/conexion.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->economico)) {
    $stmt = $conn->prepare("DELETE FROM unidades WHERE economico = ?");
    $stmt->bind_param("s", $data->economico);
    
    if($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Unidad eliminada del sistema."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error de base de datos."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Faltan datos."]);
}
?>