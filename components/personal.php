<style>
    /* ... (Tus estilos existentes se mantienen igual) ... */
    
    /* Estilos del Modal */
    .modal-overlay {
        display: none; 
        position: fixed; 
        top: 0; left: 0; 
        width: 100%; height: 100%; 
        background: rgba(15, 23, 42, 0.7); /* Fondo oscuro elegante */
        z-index: 9999; 
        align-items: center; 
        justify-content: center;
        backdrop-filter: blur(4px);
    }

    .modal-content {
        background: white; 
        padding: 35px; 
        border-radius: 20px; 
        width: 450px; 
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        animation: modalFadeIn 0.3s ease-out;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-group { margin-bottom: 20px; }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .input-modal {
        width: 100%;
        padding: 12px;
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        outline: none;
        transition: border-color 0.2s;
    }

    .input-modal:focus { border-color: #4f46e5; }
</style>

<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-800">Equipo de Trabajo</h2>
    <button class="btn-nuevo-usuario" onclick="abrirModalUsuario()" style="background:#4f46e5; color:white; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-weight:600; display:flex; align-items:center; gap:8px;">
        <i class="fas fa-plus"></i> Nuevo Usuario
    </button>
</div>

<div id="grid-personal" class="grid-personal">
    <div class="col-span-3 text-center py-10 text-slate-400">
        <i class="fas fa-spinner fa-spin mr-2"></i> Cargando personal...
    </div>
</div>

<div id="modal-usuario" class="modal-overlay">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-6">
            <h3 style="font-size: 1.4rem; font-weight: 700; color: #1e293b;">Registrar Usuario</h3>
            <button onclick="cerrarModalUsuario()" style="background:none; border:none; font-size:1.5rem; color:#94a3b8; cursor:pointer;">&times;</button>
        </div>

        <form id="form-nuevo-usuario">
            <div class="form-group">
                <label>Nombre Completo</label>
                <input type="text" name="nombre" class="input-modal" placeholder="Ej. Juan Mecánico" required>
            </div>

            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" class="input-modal" placeholder="correo@taller.com" required>
            </div>

            <div class="form-group">
                <label>Rol del Usuario</label>
                <select name="rol" class="input-modal" style="background: white;">
                    <option value="" disabled selected>Selecciona un puesto...</option>
                    <option value="Mecanico">Mecánico</option>
                    <option value="Operador">Operador</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Electrico">Eléctrico</option>
                    <option value="Mensajero">Mensajero</option>
                </select>
            </div>

            <div class="flex gap-3 mt-8">
                <button type="button" onclick="cerrarModalUsuario()" style="flex:1; padding:12px; border-radius:12px; background:#f1f5f9; color:#64748b; font-weight:600; border:none; cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit" style="flex:1; padding:12px; border-radius:12px; background:#4f46e5; color:white; font-weight:600; border:none; cursor:pointer; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script src="js/personal.js"></script>