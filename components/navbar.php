<style>
    /* Diseño de la barra superior con CSS Puro */
    .mi-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 12px 24px;
        background-color: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-sizing: border-box;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }
    .mi-navbar-titulo {
        font-size: 1.4rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        transition: color 0.3s ease;
    }
    .mi-navbar-derecha {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    /* Botón de la Luna/Sol */
    .btn-luna {
        background: none;
        border: none;
        font-size: 1.3rem;
        color: #64748b;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-luna:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }

    /* Caja del Perfil */
    .perfil-caja {
        display: flex;
        align-items: center;
        gap: 12px;
        border-left: 2px solid #e2e8f0;
        padding-left: 20px;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }
    .perfil-textos {
        text-align: right;
        display: flex;
        flex-direction: column;
    }
    .perfil-rol {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
        transition: color 0.3s ease;
    }
    .perfil-admin {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 800;
        color: #2563eb;
        letter-spacing: 0.5px;
    }
    .perfil-avatar {
        height: 42px;
        width: 42px;
        border-radius: 50%;
        background-color: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    /* =========================================
       REGLAS AUTOMÁTICAS PARA MODO OSCURO (Solo Navbar)
       ========================================= */
    html.dark .mi-navbar { background-color: #1e293b; border-bottom-color: #334155; }
    html.dark .mi-navbar-titulo, html.dark .perfil-rol { color: #f8fafc; }
    html.dark .perfil-caja { border-left-color: #334155; }
    html.dark .btn-luna { color: #f8fafc; }
    html.dark .btn-luna:hover { background-color: #334155; color: #f8fafc; }
</style>

<div class="mi-navbar">
    <h1 class="mi-navbar-titulo">Panel de Control</h1>

    <div class="mi-navbar-derecha">
        <button id="btn-theme" onclick="toggleTheme()" class="btn-luna" title="Cambiar Tema">
            <i id="theme-icon" class="fas fa-moon"></i>
        </button>

        <div class="perfil-caja">
            <div class="perfil-textos">
                <p class="perfil-rol">Jefe de Taller</p>
                <p class="perfil-admin">ADMINISTRADOR</p>
            </div>
            <div class="perfil-avatar">JE</div>
        </div>
    </div>
</div>