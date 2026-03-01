<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) { 
    
    $image_urls = [];
    $upload_dir = 'uploads/'; 
    
    if (isset($data->images) && is_array($data->images)) {
        foreach ($data->images as $index => $base64_img) {
            if (preg_match('/^data:image\/(.*?);base64,/', $base64_img, $matches)) {
                
                $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64_img));
                
                $file_name = 'evidencia_' . $data->id . '_' . $index . '_' . time() . '.jpg';
                $upload_path = $upload_dir . $file_name;
                
                if (file_put_contents($upload_path, $img)) {
                    $image_urls[] = $upload_path; 
                }
            }
        }
    }
    
    $images_to_save = json_encode($image_urls);

    $query = "UPDATE mantenimientos SET 
          descripcion_cierre = ?, 
          fecha_ejecucion = ?, 
          hora_inicio = ?, 
          hora_fin = ?, 
          duracion = ?, 
          estado = 'completed',
          proximo_servicio = ?,
          foto_evidencia = ?  
          WHERE id = ?";
    
    $prox = !empty($data->nextDate) ? $data->nextDate : NULL;
    
    $stmt = $conn->prepare($query);
    
    $stmt->bind_param("sssssssi", 
    $data->desc, 
    $data->date, 
    $data->startTime, 
    $data->endTime, 
    $data->duration,
    $prox,
    $images_to_save, 
    $data->id
);
    
    if($stmt->execute()){

        $find = $conn->query("SELECT economico FROM mantenimientos WHERE id = " . $data->id);
        $row = $find->fetch_assoc();
        $camion = $row['economico'];

        $freeTruck = "UPDATE unidades SET estado_fisico = 'Operativo' WHERE economico = ?";
        $stmt3 = $conn->prepare($freeTruck);
        $stmt3->bind_param("s", $camion);
        $stmt3->execute();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan datos o ID de tarea"]);
}
?>