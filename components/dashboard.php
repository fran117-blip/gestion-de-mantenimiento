<div class="flex justify-between items-center mb-8" style="padding-top: 10px;">
    <h2 class="text-2xl font-bold text-slate-800">Resumen General</h2>
    <div class="flex gap-3">
        <button class="btn-primario bg-emerald-500" onclick="abrirModalCamion()">
            <i class="fas fa-truck"></i> Nuevo Camión
        </button>
        <button class="btn-primario bg-blue-600" onclick="abrirModalAsignar()">
            <i class="fas fa-plus"></i> Asignar Servicio
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="card card-azul">
        <span class="text-slate-500 text-sm font-medium">Total Servicios</span>
        <h2 id="stat-total" class="text-4xl font-bold mt-1">...</h2>
    </div>
    <div class="card card-naranja">
        <span class="text-slate-500 text-sm font-medium">En Taller</span>
        <h2 id="stat-taller" class="text-4xl font-bold mt-1">...</h2>
    </div>
    <div class="card card-verde">
        <span class="text-slate-500 text-sm font-medium">Completados</span>
        <h2 id="stat-completados" class="text-4xl font-bold mt-1">...</h2>
    </div>
</div>

<style>
    .btn-ver-todo { 
        background-color: white; 
        border: 1px solid #e2e8f0; 
        color: #475569; 
        padding: 6px 14px; 
        border-radius: 8px; 
        font-size: 0.85rem; 
        font-weight: 600; 
        cursor: pointer; 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
        transition: all 0.2s ease; 
        text-decoration: none; 
    }

    .btn-ver-todo:hover { 
        background-color: #f8fafc; 
        border-color: #cbd5e1; 
        color: #1e293b; 
    }

    .tabla-actividad { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 10px; 
    }

    .tabla-actividad th { 
        text-align: left; 
        padding: 16px 10px; 
        border-bottom: 1px solid #f1f5f9; 
        color: #94a3b8; 
        font-size: 0.75rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 0.05em; 
    }

    .tabla-actividad td { 
        padding: 16px 10px; 
        border-bottom: 1px solid #f8fafc; 
        color: #334155; 
        font-size: 0.95rem; 
        vertical-align: middle; 
    }

    .unidad-info-container {
        display: flex;
        flex-direction: column;
    }

    .unidad-bold { 
        font-weight: 700; 
        color: #1e293b; 
    }

    .unidad-detalles {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: normal;
        margin-top: 2px;
    }

    .badge-pill { 
        padding: 6px 14px; 
        border-radius: 50px; 
        font-size: 0.75rem; 
        font-weight: 800; 
        text-transform: uppercase; 
        display: inline-block; 
        letter-spacing: 0.05em; 
    }

    .badge-pending { 
        background-color: #fef3c7; 
        color: #b45309; 
    }

    .badge-completed { 
        background-color: #dcfce7; 
        color: #166534; 
    }

    .modal-overlay { 
        position: fixed; 
        top: 0; 
        left: 0; 
        width: 100%; 
        height: 100%; 
        background-color: rgba(15, 23, 42, 0.6); 
        display: none; 
        justify-content: center; 
        align-items: center; 
        z-index: 1000; 
        backdrop-filter: blur(2px); 
    }

    .modal-overlay.activo { 
        display: flex; 
    }

    .modal-caja { 
        background: white; 
        width: 100%; 
        max-width: 600px; 
        border-radius: 16px; 
        padding: 30px; 
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); 
        animation: deslizarArriba 0.3s ease-out; 
    }

    @keyframes deslizarArriba { 
        from { 
            opacity: 0; 
            transform: translateY(20px); 
        } 
        to { 
            opacity: 1; 
            transform: translateY(0); 
        } 
    }

    .form-grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 15px; 
        margin-bottom: 15px; 
    }

    .grupo-input {
        margin-bottom: 15px;
    }

    .grupo-input label { 
        display: block; 
        font-size: 0.8rem; 
        color: #64748b; 
        font-weight: 600; 
        margin-bottom: 6px; 
    }

    .input-elegante { 
        width: 100%; 
        padding: 10px 14px; 
        border: 1px solid #e2e8f0; 
        border-radius: 8px; 
        font-size: 0.95rem; 
        color: #334155; 
        outline: none; 
        background-color: white; 
        transition: border-color 0.2s; 
    }

    .input-elegante:focus { 
        border-color: #3b82f6; 
    }

    .botones-modal { 
        display: flex; 
        gap: 15px; 
        margin-top: 25px; 
    }

    .btn-cancelar { 
        flex: 1; 
        padding: 12px; 
        background: white; 
        color: #475569; 
        border: 1px solid #cbd5e1; 
        border-radius: 8px; 
        font-weight: 600; 
        cursor: pointer; 
    }

    .btn-generar { 
        flex: 1; 
        padding: 12px; 
        background: #2563eb; 
        color: white; 
        border: none; 
        border-radius: 8px; 
        font-weight: 600; 
        cursor: pointer; 
    }
