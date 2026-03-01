function cargarFlota() {
    const tbody = document.getElementById('tabla-unidades-cuerpo');
    if (!tbody) return;

    fetch('api/unidades/obtener_unidades.php')
        .then(res => {
            if (!res.ok) throw new Error('Error en el servidor');
            return res.json();
        })
        .then(data => {
            if (!data || data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10">No hay unidades registradas.</td></tr>';
                return;
            }

            tbody.innerHTML = data.map(u => {
                const unidadData = encodeURIComponent(JSON.stringify(u));
                const estatus = (u.estatus || 'OPERATIVO').toUpperCase();
                
                // Clases de color según el estado
                const badgeClass = (estatus === 'MANTENIMIENTO' || estatus === 'EN TALLER') 
                    ? 'badge-wait' 
                    : 'badge-ok';

                return `
                    <tr>
                        <td class="font-bold">${u.economico}</td>
                        <td>${u.marca} ${u.modelo}</td>
                        <td>${u.anio}</td>
                        <td>${u.placas}</td>
                        <td><span class="badge ${badgeClass}">${estatus}</span></td>
                        <td style="text-align:center;">
                            <div class="acciones-grid" style="display:flex; gap:10px; justify-content:center;">
                                <button class="btn-icon" onclick="prepararEdicion('${unidadData}')">
                                    <i class="fas fa-pencil-alt text-blue-600"></i>
                                </button>
                                <button class="btn-icon" onclick="confirmarEliminarUnidad('${u.economico}')">
                                    <i class="fas fa-trash text-red-500"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        })
        .catch(err => {
            console.error("Fallo al cargar flota:", err);
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Error: No se pudo conectar con la base de datos.</td></tr>';
        });
}