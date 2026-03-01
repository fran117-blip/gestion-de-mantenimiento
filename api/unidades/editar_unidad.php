<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../auth/conexion.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->truckId)) {
    
    $query = "UPDATE unidades SET placas = ?, estado_fisico = ?, anio = ? WHERE economico = ?";
    
    $stmt = $conn->prepare($query);
    
    if(!$stmt) {
        echo json_encode(["success" => false, "message" => "Error SQL: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("ssss", 
        $data->placas, 
        $data->estatus, 
        $data->anio,
        $data->truckId
    );
    
    if($stmt->execute()){
        echo json_encode(["success" => true, "message" => "Unidad actualizada."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al ejecutar: " . $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Faltan datos."]);
}
?>