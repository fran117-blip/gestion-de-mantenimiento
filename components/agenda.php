<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-800">Agenda de Mantenimiento</h2>
    <button class="btn-primary" onclick="abrirModalAgenda()" style="background:#4f46e5; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-weight:600; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-calendar-plus"></i> Nueva Cita
    </button>
</div>

<div class="tabla-contenedor card">
    <table>
        <thead>
            <tr>
                <th class="text-xs font-bold text-slate-500 uppercase">UNIDAD</th>
                <th class="text-xs font-bold text-slate-500 uppercase">MODELO</th>
                <th class="text-xs font-bold text-slate-500 uppercase">MOTIVO</th>
                <th class="text-xs font-bold text-slate-500 uppercase">FECHA</th>
                <th class="text-xs font-bold text-slate-500 uppercase">DÍAS</th>
                <th class="text-xs font-bold text-slate-500 uppercase">MECÁNICO</th>
                <th class="text-xs font-bold text-slate-500 uppercase">ESTADO</th>
            </tr>
        </thead>
        <tbody id="agenda-datos-reales">
            <tr>
                <td colspan="7" class="text-center p-8 text-slate-400">Cargando datos del servidor...</td>
            </tr>
        </tbody>
    </table>
</div>

<div id="modal-agenda" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 23, 42, 0.7); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div class="modal-content" style="background:white; padding:35px; border-radius:20px; width:480px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25);">
        <div class="flex justify-between items-center mb-6">
            <h3 style="font-size: 1.4rem; font-weight: 700; color: #1e293b;">Programar Mantenimiento</h3>
            <button onclick="cerrarModalAgenda()" style="background:none; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer;">&times;</button>
        </div>

        <form id="form-nueva-agenda">
            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:8px; color:#64748b; font-weight:600;">Unidad (N° Económico)</label>
                <select name="unidad_id" id="select-unidades-agenda" class="input-modal" required style="width:100%; padding:12px; border:2px solid #f1f5f9; border-radius:12px;">
                    <option value="" disabled selected>Selecciona una unidad...</option>
                </select>
            </div>

            <div style="display:flex; gap:15px; margin-bottom: 15px;">
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:8px; color:#64748b; font-weight:600;">Motivo</label>
                    <select name="motivo" class="input-modal" style="width:100%; padding:12px; border:2px solid #f1f5f9; border-radius:12px;">
                        <option value="Preventivo">Preventivo</option>
                        <option value="Predictivo">Predictivo</option>
                        <option value="Correctivo">Correctivo</option>
                    </select>
                </div>
                <div style="flex:1;">
                    <label style="display:block; margin-bottom:8px; color:#64748b; font-weight:600;">Fecha Programada</label>
                    <input type="date" name="fecha" class="input-modal" required style="width:100%; padding:12px; border:2px solid #f1f5f9; border-radius:12px;">
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; color:#64748b; font-weight:600;">Asignar Mecánico Responsable</label>
                <select name="usuario_id" id="select-mecanicos-agenda" class="input-modal" required style="width:100%; padding:12px; border:2px solid #f1f5f9; border-radius:12px;">
                    <option value="" disabled selected>Cargando mecánicos...</option>
                </select>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="button" onclick="cerrarModalAgenda()" style="flex:1; padding:12px; border-radius:12px; background:#f1f5f9; color:#64748b; font-weight:600; border:none; cursor:pointer;">Cancelar</button>
                <button type="submit" style="flex:1; padding:12px; border-radius:12px; background:#4f46e5; color:white; font-weight:600; border:none; cursor:pointer;">Guardar en Agenda</button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- FUNCIONES DEL MODAL ---
    function abrirModalAgenda() {
        document.getElementById('modal-agenda').style.display = 'flex';
        cargarSelectoresAgenda();
    }

    function cerrarModalAgenda() {
        document.getElementById('modal-agenda').style.display = 'none';
        document.getElementById('form-nueva-agenda').reset();
    }

    function cargarSelectoresAgenda() {
        // Cargar Mecánicos (Usa la API corregida)
        fetch('api/usuarios/obtener_mecanicos.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('select-mecanicos-agenda');
                select.innerHTML = '<option value="" disabled selected>Selecciona un mecánico...</option>';
                data.forEach(m => {
                    select.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
                });
            });

        // Cargar Unidades
        fetch('api/unidades/obtener_unidades.php')
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('select-unidades-agenda');
                select.innerHTML = '<option value="" disabled selected>Selecciona una unidad...</option>';
                data.forEach(u => {
                    select.innerHTML += `<option value="${u.id}">${u.num_economico} - ${u.modelo}</option>`;
                });
            });
    }

    // --- CARGAR TABLA (Tu lógica original mejorada) ---
    function cargarAgenda() {
        const tbody = document.getElementById('agenda-datos-reales');
        if (!tbody) return;

        fetch('api/mantenimiento/obtener_agenda.php')
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center p-8">No hay mantenimientos pendientes.</td></tr>';
                    return;
                }

                tbody.innerHTML = data.map(a => {
                    const hoy = new Date();
                    hoy.setHours(0,0,0,0);
                    const fechaServicio = new Date(a.proximo_servicio + 'T00:00:00');
                    const diffTime = fechaServicio - hoy;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

                    let badgeHTML = '';
                    let diasClass = 'font-bold';
                    let diasTexto = diffDays;

                    if (diffDays < 0) {
                        badgeHTML = '<span class="badge" style="background:#fee2e2; color:#ef4444; padding:4px 8px; border-radius:12px; font-weight:bold; font-size:0.7rem;">VENCIDO</span>';
                        diasClass += ' text-red-600';
                    } else if (diffDays === 0) {
                        badgeHTML = '<span class="badge" style="background:#fef3c7; color:#d97706; padding:4px 8px; border-radius:12px; font-weight:bold; font-size:0.7rem;">HOY</span>';
                        diasClass += ' text-orange-500';
                    } else {
                        badgeHTML = '<span class="badge" style="background:#eff6ff; color:#3b82f6; padding:4px 8px; border-radius:12px; font-weight:bold; font-size:0.7rem;">PROGRAMADO</span>';
                        diasClass += ' text-slate-600';
                    }

                    return `
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td class="p-4 font-bold text-slate-700">${a.economico}</td>
                            <td class="p-4 text-slate-600">${a.modelo}</td>
                            <td class="p-4 text-slate-600">${a.tipo_servicio || 'Preventivo'}</td>
                            <td class="p-4 font-medium text-slate-700">${fechaServicio.toLocaleDateString('es-MX')}</td>
                            <td class="p-4 ${diasClass}">${diasTexto}</td>
                            <td class="p-4 text-slate-600">${a.ultimo_mecanico || 'Sin Asignar'}</td>
                            <td class="p-4">${badgeHTML}</td>
                        </tr>
                    `;
                }).join('');
            });
    }

    // Inicialización y envío
    document.addEventListener('DOMContentLoaded', () => {
        cargarAgenda();
        
        document.getElementById('form-nueva-agenda').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const datos = Object.fromEntries(formData.entries());

            fetch('api/mantenimiento/guardar_agenda.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    Swal.fire('¡Éxito!', 'Cita programada correctamente', 'success');
                    cerrarModalAgenda();
                    cargarAgenda();
                }
            });
        });
    });
</script>
