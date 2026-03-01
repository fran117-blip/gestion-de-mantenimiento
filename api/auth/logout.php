<?php
// 1. Abrimos la memoria de la sesión
session_start();

// 2. Destruimos toda la información del usuario
session_destroy();

// 3. Lo redirigimos a la fuerza a la pantalla de login
header("Location: ../../login.php");
exit;
?>