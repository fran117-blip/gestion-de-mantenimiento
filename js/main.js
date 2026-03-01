function inicializarGraficaPareto() {
    const canvas = document.getElementById('graficaPareto');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    
    // 1. Consultamos los datos reales a la API
    fetch('api/mantenimiento/obtener_pareto.php')
        .then(res => res.json())
        .then(datosApi => {
            // Si ya existe una gráfica en este canvas, la destruimos para evitar duplicados al redibujar
            if (window.miGraficaPareto) {
                window.miGraficaPareto.destroy();
            }

            // 2. Creamos la gráfica con los datos recibidos de la base de datos
            window.miGraficaPareto = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Preventivo', 'Correctivo'],
                    datasets: [{
                        data: [datosApi.Preventivo || 0, datosApi.Correctivo || 0],
                        backgroundColor: ['#ef4444', '#f59e0b'], 
                        borderRadius: 8,
                        barThickness: 40
                    }]
                },
                options: {
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            ticks: { display: false },
                            beginAtZero: true 
                        },
                        y: { 
                            grid: { display: false } 
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        })
        .catch(err => console.error("Error al cargar datos de Pareto:", err));
}

function navegar(seccion) {
    // 1. Ocultamos todas las vistas
    document.querySelectorAll('.seccion-vista').forEach(s => {
        s.style.display = 'none';
    });

    // 2. Mostramos solo la que elegimos
    const destino = document.getElementById('sec-' + seccion);
    if (destino) {
        destino.style.display = 'block';
    }

    // 3. Cambiamos el estilo del botón activo en el sidebar
    document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
    
    // Usamos el evento para marcar el botón actual
    if (event && event.currentTarget) {
        event.currentTarget.classList.add('active');
    }

    // 4. Actualización Automática al navegar
    if (seccion === 'dashboard') {
        inicializarGraficaPareto();
        // Si tienes funciones de carga en dashboard.php, se llamarán aquí
        if (typeof cargarActividadReciente === 'function') cargarActividadReciente();
        if (typeof cargarEstadisticas === 'function') cargarEstadisticas();
    }

    if (seccion === 'flota' && typeof cargarFlota === 'function') {
        cargarFlota();
    }
}

function mostrarSeccion(seccionId) {
    // Ocultar todas las secciones
    const secciones = ['dashboard', 'flota', 'agenda', 'historial', 'personal'];
    secciones.forEach(id => {
        const el = document.getElementById('sec-' + id);
        if (el) el.style.display = 'none';
    });

    // Mostrar la elegida
    const destino = document.getElementById('sec-' + seccionId);
    if (destino) destino.style.display = 'block';
    
    // Recarga de datos específica por sección
    if (seccionId === 'historial' && typeof cargarHistorial === 'function') {
        cargarHistorial(); 
    }
    
    if (seccionId === 'dashboard') {
        inicializarGraficaPareto();
    }
}

// --- Función para el buscador en tiempo real ---
function filtrarFlota() {
    let input = document.getElementById("buscador-flota");
    if (!input) return; // Seguridad
    let filtro = input.value.toUpperCase();
    let tabla = document.getElementById("tabla-unidades");
    let filas = tabla.getElementsByTagName("tr");

    for (let i = 1; i < filas.length; i++) {
        let textoFila = filas[i].textContent || filas[i].innerText;
        filas[i].style.display = textoFila.toUpperCase().indexOf(filtro) > -1 ? "" : "none";
    }
}

// --- Función para preparar el modal para EDITAR ---
function prepararEdicion(datosJson) {
    const unidad = JSON.parse(decodeURIComponent(datosJson));
    
    // 1. Cambiamos el título del modal y el texto del botón (viven en index.php)
    document.querySelector('#modalNuevoCamion h3').innerHTML = `<i class="fas fa-edit text-blue-600"></i> Editar Unidad ${unidad.economico}`;
    document.querySelector('#formNuevoCamion .btn-guardar').innerHTML = `<i class="fas fa-save"></i> Guardar Cambios`;
    
    // 2. Llenamos los campos del formulario
    document.querySelector('input[name="economico"]').value = unidad.economico;
    document.querySelector('input[name="economico"]').readOnly = true; // El económico no se debe cambiar
    document.querySelector('input[name="placas"]').value = unidad.placas;
    document.querySelector('input[name="modelo"]').value = unidad.modelo;
    document.querySelector('input[name="anio"]').value = unidad.anio;
    
    // Manejo de la marca
    const selectMarca = document.getElementById('selectMarca');
    const marcasExistentes = Array.from(selectMarca.options).map(opt => opt.value);
    
    if (marcasExistentes.includes(unidad.marca)) {
        selectMarca.value = unidad.marca;
        document.getElementById('divOtraMarca').style.display = 'none';
    } else {
        selectMarca.value = 'Otra';
        verificarOtraMarca();
        document.getElementById('inputOtraMarca').value = unidad.marca;
    }

    // 3. Abrimos el modal
    abrirModalCamion();
}

// --- Función para buscar en el Historial ---
function filtrarHistorial() {
    let input = document.getElementById("buscador-historial");
    let filtro = input.value.toUpperCase();
    let tabla = document.getElementById("tabla-historial-completa");
    let filas = tabla.getElementsByTagName("tr");

    // Saltamos la fila 0 (encabezados)
    for (let i = 1; i < filas.length; i++) {
        let textoFila = filas[i].textContent || filas[i].innerText;
        filas[i].style.display = textoFila.toUpperCase().indexOf(filtro) > -1 ? "" : "none";
    }
}

// Ejecutar inicialización al cargar el documento por primera vez
document.addEventListener('DOMContentLoaded', function() {
    inicializarGraficaPareto();
});