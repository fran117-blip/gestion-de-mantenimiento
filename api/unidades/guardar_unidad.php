<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// ¡AQUÍ ESTÁ LA CORRECCIÓN DE LA RUTA A TU CONEXIÓN!
require_once '../auth/conexion.php';

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->economico) && 
    !empty($data->marca) && 
    !empty($data->modelo)
){
    // Quitamos la columna 'tipo' igual que hicimos en el inventario masivo para no romper tu BD
    $query = "INSERT INTO unidades (economico, placas, marca, modelo, anio, estado_fisico) VALUES (?, ?, ?, ?, ?, 'Operativo')";
    
    $stmt = $conn->prepare($query);
    
    if(!$stmt) {
        echo json_encode(["success" => false, "message" => "Error SQL: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("sssss", 
        $data->economico, 
        $data->placas,
        $data->marca, 
        $data->modelo, 
        $data->anio
    );
    
    if($stmt->execute()){
        echo json_encode(["success" => true, "message" => "Unidad registrada correctamente."]);
    } else {
        // Código 1062 es para identificar si la placa o el económico ya están repetidos
        if ($conn->errno == 1062) {
            echo json_encode(["success" => false, "message" => "El N° Económico o Placas ya existen en el sistema."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al guardar: " . $stmt->error]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Faltan datos obligatorios."]);
}
?>