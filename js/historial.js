// Variable para guardar los datos frescos de la base de datos
let registrosHistorial = [];

function cargarHistorial(filtroEstado = 'todos') {
    const tbody = document.getElementById('tabla-historial-body');
    if (!tbody) return;

    // Si ya tenemos datos, solo filtramos. Si no, los traemos.
    if (registrosHistorial.length > 0) {
        dibujarTabla(filtroEstado);
    } else {
        fetch('api/mantenimiento/obtener_historial.php')
            .then(res => res.json())
            .then(data => {
                registrosHistorial = data;
                dibujarTabla(filtroEstado);
            })
            .catch(err => console.error("Error al obtener datos:", err));
    }
}

function dibujarTabla(filtro) {
    const tbody = document.getElementById('tabla-historial-body');
    const f = filtro.toLowerCase();

    // Filtrado lógico
    const filtrados = registrosHistorial.filter(item => {
        const estadoDB = (item.estado || '').trim().toUpperCase();
        if (f === 'finalizado') return estadoDB === 'FINALIZADO' || estadoDB === 'COMPLETED' || estadoDB === 'COMPLETADO';
        if (f === 'pendiente' || f === 'pendientes') return estadoDB === 'PENDIENTE' || estadoDB === 'PENDING';
        return true; // Mostrar todos
    });

    if (filtrados.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center p-8 text-slate-400">Sin registros para: ${filtro}</td></tr>`;
        return;
    }

    // Generar las filas
    tbody.innerHTML = filtrados.map(item => {
        const estado = (item.estado || '').toUpperCase();
        const esFinalizado = estado === 'COMPLETED' || estado === 'FINALIZADO' || estado === 'COMPLETADO';
        
        const badge = esFinalizado 
            ? `<span class="badge badge-ok">COMPLETADO</span>` 
            : `<span class="badge badge-wait">PENDIENTE</span>`;

        return `
            <tr>
                <td class="font-bold">#${item.id}</td>
                <td>${item.fecha_principal}</td>
                <td class="font-bold">${item.economico}</td>
                <td>${item.tipo_servicio || 'Mantenimiento'}</td>
                <td>${item.duracion_calculada || '--'}</td>
                <td>${item.operador_asignado || 'Sin Asignar'}</td>
                <td>${badge}</td>
                <td>
                    <div class="acciones-grid">
                        ${esFinalizado ? `<button class="btn-cuadrado btn-imprimir" onclick="imprimirServicio(${item.id})"><i class="fas fa-print"></i></button>` : ''}
                        <button class="btn-cuadrado btn-borrar" onclick="confirmarBorradoV2(${item.id})"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// Función de borrado sin interferencias
function confirmarBorradoV2(id) {
    Swal.fire({
        title: '¿Confirmas eliminar el #' + id + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Eliminar ahora'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('api/mantenimiento/eliminar_historial.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire('Listo', 'Registro borrado', 'success');
                    registrosHistorial = []; // Limpiamos caché
                    cargarHistorial(); // Recarga real
                }
            });
        }
    });
}

function imprimirServicio(id) {
    window.open(`api/mantenimiento/imprimir_orden.php?id=${id}`, '_blank');
}

// Buscador manual rápido
function filtrarHistorial() {
    const texto = document.getElementById("buscador-historial").value.toUpperCase();
    const filas = document.querySelectorAll("#tabla-historial-body tr");
    filas.forEach(fila => {
        fila.style.display = fila.innerText.toUpperCase().includes(texto) ? "" : "none";
    });
}