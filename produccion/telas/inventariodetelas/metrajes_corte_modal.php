<?php
require_once(__DIR__ . "/../../../conexion/conexion.php");

$codigo_tela = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : '';

if (!$codigo_tela) {
    echo "<p style='color:red;'>❌ Código de tela no proporcionado.</p>";
    exit();
}

$metrajes = $conexion->prepare("SELECT * FROM metrajes WHERE codigo_tela = ? ORDER BY id DESC");
$metrajes->execute([$codigo_tela]);
$datos_metrajes = $metrajes->fetchAll(PDO::FETCH_ASSOC);

// Inicializar total metros
$total_metros = 0;
?>

<h3 style="font-family:'Segoe UI'; margin-bottom:10px;">✂️ Cortar Rollo: <?= htmlspecialchars($codigo_tela) ?></h3>

<form action="guardar_cortes.php" method="POST">
    <input type="hidden" name="codigo_tela" value="<?= htmlspecialchars($codigo_tela) ?>">

    <table style="width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
        <thead style="background:#ecf6fd; color:#2c3e50;">
            <tr>
                <th style="padding:10px; text-align:center;">#</th>
                <th style="padding:10px;">Metros</th>
                <th style="padding:10px;">Ancho</th>
                <th style="padding:10px;">Cortado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos_metrajes as $index => $fila): ?>
            <?php $total_metros += (float)$fila['metros']; ?>
            <tr>
                <td style="padding:10px; text-align:center;"><?= $index + 1 ?></td>
                <td 
                    style="padding:10px; cursor:pointer; background:#f9f9f9;" 
                    class="metros-clickable"
                    title="Haz clic para copiar a Cortado"
                >
                    <?= htmlspecialchars($fila['metros']) ?>
                </td>
                <td style="padding:10px;"><?= htmlspecialchars($fila['ancho']) ?></td>
                <td style="padding:10px;">
                    <input
                        type="number"
                        name="cortado[<?= $fila['id'] ?>]"
                        step="0.01"
                        value="<?= htmlspecialchars($fila['cortado']) ?>"
                        class="cortado-input"
                        style="width:100%; padding:6px; border:1px solid #ccc; border-radius:4px;"
                    >
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background:#f9f9f9; font-weight:bold; color:#2c3e50;">
                <td style="padding:10px; text-align:center;">Totales:</td>
                <td style="padding:10px;"><?= number_format($total_metros, 2) ?></td>
                <td style="padding:10px;">-</td>
                <td style="padding:10px;" id="total-cortado">0.00</td>
            </tr>
        </tfoot>
    </table>

    <!-- Botones de acción -->
    <div style="margin-top:15px; text-align:right;">
        <!-- 📥 Botón copiar todos dentro del modal -->
        <button type="button" id="btnCopiarTodos" 
            style="background:#2980b9;color:#fff;padding:10px 16px;border:none;border-radius:6px;cursor:pointer; margin-right:10px;">
            📥 Copiar todos
        </button>

        <!-- 💾 Botón guardar -->
        <button type="submit" style="background:#27ae60;color:#fff;padding:10px 16px;border:none;border-radius:6px;cursor:pointer;">
            💾 Guardar Cortes
        </button>
    </div>
</form>

<script>
// 🔥 Copiar valor de una celda “Metros” a la celda “Cortado”
document.querySelectorAll('.metros-clickable').forEach(td => {
    td.addEventListener('click', function () {
        const metros = parseFloat(td.textContent);
        const fila = td.parentElement;
        const inputCortado = fila.querySelector('.cortado-input');
        if (!isNaN(metros) && inputCortado) {
            inputCortado.value = metros.toFixed(2);
            actualizarTotalCortado();
        }
    });
});

// 📥 Copiar todos los metros a cortado (ahora correctamente)
document.getElementById('btnCopiarTodos').addEventListener('click', function () {
    document.querySelectorAll('tbody tr').forEach(fila => {
        const metrosCelda = fila.querySelector('.metros-clickable');
        const inputCortado = fila.querySelector('.cortado-input');
        if (metrosCelda && inputCortado) {
            const metros = parseFloat(metrosCelda.textContent);
            if (!isNaN(metros)) {
                inputCortado.value = metros.toFixed(2);
            }
        }
    });
    actualizarTotalCortado();
});

// 🔄 Actualizar el total de cortado
function actualizarTotalCortado() {
    let total = 0;
    document.querySelectorAll('.cortado-input').forEach(input => {
        const valor = parseFloat(input.value);
        if (!isNaN(valor)) {
            total += valor;
        }
    });
    document.getElementById('total-cortado').textContent = total.toFixed(2);
}

// ✅ Inicializar el total al cargar
actualizarTotalCortado();
</script>
