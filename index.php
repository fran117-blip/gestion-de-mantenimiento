<?php
// 1. Iniciamos la sesión para leer la memoria
session_start();

// 2. Si NO existe el usuario en la memoria, lo pateamos al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Taller - Gestión de Flota</title>
    
    <link rel="stylesheet" href="styles.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }

        function toggleTheme() {
            let htmlTag = document.documentElement;
            let iconoTema = document.getElementById('theme-icon');

            if (htmlTag.classList.contains('dark')) {
                htmlTag.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if(iconoTema) {
                    iconoTema.classList.remove('fa-sun');
                    iconoTema.classList.add('fa-moon');
                    iconoTema.style.color = ''; 
                }
            } else {
                htmlTag.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if(iconoTema) {
                    iconoTema.classList.remove('fa-moon');
                    iconoTema.classList.add('fa-sun');
                    iconoTema.style.color = '#fbbf24'; 
                }
            }
        }
    </script>
</head>
<body>

    <div class="contenedor-app">
        <aside class="sidebar">
            <?php include 'components/sidebar.php'; ?>
        </aside>

        <div class="contenido-principal">
            <header class="navbar-top">
                <?php include 'components/navbar.php'; ?>
            </header>

            <main id="area-trabajo">
                <section id="sec-dashboard" class="seccion-vista">
                    <?php include 'components/dashboard.php'; ?>
                </section>

                <section id="sec-flota" class="seccion-vista" style="display:none;">
                    <?php include 'components/flota.php'; ?>
                </section>

                <section id="sec-agenda" class="seccion-vista" style="display:none;">
                    <?php include 'components/agenda.php'; ?>
                </section>

                <section id="sec-historial" class="seccion-vista" style="display:none;">
                    <?php include 'components/historial.php'; ?>
                </section>
                <section id="sec-personal" class="seccion-vista" style="display:none;">
                    <?php include 'components/personal.php'; ?>
                </section>

                <div id="modal-qr" class="modal-qr-container" style="display:none;">
                    <div id="reader"></div>
                    <button onclick="cerrarEscaner()" class="btn-danger">Cerrar Cámara</button>
                </div>

                <div id="modalNuevoCamion" class="modal-overlay" style="display: none;">
                    <div class="modal-moderno">
                        <div class="modal-header">
                            <h3 id="tituloModalCamion"><i class="fas fa-truck-front text-primary"></i> Alta de Vehículo</h3>
                            <button type="button" class="btn-cerrar-modal" onclick="cerrarModalCamion()"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <form id="formNuevoCamion">
                                <div class="form-grid-2">
                                    <div class="input-group">
                                        <label>N° Económico</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-hashtag"></i>
                                            <input type="text" name="economico" id="inputEconomico" placeholder="Ej: T-005" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label>Placas</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-id-card"></i>
                                            <input type="text" name="placas" placeholder="Ej: 58-AK-9" required autocomplete="off">
                                        </div>
                                    </div>
                                    
                                    <div class="input-group">
                                        <label>Marca</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-industry"></i>
                                            <select name="marca" id="selectMarca" required onchange="verificarOtraMarca()">
                                                <option value="" disabled selected>-- Seleccionar Marca --</option>
                                                <option value="Freightliner">Freightliner</option>
                                                <option value="International">International</option>
                                                <option value="Kenworth">Kenworth</option>
                                                <option value="Volvo">Volvo</option>
                                                <option value="Scania">Scania</option>
                                                <option value="Mack">Mack</option>
                                                <option value="Peterbilt">Peterbilt</option>
                                                <option value="Mercedes-Benz">Mercedes-Benz</option>
                                                <option value="Volkswagen">Volkswagen</option>
                                                <option value="Iveco">Iveco</option>
                                                <option value="Otra" style="font-weight: bold; color: #2563eb;">Otra (Especificar)...</option>
                                            </select>
                                        </div>
                                        <div class="input-with-icon" id="divOtraMarca" style="display: none; margin-top: 10px;">
                                            <i class="fas fa-plus"></i>
                                            <input type="text" name="otra_marca" id="inputOtraMarca" placeholder="Escriba la nueva marca" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="input-group">
                                        <label>Modelo</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-truck"></i>
                                            <input type="text" name="modelo" placeholder="Ej: T680" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label>Año</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                            <input type="number" name="anio" placeholder="Ej: 2024" min="1990" max="2030" required>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label>Tipo de Unidad</label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-truck-moving"></i>
                                            <select name="tipo" required>
                                                <option value="Tractocamión" selected>Tractocamión</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn-cancelar" onclick="cerrarModalCamion()">Cancelar</button>
                                    <button type="submit" class="btn-guardar" id="btnGuardarCamion"><i class="fas fa-save"></i> Registrar Unidad</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="js/auth.js"></script>
    <script src="js/unidades.js"></script>
    <script src="js/mantenimiento.js"></script>
    <script src="js/agenda.js"></script>.
    <script src="js/historial.js"></script>
    <script src="js/personal.js"></script>
    <script src="js/qr-reader.js"></script>
    <script src="js/main.js"></script>

    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            if (localStorage.getItem('theme') === 'dark') {
                let iconoTema = document.getElementById('theme-icon');
                if(iconoTema) {
                    iconoTema.classList.remove('fa-moon');
                    iconoTema.classList.add('fa-sun');
                    iconoTema.style.color = '#fbbf24';
                }
            }
        });

        /* ==========================================
           LÓGICA MODAL: NUEVO CAMIÓN / EDICIÓN
           ========================================== */
        function abrirModalCamion() { 
            document.getElementById('modalNuevoCamion').style.display = 'flex'; 
        }
        
        function cerrarModalCamion() {
            document.getElementById('modalNuevoCamion').style.display = 'none';
            document.getElementById('formNuevoCamion').reset(); 
            document.getElementById('divOtraMarca').style.display = 'none';
            document.getElementById('inputOtraMarca').removeAttribute('required');
            
            // Restaurar estado inicial (por si venía de una edición)
            document.getElementById('inputEconomico').readOnly = false;
            document.getElementById('tituloModalCamion').innerHTML = '<i class="fas fa-truck-front text-primary"></i> Alta de Vehículo';
            document.getElementById('btnGuardarCamion').innerHTML = '<i class="fas fa-save"></i> Registrar Unidad';
        }

        function verificarOtraMarca() {
            var select = document.getElementById('selectMarca');
            var divOtra = document.getElementById('divOtraMarca');
            var inputOtra = document.getElementById('inputOtraMarca');

            if (select.value === 'Otra') {
                divOtra.style.display = 'flex'; 
                inputOtra.setAttribute('required', 'required'); 
                inputOtra.focus(); 
            } else {
                divOtra.style.display = 'none'; 
                inputOtra.removeAttribute('required'); 
                inputOtra.value = ''; 
            }
        }

        document.getElementById('formNuevoCamion').addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const economicoInput = document.getElementById('inputEconomico');
            const esEdicion = economicoInput.readOnly; // Detectamos si es edición
            
            var selectMarca = document.getElementById('selectMarca').value;
            var inputOtraMarca = document.getElementById('inputOtraMarca').value;
            
            // Ajustamos el payload según lo que espera el PHP (truckId para edición)
            const payload = {
                truckId: economicoInput.value,
                economico: economicoInput.value,
                placas: document.querySelector('input[name="placas"]').value,
                marca: selectMarca === 'Otra' ? inputOtraMarca : selectMarca,
                modelo: document.querySelector('input[name="modelo"]').value,
                anio: document.querySelector('input[name="anio"]').value,
                tipo: document.querySelector('select[name="tipo"]').value,
                estatus: 'Operativo'
            };

            // Decidimos a qué API enviar
            const url = esEdicion ? 'api/unidades/editar_unidad.php' : 'api/unidades/guardar_unidad.php';

            fetch(url, { 
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success', 
                        title: esEdicion ? '¡Actualizado!' : '¡Registrado!',
                        text: data.message || 'Operación realizada correctamente.',
                        confirmButtonColor: '#10b981', 
                        confirmButtonText: 'Aceptar'
                    }).then(() => { 
                        cerrarModalCamion(); 
                        if (typeof cargarFlota === 'function') {
                            cargarFlota(); // Recarga la tabla sin recargar toda la página
                        } else {
                            window.location.reload();
                        }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, confirmButtonColor: '#ef4444' });
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Error de Conexión', text: 'No se pudo contactar al servidor.', confirmButtonColor: '#ef4444' });
            });
        });

    </script>
</body>
</html>