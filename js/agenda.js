function cargarAgenda() {
    // Usamos el ID exacto que tienes en tu agenda.php
    const contenedor = document.getElementById('agenda-datos-reales');
    if (!contenedor) return;
    
    fetch('api/mantenimiento/obtener_agenda.php')
        .then(response => response.json())
        .then(datosRecibidos => {
            // Limpiamos el mensaje de "Cargando..."
            contenedor.innerHTML = '';

            if (datosRecibidos.length === 0) {
                contenedor.innerHTML = '<tr><td colspan="7" class="text-center p-8 text-slate-400">No hay citas programadas.</td></tr>';
                return;
            }

            // Construimos las filas usando los nombres exactos de tu JSON
            let htmlFinal = '';
            datosRecibidos.forEach(item => {
                const hoy = new Date();
                hoy.setHours(0,0,0,0);
                
                // Procesamos 'proximo_servicio' que viene de tu DB
                const fechaServicio = new Date(item.proximo_servicio + 'T00:00:00');
                const diff = Math.ceil((fechaServicio - hoy) / (1000 * 60 * 60 * 24));

                let estiloDias = 'text-slate-600';
                let textoBadge = 'PROGRAMADO';
                let colorBadge = 'background:#eff6ff; color:#3b82f6;';

                if (diff < 0) {
                    estiloDias = 'text-red-600 font-bold';
                    textoBadge = 'VENCIDO';
                    colorBadge = 'background:#fee2e2; color:#ef4444;';
                }

                htmlFinal += `
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td class="p-4 font-bold text-slate-700">${item.economico}</td>
                        <td class="p-4 text-slate-600">${item.modelo}</td>
                        <td class="p-4 text-slate-600">${item.tipo_servicio}</td> 
                        <td class="p-4 text-slate-700">${fechaServicio.toLocaleDateString('es-MX')}</td>
                        <td class="p-4 ${estiloDias}">${diff}</td>
                        <td class="p-4 text-slate-600">${item.operador_asignado || 'Sin Asignar'}</td>
                        <td class="p-4">
                            <span style="${colorBadge} padding:4px 8px; border-radius:12px; font-weight:bold; font-size:0.7rem;">
                                ${textoBadge}
                            </span>
                        </td>
                    </tr>`;
            });
            contenedor.innerHTML = htmlFinal;
        })
        .catch(error => {
            console.error("Error cargando la agenda:", error);
            contenedor.innerHTML = '<tr><td colspan="7" class="text-center p-8 text-red-500">Error al conectar con la base de datos.</td></tr>';
        });
}

// Lógica para llenar los selectores del Modal
function cargarSelectoresAgenda() {
    // Cargar Mecánicos
    fetch('api/usuarios/obtener_mecanicos.php')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('select-mecanicos-agenda');
            if(!select) return;
            select.innerHTML = '<option value="" disabled selected>Selecciona un mecánico...</option>';
            data.forEach(m => {
                select.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
            });
        });

    // Cargar Unidades usando 'economico'
    fetch('api/unidades/obtener_unidades.php')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('select-unidades-agenda');
            if(!select) return;
            select.innerHTML = '<option value="" disabled selected>Selecciona una unidad...</option>';
            data.forEach(u => {
                select.innerHTML += `<option value="${u.economico}">${u.economico} - ${u.modelo}</option>`;
            });
        });
}

// Inicialización
document.addEventListener('DOMContentLoaded', () => {
    cargarAgenda();
});