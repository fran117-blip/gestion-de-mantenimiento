<?php
// 1. SEGURIDAD: Iniciar sesión y validar
session_start();

// Si no está logueado, lo mandamos al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Si un Administrador entra aquí por error, lo regresamos a su panel principal
if (strtolower($_SESSION['rol']) === 'administrador') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Operador - Sistema Taller</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-slate-50">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col hidden md:flex">
            <div class="p-6 text-center border-b border-slate-100 mb-4">
                <div class="w-12 h-12 bg-indigo-600 text-white rounded-xl flex items-center justify-center text-2xl mx-auto mb-3 shadow-lg shadow-indigo-200">
                    <i class="fas fa-wrench"></i>
                </div>
                <h1 class="font-bold text-indigo-600 tracking-wide text-sm">ÁREA TÉCNICA</h1>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <div class="nav-item active flex items-center p-3 text-indigo-700 bg-indigo-50 rounded-lg font-bold cursor-pointer">
                    <i class="fas fa-clipboard-list w-6"></i> Mis Tareas
                </div>
                <div class="nav-item flex items-center p-3 text-slate-500 hover:bg-slate-50 rounded-lg font-medium cursor-pointer" onclick="alert('Abriendo escáner QR...')">
                    <i class="fas fa-qrcode w-6"></i> Escanear Unidad
                </div>
            </nav>

            <div class="p-4 border-t border-slate-200">
                <a href="api/auth/logout.php" class="flex items-center w-full p-3 text-red-500 hover:bg-red-50 rounded-lg font-bold transition-colors">
                    <i class="fas fa-sign-out-alt w-6"></i> Cerrar Sesión
                </a>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto p-8">
            
            <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">
                        Bienvenido, <?php echo $_SESSION['nombre']; ?>
                    </h2>
                    <p class="text-slate-500 mt-1">Revisa tus mantenimientos pendientes para hoy.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="font-bold text-slate-700"><?php echo $_SESSION['nombre']; ?></p>
                        <p class="text-xs text-indigo-500 font-bold uppercase"><?php echo $_SESSION['rol']; ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </div>

            <div id="contenedor-tareas" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <div class="col-span-full text-center py-10 text-slate-400">
                    <i class="fas fa-spinner fa-spin text-3xl mb-3"></i>
                    <p>Buscando tareas asignadas...</p>
                </div>
            </div>

        </main>
    </div>

    <script src="js/auth.js"></script>

    <script>
        function cargarMisTareas() {
            const contenedor = document.getElementById('contenedor-tareas');
            
            // Llama al archivo "cerebro" 
            fetch('api/mantenimiento/obtener_tareas_mecanico.php')
                .then(res => res.json())
                .then(data => {
                    // Si el arreglo está vacío, mostramos el mensaje de "Estás al día"
                    if(data.length === 0) {
                        contenedor.innerHTML = `
                            <div class="col-span-full bg-white p-10 rounded-2xl shadow-sm border border-slate-100 text-center">
                                <i class="fas fa-check-circle text-6xl text-emerald-400 mb-4"></i>
                                <h3 class="text-2xl font-bold text-slate-700">¡Estás al día!</h3>
                                <p class="text-slate-500 mt-2">No tienes tareas pendientes asignadas en este momento.</p>
                            </div>
                        `;
                        return;
                    }

                    // Si hay tareas, generamos una tarjeta por cada camión
                    contenedor.innerHTML = data.map(tarea => `
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 hover:shadow-lg transition-all relative overflow-hidden">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold uppercase tracking-wide">
                                    ${tarea.estado || 'PENDIENTE'}
                                </span>
                                <span class="text-sm font-bold text-slate-300">#${tarea.id}</span>
                            </div>
                            
                            <h3 class="text-3xl font-black text-slate-800 mb-1">${tarea.economico}</h3>
                            <p class="text-indigo-600 font-bold text-sm mb-5">
                                <i class="fas fa-wrench mr-1"></i> ${tarea.tipo_servicio || 'Mantenimiento'}
                            </p>
                            
                            <div class="flex items-center text-sm text-slate-500 mb-6 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <i class="fas fa-calendar-alt mr-2 text-slate-400"></i>
                                Programado: <span class="ml-1 font-semibold text-slate-700">${tarea.fecha_principal}</span>
                            </div>
                            
                            <div class="flex gap-3">
                                <button onclick="alert('Función para iniciar tarea en desarrollo...')" class="flex-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 py-2.5 rounded-xl font-bold transition-colors text-sm border border-indigo-200">
                                    <i class="fas fa-play mr-1"></i> Iniciar
                                </button>
                                <button onclick="alert('Función para finalizar tarea en desarrollo...')" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white py-2.5 rounded-xl font-bold transition-colors text-sm shadow-lg shadow-emerald-200">
                                    <i class="fas fa-check mr-1"></i> Finalizar
                                </button>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(err => {
                    console.error("Error al cargar tareas:", err);
                    contenedor.innerHTML = '<p class="text-red-500 col-span-full text-center">Error al conectar con la base de datos.</p>';
                });
        }

        // Ejecutar la función apenas termine de cargar la pantalla
        document.addEventListener('DOMContentLoaded', cargarMisTareas);
    </script>
</body>
</html>