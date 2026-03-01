// js/mantenimiento.js

async function cargarAgenda() {
    try {
        const resp = await fetch('api/mantenimiento/obtener_agenda.php');
        const datos = await resp.json();
        
        const lista = document.getElementById('lista-agenda');
        lista.innerHTML = '';

        datos.forEach(item => {
            // Lógica para el color del estado (Vencido en rojo, etc.)
            const claseEstado = item.dias_restantes < 0 ? 'status-vencido' : 'status-ok';
            
            lista.innerHTML += `
                <tr>
                    <td>${item.unidad}</td>
                    <td>${item.modelo}</td>
                    <td>${item.motivo}</td>
                    <td>${item.fecha_programada}</td>
                    <td class="${claseEstado}">${item.dias_restantes}</td>
                    <td>${item.mecanico}</td>
                    <td><span class="badge-vencido">VENCIDO</span></td>
                </tr>
            `;
        });
    } catch (error) {
        console.error("Error en agenda:", error);
    }
}