// js/qr-reader.js

function abrirEscanerQR() {
    // Creamos un modal o contenedor para la cámara
    const scannerContainer = document.getElementById('reader');
    scannerContainer.style.display = 'block';

    const html5QrCode = new Html5Qrcode("reader");

    const qrCodeSuccessCallback = (decodedText, decodedResult) => {
        // decodedText será el número económico (ej: "T-420")
        console.log(`Código detectado: ${decodedText}`);
        
        // Detenemos la cámara
        html5QrCode.stop().then(() => {
            scannerContainer.style.display = 'none';
            // Llamamos a la búsqueda en la API
            consultarUnidadPorQR(decodedText);
        });
    };

    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

    // Iniciar cámara trasera
    html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
}

async function consultarUnidadPorQR(economico) {
    try {
        const resp = await fetch(`api/unidades/buscar_por_qr.php?economico=${economico}`);
        const unidad = await resp.json();

        if (unidad.error) {
            alert("Unidad no registrada en la base de datos.");
        } else {
            // Aquí puedes mandarlo a la sección de reporte o mostrar sus datos
            alert(`Unidad detectada: ${unidad.marca} ${unidad.modelo} (${unidad.placas})`);
            // Ejemplo: llenar un campo de búsqueda y filtrar
            document.getElementById('busqueda-flota').value = economico;
            navegar('flota');
        }
    } catch (error) {
        console.error("Error al consultar QR:", error);
    }
}