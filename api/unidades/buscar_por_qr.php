<?php
header('Content-Type: application/json');
require '../auth/conexion.php'; // Ajusta la ruta a tu conexion.php

$eco = $_GET['economico'] ?? '';

if (!$eco) {
    echo json_encode(['error' => 'No se proporcionó código']);
    exit;
}

try {
    // Buscamos en tu tabla de unidades que vimos en phpMyAdmin
    $stmt = $conn->prepare("SELECT * FROM unidades WHERE economico = :eco LIMIT 1");
    $stmt->execute([':eco' => $eco]);
    $unidad = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($unidad) {
        echo json_encode($unidad);
    } else {
        echo json_encode(['error' => 'Unidad no encontrada']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}