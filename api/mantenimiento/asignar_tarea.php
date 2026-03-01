<?php
session_start();
require_once '../auth/conexion.php';
header('Content-Type: application/json');

// Atrapamos los datos enviados por JS
$economico = $_POST['economico'] ?? '';
$tipo_servicio = $_POST['tipo_servicio'] ?? '';
$prioridad = $_POST['prioridad'] ?? '';
$sistema = $_POST['sistema'] ?? '';
$operador_asignado = $_POST['operador_asignado'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';

// Definimos los valores por defecto
$estado = 'PENDING'; // Toda tarea nueva nace como pendiente
$fecha_ejecucion = date('Y-m-d H:i:s'); // Se registra el día de hoy automáticamente

if (empty($economico) || empty($operador_asignado)) {
    echo json_encode(['success' => false, 'mensaje' => 'Faltan datos obligatorios.']);
    exit;
}

// Preparamos la inserción segura a la tabla mantenimientos
$sql = "INSERT INTO mantenimientos (economico, tipo_servicio, prioridad, sistema, operador_asignado, descripcion, estado, fecha_ejecucion) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'mensaje' => 'Error SQL: ' . $conn->error]);
    exit;
}

$stmt->bind_param("ssssssss", $economico, $tipo_servicio, $prioridad, $sistema, $operador_asignado, $descripcion, $estado, $fecha_ejecucion);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Error al guardar.']);
}

$stmt->close();
$conn->close();
?>