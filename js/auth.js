// js/auth.js

async function login(email, password) {
    try {
        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        const resp = await fetch('api/auth/login.php', {
            method: 'POST',
            body: formData
        });

        const data = await resp.json();

        if (data.success) {
            window.location.href = 'index.php'; // Recargamos para que PHP valide la sesión
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error("Error en el login:", error);
    }
}

function logout() {
    window.location.href = 'login.php';
}