<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Mantenimiento</title>
    <link rel="stylesheet" href="styles.css?v=17">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', system-ui, sans-serif; }
        
        body, html { 
            min-height: 100vh; 
            margin: 0; 
            padding: 0; 
            background-color: #f1f5f9; 
        }
        
        .login-bg {
            min-height: 100vh; 
            width: 100%;
            background: linear-gradient(rgba(15, 23, 42, 0.6), rgba(15, 23, 42, 0.6)), 
                        url('assets/img/fondo_login.webp') center/cover no-repeat; 
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            text-align: center;
            transition: background-color 0.3s ease, border-color 0.3s ease; 
        }

        .logo-icon { font-size: 3rem; color: #2563eb; margin-bottom: 10px; }
        h2 { margin: 0; color: #1e293b; font-size: 1.5rem; font-weight: 800; transition: color 0.3s ease; }
        p { color: #64748b; font-size: 0.9rem; margin-top: 5px; margin-bottom: 30px; transition: color 0.3s ease; }

        .form-group { margin-bottom: 20px; text-align: left; position: relative; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 8px; transition: color 0.3s ease; }
        
        .input-control {
            width: 100%; padding: 12px 15px;
            border: 1px solid #cbd5e1; border-radius: 10px;
            font-size: 1rem; color: #334155; outline: none; transition: all 0.2s;
            background-color: white;
        }
        .input-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }

        .toggle-password {
            position: absolute; right: 15px; top: 38px;
            color: #94a3b8; cursor: pointer; transition: color 0.2s;
        }
        .toggle-password:hover { color: #3b82f6; }

        .btn-primary {
            width: 100%; padding: 12px; background: #2563eb; color: white;
            border: none; border-radius: 10px; font-size: 1rem; font-weight: 600;
            cursor: pointer; transition: background 0.2s; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
            margin-bottom: 15px;
        }
        .btn-primary:hover { background: #1d4ed8; }

        .btn-secondary {
            width: 100%; padding: 12px; background: white; color: #475569;
            border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s; display: flex; justify-content: center; align-items: center; gap: 8px;
        }
        .btn-secondary:hover { background: #f8fafc; color: #1e293b; border-color: #94a3b8; }

        #mensaje-error { 
            color: #ef4444; background-color: #fef2f2; border: 1px solid #fecaca;
            padding: 10px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; 
            margin-bottom: 15px; display: none; 
        }

        /* REGLAS PARA MODO OSCURO */
        html.dark body, html.dark html { background-color: #0f172a !important; }
        html.dark .login-card { background-color: #1e293b; border: 1px solid #334155; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); }
        html.dark h2 { color: #f8fafc; }
        html.dark p { color: #94a3b8; }
        html.dark label { color: #cbd5e1; }
        html.dark .input-control { background-color: #0f172a; color: #f8fafc; border-color: #475569; }
        html.dark .input-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25); }
        html.dark .btn-secondary { background-color: #0f172a; color: #cbd5e1; border-color: #475569; }
        html.dark .btn-secondary:hover { background-color: #334155; color: #f8fafc; border-color: #64748b; }

        /* ARREGLO PARA SWEETALERT EN LOGIN */
        .swal2-icon { box-sizing: content-box !important; }
        html.dark .swal2-popup { background-color: #1e293b !important; color: #f8fafc !important; }
        html.dark .swal2-title, html.dark .swal2-html-container { color: #f8fafc !important; }
    </style>
</head>
<body>

    <div class="login-bg">
        <div class="login-card">
            <i class="fas fa-check-double logo-icon"></i>
            <h2>Sistema Taller</h2>
            <p>Ingreso de Personal</p>

            <div id="mensaje-error">Correo o contraseña incorrectos.</div>

            <form id="formLogin">
                <div class="form-group">
                    <label>Correo Electrónico</label>
                    <input type="email" name="email" class="input-control" value="admin@test.com" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" id="passInput" class="input-control" value="1234" required>
                    <i class="fas fa-eye toggle-password" id="togglePass" title="Mostrar/Ocultar"></i>
                </div>

                <button type="submit" class="btn-primary">Iniciar Sesión</button>
                
                <button type="button" class="btn-secondary" onclick="abrirConsultaUnidad()">
                    <i class="fas fa-search"></i> Consultar Estado de Unidad
                </button>
            </form>
        </div>
    </div>

    <script>
        // Ocultar/Mostrar contraseña
        document.getElementById('togglePass').addEventListener('click', function() {
            const passInput = document.getElementById('passInput');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });

        // Inicio de sesión normal
        document.getElementById('formLogin').addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const formData = new FormData(this);
            const msjError = document.getElementById('mensaje-error');
            msjError.style.display = 'none'; 

            fetch('api/auth/login.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const rolUsuario = data.usuario.rol.toLowerCase();

                    Swal.fire({
                        icon: 'success',
                        title: '¡Bienvenido!',
                        text: 'Sesión iniciada como ' + data.usuario.rol,
                        showConfirmButton: false,
                        timer: 1500 
                    }).then(() => {
                        if (rolUsuario === 'administrador') {
                            window.location.href = 'index.php';
                        } else {
                            window.location.href = 'mecanico.php'; 
                        }
                    });

                } else {
                    msjError.innerText = data.mensaje || 'Datos incorrectos';
                    msjError.style.display = 'block';
                }
            })
            .catch(err => {
                console.error('Error:', err);
                msjError.innerText = 'Error de conexión con el servidor.';
                msjError.style.display = 'block';
            });
        });

        /* ==========================================
           LÓGICA DEL BUSCADOR DE UNIDADES
           ========================================== */
        function abrirConsultaUnidad() {
            Swal.fire({
                title: 'Consultar Estado',
                text: 'Ingresa el N° Económico o Placas del vehículo:',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    placeholder: 'Ej: T-005 o 58-AK-9'
                },
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-search"></i> Buscar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                showLoaderOnConfirm: true,
                preConfirm: (unidadBuscada) => {
                    if (!unidadBuscada) {
                        Swal.showValidationMessage('Debes ingresar un N° Económico o Placas para buscar.');
                        return false;
                    }
                    
                    return fetch(`api/unidades/consultar_estado.php?unidad=${encodeURIComponent(unidadBuscada)}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Error de conexión con la base de datos.`);
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = result.value;
                    
                    if (data.success) {
                        let colorEstado = '#10b981'; // Verde si está Operativo
                        let iconoEstado = 'success';
                        let subtitulo = 'La unidad está libre y operativa.';
                        let detallesTaller = '';

                        // Si está en mantenimiento, armamos la sección con fecha y tipo de servicio
                        if (data.data.estatus === 'En Mantenimiento') {
                            colorEstado = '#f59e0b'; // Naranja si está en taller
                            iconoEstado = 'warning';
                            subtitulo = 'La unidad se encuentra actualmente en el taller.';
                            
                            // Formatear la fecha para que se vea bonita (Día/Mes/Año)
                            let fechaBonita = data.data.fecha_ingreso;
                            if(fechaBonita && fechaBonita.includes('-')) {
                                const partes = fechaBonita.split('-'); // asume formato AAAA-MM-DD
                                if(partes.length === 3) fechaBonita = `${partes[2]}/${partes[1]}/${partes[0]}`;
                            }

                            detallesTaller = `
                                <div style="margin-top: 15px; padding-top: 12px; border-top: 1px dashed #cbd5e1;">
                                    <p style="margin-bottom: 6px; font-size: 0.95rem;">
                                        <i class="fas fa-calendar-alt" style="color: #94a3b8; width: 20px;"></i> 
                                        <strong>Fecha Ingreso:</strong> ${fechaBonita || 'No registrada'}
                                    </p>
                                    <p style="margin-bottom: 0; font-size: 0.95rem;">
                                        <i class="fas fa-tools" style="color: #94a3b8; width: 20px;"></i> 
                                        <strong>Tipo Servicio:</strong> ${data.data.tipo_servicio || 'Revisión General'}
                                    </p>
                                </div>
                            `;
                        }

                        // Mostramos la tarjeta final
                        Swal.fire({
                            title: `Unidad ${data.data.economico}`,
                            html: `
                                <div style="text-align: left; padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; margin-top: 10px;">
                                    <p style="margin-bottom: 8px; font-size: 1.05rem;"><strong>Marca/Modelo:</strong> ${data.data.marca} ${data.data.modelo}</p>
                                    <p style="margin-bottom: 8px; font-size: 1.05rem;"><strong>Estado:</strong> <span style="color: ${colorEstado}; font-weight: 800; text-transform: uppercase;">${data.data.estatus}</span></p>
                                    
                                    ${detallesTaller}

                                    <p style="font-size: 0.85rem; color: #64748b; margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
                                        <i class="fas fa-info-circle"></i> ${subtitulo}
                                    </p>
                                </div>
                            `,
                            icon: iconoEstado,
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: 'Cerrar'
                        });
                    } else {
                        // Si no existe, tiramos error rojo
                        Swal.fire({
                            icon: 'error',
                            title: 'No Encontrada',
                            text: data.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                }
            });
        }
    </script>
</body>
</html>