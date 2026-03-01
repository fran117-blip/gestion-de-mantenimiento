<div class="flex justify-between items-center mb-8">
    <h2 class="text-2xl font-bold text-slate-800">Inventario de Unidades</h2>
    <div class="flex gap-3">
        <button class="btn-verde-excel" onclick="exportarFlotaExcel()" style="background-color: #10b981; color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 600; box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);">
            <i class="fas fa-file-excel"></i> Exportar Excel
        </button>

        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" 
                   id="buscador-flota" 
                   class="input-elegante" 
                   style="padding: 10px 10px 10px 40px; width: 300px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none;" 
                   placeholder="Buscar unidad, marca o placa..."
                   onkeyup="filtrarFlota()">
        </div>

        <button class="btn-primario" onclick="abrirModalCamion()" style="background-color: #10b981; color: white; padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600;">
            <i class="fas fa-plus"></i> Nuevo Camión
        </button>
    </div>
</div>

<div class="tabla-pro-container" style="background: white; border-radius: 12px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
    <table class="tabla-pro" id="tabla-unidades" style="width: 100%; border-collapse: collapse;">
        <thead style="background: #f8fafc;">
            <tr>
                <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">ECONÓMICO</th>
                <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">MARCA / MODELO</th>
                <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">AÑO</th>
                <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">PLACAS</th>
                <th style="padding: 15px; text-align: left; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">ESTATUS</th>
                <th style="padding: 15px; text-align: center; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0;">ACCIÓN</th>
            </tr>
        </thead>
        <tbody id="tabla-unidades-cuerpo">
            <tr>
                <td colspan="6" class="text-center py-10 text-slate-400">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Cargando inventario...
                </td>
            </tr> 
        </tbody>
    </table>
</div>

<script src="js/flota.js"></script>