</style>

<div class="dashboard-grid">
    <div class="card">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-slate-700 text-lg">Actividad Reciente</h3>
            <button class="btn-ver-todo" onclick="navegar('historial')">
                Ver Todo <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        
        <table class="tabla-actividad">
            <thead>
                <tr>
                    <th>UNIDAD</th>
                    <th>SISTEMA / SERVICIO</th>
                    <th>PRIORIDAD</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody id="tabla-actividad-reciente">
                <tr>
                    <td colspan="4" class="text-center py-6 text-slate-400">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Cargando actividad...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 class="font-bold text-slate-700 mb-6">Frecuencia de Servicios</h3>
        <div style="height: 250px;">
            <canvas id="graficaPareto"></canvas>
        </div>
    </div>
</div>

<div id="modal-asignar" class="modal-overlay">
    <div class="modal-caja">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Nueva Orden de Servicio</h3>
        
        <form id="form-nueva-orden" onsubmit="guardarOrden(event)">
            
            <div class="form-grid">
                <div class="grupo-input">
                    <label>N° Económico</label>
                    <input type="text" class="input-elegante font-bold text-blue-600" id="input-economico" placeholder="Ej: T-105" required autocomplete="off" style="text-transform: uppercase;">
                </div>
                <div class="grupo-input">
                    <label>Prioridad</label>
                    <select class="input-elegante" id="select-prioridad" required>
                        <option value="Alta">🔴 Alta</option>
                        <option value="Media" selected>🟡 Media</option>
                        <option value="Baja">🟢 Baja</option>
                    </select>
                </div>
            </div>

            <div class="form-grid">
                <div class="grupo-input">
                    <label>Tipo Servicio</label>
                    <select class="input-elegante" id="select-tipo" required>
                        <option value="Preventivo">Preventivo</option>
                        <option value="Correctivo">Correctivo</option>
                        <option value="Predictivo">Predictivo</option>
                    </select>
                </div>
                <div class="grupo-input">
                    <label>Sistema Afectado</label>
                    <select class="input-elegante" id="select-sistema" required>
                        <option value="Motor">Motor</option>
                        <option value="Electrico">Electrico</option>
                        <option value="Suspensión">Suspensión</option>
                        <option value="Frenos">Frenos</option>
                        <option value="Transmisión">Transmisión</option>
                        <option value="General">General</option>
                    </select>
                </div>
            </div>

            <div class="grupo-input">
                <label>Asignar a Mecánico</label>
                <select class="input-elegante" id="select-mecanico" required>
                    <option value="" disabled selected>-- Cargando mecánicos... --</option>
                </select>
            </div>

            <div class="grupo-input">
                <label>Instrucciones de Trabajo</label>
                <textarea class="input-elegante" id="input-instrucciones" rows="3" placeholder="Detalles del trabajo a realizar..." required></textarea>
            </div>

            <div class="botones-modal">
                <button type="button" class="btn-cancelar" onclick="cerrarModalAsignar()">Cancelar</button>
                <button type="submit" class="btn-generar">Generar Orden</button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalAsignar() { 
        document.getElementById('modal-asignar').classList.add('activo'); 
        cargarMecanicos();
    }

    function cerrarModalAsignar() { 
        document.getElementById('modal-asignar').classList.remove('activo'); 
        document.getElementById('form-nueva-orden').reset();
    }

    function cargarActividadReciente() {
        const tbody = document.getElementById('tabla-actividad-reciente');
        
        fetch('api/mantenimiento/obtener_historial.php')
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-slate-400">No hay servicios registrados.</td></tr>';
                    return;
                }
                
                tbody.innerHTML = '';
                // Mostramos solo los últimos 5 para el Dashboard
                data.slice(0, 5).forEach(item => {
                    const estado = (item.estado || '').toUpperCase();
                    const badgeClass = (estado === 'COMPLETED' || estado === 'COMPLETADO') ? 'badge-completed' : 'badge-pending';
                    const badgeText = (estado === 'COMPLETED' || estado === 'COMPLETADO') ? 'COMPLETO' : 'PENDIENTE';
                    
                    tbody.innerHTML += `
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td>
                                <div class="unidad-info-container">
                                    <span class="unidad-bold">${item.economico}</span>
                                    <span class="unidad-detalles">${item.marca || ''} ${item.modelo || ''}</span>
                                </div>
                            </td>
                            <td>${item.sistema || 'General'}</td>
                            <td>${item.prioridad || 'Media'}</td>
                            <td><span class="badge-pill ${badgeClass}">${badgeText}</span></td>
                        </tr>
                    `;
                });
            })
            .catch(err => {
                console.error("Error cargando actividad:", err);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-6 text-red-500">Error al conectar.</td></tr>';
            });
    }

    function cargarEstadisticas() {
        fetch('api/mantenimiento/obtener_estadisticas.php')
            .then(res => res.json())
            .then(data => {
                document.getElementById('stat-total').innerText = data.total || 0;
                document.getElementById('stat-taller').innerText = data.en_taller || 0;
                document.getElementById('stat-completados').innerText = data.completados || 0;
            });
    }

    function cargarMecanicos() {
        fetch('api/usuarios/obtener_mecanicos.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('select-mecanico');
                select.innerHTML = '<option value="" disabled selected>-- Seleccione Mecánico --</option>';
                data.forEach(m => {
                    select.innerHTML += `<option value="${m.nombre}">${m.nombre}</option>`;
                });
            });
    }

    function guardarOrden(e) {
        e.preventDefault();
        
        const payload = {
            economico: document.getElementById('input-economico').value.trim().toUpperCase(),
            mecanico: document.getElementById('select-mecanico').value,
            prioridad: document.getElementById('select-prioridad').value,
            sistema: document.getElementById('select-sistema').value,
            instrucciones: document.getElementById('input-instrucciones').value
        };

        const btn = document.querySelector('.btn-generar');
        btn.innerText = "Guardando...";
        btn.disabled = true;

        fetch('api/mantenimiento/guardar_servicio.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Orden Generada!',
                    text: data.message,
                    confirmButtonColor: '#2563eb'
                });
                cerrarModalAsignar();
                cargarActividadReciente();
                cargarEstadisticas();
                
                // --- AQUÍ SE ACTUALIZA EL PARETO AL GUARDAR ---
                if(typeof inicializarGraficaPareto === 'function'){
                    inicializarGraficaPareto();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .finally(() => {
            btn.innerText = "Generar Orden";
            btn.disabled = false;
        });
    }

    // Inicialización
    setTimeout(() => {
        cargarActividadReciente();
        cargarEstadisticas();
    }, 200);
</script>