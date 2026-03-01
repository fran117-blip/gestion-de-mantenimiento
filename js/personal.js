// js/personal.js

function cargarPersonal() {
    const contenedor = document.getElementById('grid-personal');
    if (!contenedor) return;

    fetch('api/usuarios/obtener_usuarios.php')
        .then(res => res.json())
        .then(data => {
            if (data.length === 0) {
                contenedor.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #94a3b8;">No hay usuarios registrados.</p>';
                return;
            }

            contenedor.innerHTML = data.map(u => {
                // Lógica de iconos según el rol del usuario
                let icono = 'fa-user';
                const rol = (u.rol || '').toLowerCase();
                if (rol.includes('admin')) icono = 'fa-user-shield';
                else if (rol.includes('mecanico') || rol.includes('operador')) icono = 'fa-wrench';
                else if (rol.includes('electrico')) icono = 'fa-bolt';      
                else if (rol.includes('mensajero')) icono = 'fa-motorcycle'; 

                // Configuración visual del estado (Activo/Inactivo)
                const esActivo = u.estado === 'Activo';
                const bgEstado = esActivo ? '#dcfce7' : '#f1f5f9';
                const colorTexto = esActivo ? '#166534' : '#64748b';

                return `
                <div class="personal-card">
                    <div class="card-header">
                        <div class="header-left">
                            <div class="avatar-circle">
                                <i class="fas ${icono}"></i>
                            </div>
                            <div class="user-info">
                            <h3>${u.nombre}</h3>
                            <span class="user-role">${u.rol}</span>
                            </div>
                                </p>
                        </div>
                        <span style="background:${bgEstado}; color:${colorTexto}; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: bold;">
                            ${u.estado}
                        </span>
                    </div>
                    
                    <div class="user-contact" style="background: #f8fafc; padding: 10px; border-radius: 8px; display: flex; align-items: center; gap: 10px; color: #475569; font-size: 0.9rem;">
                        <i class="fas fa-envelope"></i>
                        <span>${u.email}</span>
                    </div>

                    <div class="card-actions" style="display: flex; gap: 10px; margin-top: 15px;">
                        <button class="btn-editar-u" onclick="abrirEditarUsuario(${u.id})" style="flex:1; padding:8px; border-radius:8px; border:1px solid #e2e8f0; background:white; cursor:pointer; font-weight: 600;">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="btn-baja-u" onclick="confirmarBaja(${u.id}, '${u.nombre}')" style="flex:1; padding:8px; border-radius:8px; border:none; background:#fee2e2; color:#ef4444; cursor:pointer; font-weight: 600;">
                            <i class="fas fa-user-minus"></i> Baja
                        </button>
                    </div>
                </div>`;
            }).join('');
        })
        .catch(err => console.error("Error al cargar personal:", err));
}

// --- LÓGICA DEL MODAL DE REGISTRO ---
function abrirModalUsuario() {
    const modal = document.getElementById('modal-usuario');
    if(modal) modal.style.display = 'flex';
}

function cerrarModalUsuario() {
    const modal = document.getElementById('modal-usuario');
    const form = document.getElementById('form-nuevo-usuario');
    if(modal) modal.style.display = 'none';
    if(form) form.reset();
}

function registrarUsuario(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const datos = {
        name: formData.get('nombre'),
        email: formData.get('correo'),
        role: formData.get('rol')
    };

    fetch('api/usuarios/guardar_usuario.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Swal.fire('¡Éxito!', 'Usuario registrado correctamente', 'success');
            cerrarModalUsuario();
            cargarPersonal(); 
        } else {
            Swal.fire('Error', 'No se pudo guardar: ' + (data.error || 'Desconocido'), 'error');
        }
    });
}

// Lógica de eliminación definitiva
function confirmarBaja(id, nombre) {
    Swal.fire({
        title: '¿Eliminar a ' + nombre + '?',
        text: "Esta acción borrará al usuario permanentemente de la base de datos.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`api/usuarios/eliminar_usuario.php?id=${id}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Eliminado', 'El registro ha sido borrado.', 'success');
                        cargarPersonal(); 
                    }
                });
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    cargarPersonal();
    const form = document.getElementById('form-nuevo-usuario');
    if(form) form.addEventListener('submit', registrarUsuario);
});