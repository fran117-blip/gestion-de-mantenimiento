<div class="brand">
    <img src="assets/img/logo.png" alt="Logo Sistema" class="logo-pequeno">
    <span>SISTEMA TALLER</span>
</div>

<div class="view-container">
    <div class="nav-item active" onclick="navegar('dashboard')">
        <i class="fas fa-chart-pie"></i>
        <span>Dashboard</span>
    </div>

    <div class="nav-item" onclick="navegar('flota')">
        <i class="fas fa-truck-moving"></i>
        <span>Flota</span>
    </div>

    <div class="nav-item" onclick="navegar('agenda')">
        <i class="fas fa-calendar-check"></i>
        <span>Agenda</span>
    </div>

    <div class="nav-item" onclick="navegar('historial')">
        <i class="fas fa-history"></i>
        <span>Historial</span>
    </div>

    <div class="nav-item" onclick="navegar('personal')">
        <i class="fas fa-users"></i>
        <span>Personal</span>
    </div>
</div>

<style>
    .btn-salir-elegante {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        background-color: transparent; 
        color: #ef4444; 
        border-radius: 10px; 
        font-weight: 600; 
        font-size: 1rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        box-sizing: border-box;
        
        position: absolute;
        bottom: 30px; 
        width: calc(100% - 40px); 
    }
    
    .btn-salir-elegante:hover {
        background-color: #fef2f2; 
        color: #dc2626;
    }

    .btn-salir-elegante i {
        width: 30px; 
        text-align: left;
        font-size: 1.2rem;
    }

    html.dark .btn-salir-elegante {
        color: #fca5a5; 
    }
    html.dark .btn-salir-elegante:hover {
        background-color: #450a0a; 
        color: #fecaca;
    }
</style>

<button onclick="confirmarSalida()" class="btn-salir-elegante">
    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
</button>

<script>
    function confirmarSalida() {
        Swal.fire({
            title: '¿Cerrar Sesión?',
            text: "¿Estás seguro que deseas salir del sistema?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar',
            reverseButtons: true // Pone el botón "Sí" del lado derecho
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'api/auth/logout.php';
            }
        });
    }
</script>