<?php
// Asegúrate de que esta ruta hacia tu conexión es la correcta
require_once '../auth/conexion.php'; 

$catalogo = [
    'Freightliner' => ['Cascadia', 'Columbia', 'M2 112', 'FLD 120', 'Coronado'],
    'Kenworth'     => ['T680', 'T660', 'T800', 'W900', 'K270'],
    'International'=> ['LT Series', 'ProStar', 'LoneStar', 'HX Series'],
    'Volvo'        => ['VNL 860', 'VNL 760', 'VNR', 'VHD'],
    'Scania'       => ['Serie R', 'Serie S', 'Serie G', 'Serie P'],
    'Mack'         => ['Anthem', 'Granite', 'Pinnacle', 'TerraPro'],
    'Peterbilt'    => ['579', '389', '567', '379'],
    'Mercedes-Benz'=> ['Actros', 'Arocs', 'Atego', 'Zetros'],
    'Volkswagen'   => ['Constellation', 'Delivery', 'Meteor', 'Worker'],
    'Iveco'        => ['Stralis', 'S-Way', 'Trakker', 'Eurocargo']
];

$cantidades = [
    'Freightliner' => 99,
    'International'=> 36,
    'Kenworth'     => 99,
    'Volvo'        => 67,
    'Scania'       => 15,
    'Mack'         => 20,
    'Peterbilt'    => 10,
    'Mercedes-Benz'=> 10, 
    'Volkswagen'   => 5,
    'Iveco'        => 10
];

$prefijos = [
    'Freightliner' => 'FR', 'International' => 'IN', 'Kenworth' => 'KW',
    'Volvo' => 'VO', 'Scania' => 'SC', 'Mack' => 'MK', 'Peterbilt' => 'PB',
    'Mercedes-Benz' => 'MB', 'Volkswagen' => 'VW', 'Iveco' => 'IV'
];

echo "<h2><span style='color:#2563eb;'>🔄</span> Regenerando Inventario Masivo...</h2>";

// 1. LIMPIEZA TOTAL: Borramos el historial viejo PRIMERO y luego las unidades
$error_borrado = false;
if(!$conn->query("TRUNCATE TABLE mantenimientos")) $error_borrado = true;
if(!$conn->query("TRUNCATE TABLE unidades")) $error_borrado = true;

if(!$error_borrado) {
    echo "<p style='color:#64748b;'>🗑️ Historial de taller e inventario viejo eliminados con éxito. ¡Sistema limpio!</p><hr>";
} else {
    echo "<p style='color:red;'>⚠️ Hubo un problema al vaciar las tablas. Error: " . $conn->error . "</p><hr>";
}

$totalGlobal = 0;
$inicioEconomico = 100; 

// 2. Preparamos la consulta general (YA SIN LA COLUMNA 'TIPO' PARA QUE NO TRUENE)
$query = "INSERT INTO unidades (economico, marca, modelo, placas, anio, estado_fisico) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("<div style='background:#fee2e2; border: 1px solid #ef4444; padding: 15px; border-radius: 8px;'>
            <h3 style='color:#b91c1c; margin-top:0;'>¡Error Crítico!</h3>
            <p style='color:#7f1d1d;'>MySQL dijo: <b>" . $conn->error . "</b></p>
         </div>");
}

// 3. Comenzamos a inyectar los camiones
foreach ($cantidades as $marca => $cantidad) {
    $count = 0;
    $errores_marca = 0;
    
    for ($i = 0; $i < $cantidad; $i++) {
        $inicioEconomico++;
        
        $economico = "T-" . $inicioEconomico;
        $modelosDisponibles = $catalogo[$marca];
        $modeloAleatorio = $modelosDisponibles[array_rand($modelosDisponibles)];
        
        $placas = $prefijos[$marca] . "-" . rand(100, 999) . "-" . chr(rand(65, 90)); 
        $anio = rand(2015, 2024);
        $estado = 'Operativo';

        // Las "s" indican que son strings y la "i" es el año (entero)
        $stmt->bind_param("ssssis", $economico, $marca, $modeloAleatorio, $placas, $anio, $estado);
        
        // Intentamos guardar
        if ($stmt->execute()) {
            $count++;
            $totalGlobal++;
        } else {
            $errores_marca++;
            echo "<span style='color:#ef4444; font-size:12px;'>❌ Falló el $economico ($placas): " . $stmt->error . "</span><br>";
        }
    }
    
    echo "✅ <strong>$marca:</strong> Se guardaron en BD $count unidades.";
    if ($errores_marca > 0) {
        echo " <span style='color:#f59e0b;'>($errores_marca descartadas por error)</span>";
    }
    echo "<br>";
}

echo "<hr><h1 style='color:#10b981;'>✨ ¡Finalizado! Se guardaron $totalGlobal unidades en la Base de Datos.</h1>";
echo "<p>Si regresas a tu Dashboard, verás que la Actividad Reciente ya está limpia y tienes tus 371 camiones reales listos.</p>";

$stmt->close();
$conn->close();
?>