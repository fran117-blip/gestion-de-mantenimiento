<style>
    /* 1. Header más espacioso */
    .header-historial {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px; /* MÁS ESPACIO AQUÍ (Antes era menos) */
    }

    /* 2. Barra de Filtros */
    .filtros-box {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        align-items: center;
    }

    .buscador-wrapper {
        position: relative;
        flex: 1;
    }

    .input-busqueda {
        width: 100%;
        padding: 12px 12px 12px 45px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        color: #475569;
        outline: none;
    }

    /* 3. Botón Excel (Verde) */
    .btn-verde-excel {
        background-color: #10b981;
        color: white;
        padding: 12px 24px; /* Un poco más grande */
        border-radius: 8px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
        transition: transform 0.2s;
    }
    .btn-verde-excel:hover { transform: translateY(-2px); background-color: #059669; }

    /* 4. Tabla y Espaciado */
    .tabla-pro-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .tabla-pro { width: 100%; border-collapse: collapse; }
    
    .tabla-pro th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        padding: 20px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .tabla-pro td {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    /* 5. BOTONES DE ACCIÓN */
    .acciones-grid {
        display: flex;
        gap: 10px; /* Separación entre botones */
        align-items: center;
    }

    .btn-cuadrado {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1rem;
        border: 1px solid transparent;
    }

    /* Botón Blanco (Imprimir) */
    .btn-imprimir {
        background: white;
        border-color: #cbd5e1;
        color: #64748b;
    }
    .btn-imprimir:hover { border-color: #3b82f6; color: #3b82f6; }

    /* Botón Rojo (Borrar) */
    .btn-borrar {
        background: #fee2e2;
        color: #ef4444;
        border: none;
    }
    .btn-borrar:hover { background: #fecaca; color: #dc2626; }

    /* Pastillas de Estado */
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
    }
    .badge-ok { background: #dcfce7; color: #166534; }
    .badge-wait { background: #fef3c7; color: #b45309; }

    /* =========================================
       MODO OSCURO EXCLUSIVO PARA HISTORIAL
       ========================================= */
    html.dark .header-historial h2 { color: #f8fafc; }
    
    html.dark .filtros-box { 
        background: #1e293b; 
        border-color: #334155; 
        box-shadow: none; 
    }
    
    html.dark .input-busqueda { 
        background: #0f172a; 
        border-color: #475569; 
        color: #f8fafc; 
    }
    
    html.dark .tabla-pro-container { 
        background: #1e293b; 
        border-color: #334155; 
    }
    
    html.dark .tabla-pro th { 
        background: #0f172a; 
        color: #94a3b8; 
        border-bottom-color: #334155; 
    }
    
    html.dark .tabla-pro td { 
        border-bottom-color: #334155; 
        color: #e2e8f0; 
    }

    html.dark .btn-imprimir { 
        background: #0f172a; 
        border-color: #334155; 
        color: #cbd5e1; 
    }
    html.dark .btn-imprimir:hover { 
        border-color: #60a5fa; 
        color: #60a5fa; 
    }

    html.dark .btn-borrar { 
        background: #450a0a; 
        color: #fca5a5; 
    }
    html.dark .btn-borrar:hover { 
        background: #7f1d1d; 
        color: #fecaca; 
    }

    html.dark .badge-ok { 
        background-color: #064e3b; 
        color: #6ee7b7; 
        border: 1px solid #047857; 
    }
    
    html.dark .badge-wait { 
        background-color: #451a03; 
        color: #fcd34d; 
        border: 1px solid #78350f; 
    }
</style>

<div class="header-historial">
    <h2 class="text-2xl font-bold text-slate-800">Historial de Servicios</h2>
    <button class="btn-verde-excel" onclick="exportarHistorialReal()">
        <i class="fas fa-file-excel"></i> Exportar a Excel
    </button>
</div>

<div class="filtros-box">
    <div class="buscador-wrapper">
        <i class="fas fa-search" style="position:absolute; left:15px; top:14px; color:#94a3b8;"></i>
        <input type="text" id="buscador-historial" placeholder="Buscar por unidad, mecánico..." class="input-busqueda" onkeyup="filtrarHistorial()">
    </div>
    <select class="input-busqueda" style="width: 200px;" onchange="cargarHistorial(this.value)">
        <option value="todos">Todos los estados</option>
        <option value="finalizado">Finalizados</option>
    <option value="pendiente">Pendientes</option> </select>
</div>

<div class="tabla-pro-container">
    <table class="tabla-pro" id="tabla-historial-completa">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Unidad</th>
                <th>Detalle Trabajo</th>
                <th>Duración</th>
                <th>Mecánico</th>
                <th>Estado</th>
                <th style="text-align: center;">Acción</th>
            </tr>
        </thead>
        <tbody id="tabla-historial-body">
            <tr><td colspan="8" class="text-center p-8">
                <i class="fas fa-spinner fa-spin mr-2"></i> Cargando historial...
            </td></tr>
        </tbody>
    </table>
</div>

<script src="js/historial.js"></script>
<script>
    // 1. Función de filtrado instantánea
    function filtrarHistorial() {
        const input = document.getElementById("buscador-historial");
        const filtro = input.value.toUpperCase();
        const tabla = document.getElementById("tabla-historial-completa");
        const filas = tabla.getElementsByTagName("tr");

        for (let i = 1; i < filas.length; i++) {
            const textoFila = filas[i].textContent || filas[i].innerText;
            filas[i].style.display = textoFila.toUpperCase().indexOf(filtro) > -1 ? "" : "none";
        }
    }

    // 2. EXPORTACIÓN REAL A EXCEL (Sin alertas)
    function exportarHistorialReal() {
        const tabla = document.getElementById("tabla-historial-completa");
        if (!tabla) return;

        // Clonamos la tabla para no quitar la columna de acción de la vista del usuario
        let tablaClon = tabla.cloneNode(true);
        
        // Eliminamos la columna "Acción" para que no salga en el Excel
        Array.from(tablaClon.querySelectorAll('tr')).forEach(tr => {
            if(tr.lastElementChild) tr.removeChild(tr.lastElementChild);
        });

        const contenido = tablaClon.outerHTML;
        const blob = new Blob(['\ufeff' + contenido], { type: 'application/vnd.ms-excel' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        
        a.href = url;
        a.download = "Historial_Mantenimiento_" + new Date().toLocaleDateString() + ".xls";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // 3. ELIMINACIÓN CON SWEETALERT (Llamada desde el botón rojo de la tabla)
    function confirmarEliminarHistorial(id) {
        Swal.fire({
            title: '¿Eliminar registro #' + id + '?',
            text: "Esta acción es permanente y afectará las estadísticas.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
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
                        Swal.fire('¡Eliminado!', data.message, 'success');
                        if (typeof cargarHistorial === 'function') cargarHistorial();
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(typeof cargarHistorial === 'function') cargarHistorial();
    });
</script>