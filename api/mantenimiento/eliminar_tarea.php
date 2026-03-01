<?php
require_once 'conexion.php';
header('Content-Type: application/json');

$id = $_GET['id']; // Lo recibimos por la URL

$sql = "DELETE FROM mantenimientos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
$conn->close();
?>