<?php
require_once '../auth/conexion.php';
header('Content-Type: application/json');

$stats = [
    'total' => 0,
    'en_taller' => 0,
    'completados' => 0
];

$resTotal = $conn->query("SELECT COUNT(*) as total FROM mantenimientos");
if($resTotal) $stats['total'] = $resTotal->fetch_assoc()['total'];

$resTaller = $conn->query("SELECT COUNT(*) as en_taller FROM mantenimientos WHERE estado NOT IN ('COMPLETED', 'FINALIZADO', 'Finalizado') OR estado IS NULL");
if($resTaller) $stats['en_taller'] = $resTaller->fetch_assoc()['en_taller'];

$resCompletados = $conn->query("SELECT COUNT(*) as completados FROM mantenimientos WHERE estado IN ('COMPLETED', 'FINALIZADO', 'Finalizado')");
if($resCompletados) $stats['completados'] = $resCompletados->fetch_assoc()['completados'];

echo json_encode($stats);
$conn->close();
?>