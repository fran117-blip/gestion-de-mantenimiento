function exportarFlotaExcel() {
    const tablaOriginal = document.getElementById("tabla-unidades"); // ID actualizado
    if (!tablaOriginal) return;

    let tablaParaExcel = tablaOriginal.cloneNode(true);
    
    // Quitamos la columna de "Acción" para que el Excel sea profesional
    const filas = tablaParaExcel.querySelectorAll('tr');
    filas.forEach(fila => {
        if (fila.lastElementChild) fila.removeChild(fila.lastElementChild);
    });

    const contenido = tablaParaExcel.outerHTML;
    const blob = new Blob(['\ufeff' + contenido], { type: 'application/vnd.ms-excel' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement("a");
    
    a.href = url;
    a.download = "Inventario_Flota_" + new Date().toLocaleDateString() + ".xls";
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// Función para el buscador manual de unidades (T-101, T-102, etc.)
function filtrarFlota() {
    const texto = document.getElementById("buscador-flota").value.toUpperCase();
    const filas = document.querySelectorAll("#tabla-unidades-cuerpo tr");
    filas.forEach(fila => {
        fila.style.display = fila.innerText.toUpperCase().includes(texto) ? "" : "none";
    });
}