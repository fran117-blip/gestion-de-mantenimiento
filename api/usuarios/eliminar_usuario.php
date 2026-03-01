<?php
// Usamos la conexión centralizada que ya actualizamos
require_once '../auth/conexion.php'; 
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminación física (Borrado permanente de la base de datos)
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        // Si hay un error (por ejemplo, si el usuario tiene registros vinculados)
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "ID no proporcionado"]);
}

$conn->close();
?>