<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

require_once(__DIR__ . "/../../../conexion/conexion.php");

// obtener código de tela
$codigo_tela = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : '';
?>

<div style="max-width:800px; margin:30px auto; font-family:'Segoe UI',sans-serif; box-shadow:0 4px 10px rgba(0,0,0,0.1); border-radius:10px; padding:25px; background:#fdfdfd;">
    <h2 style="text-align:center; color:#2c3e50; font-size:24px; margin-bottom:20px;">➕ Nuevo Metraje</h2>

    <form action="guardar_metrajes.php" method="POST" id="metrajesForm">
        <!-- Código de tela -->
        <div style="margin-bottom:15px;">
            <label for="codigo_tela" style="font-weight:600; color:#34495e;">Código Tela:</label>
            <input
                type="text"
                name="codigo_tela"
                id="codigo_tela"
                value="<?= $codigo_tela ?>"
                <?= $codigo_tela ? 'readonly style="background:#ecf0f1;color:#7f8c8d;"' : 'required' ?>
                style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;"
            >
        </div>

        <!-- Cuadrícula para ingresar Metros y Ancho -->
        <table id="metrajesTable" style="width:100%; border-collapse:collapse; margin-bottom:15px;">
            <thead>
                <tr style="background:#ecf6fd; color:#2c3e50;">
                    <th style="padding:8px; text-align:left;">#</th>
                    <th style="padding:8px; text-align:left;">Metros</th>
                    <th style="padding:8px; text-align:left;">Ancho</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 10; $i++): ?> <!-- ✅ Solo 10 filas iniciales -->
                <tr>
                    <td style="padding:5px;"><?= $i ?></td>
                    <td style="padding:5px;">
                        <input
                            type="number"
                            name="metros[]"
                            step="0.01"
                            placeholder=" "
                            onkeydown="return bloquearFlechas(event)"
                            onkeypress="return moverConEnter(event)"
                            style="width:100%;padding:6px;border:1px solid #ccc;border-radius:4px;"
                        >
                    </td>
                    <td style="padding:5px;">
                        <input
                            type="number"
                            name="ancho[]"
                            step="0.01"
                            placeholder=" "
                            onkeydown="return bloquearFlechas(event)"
                            onkeypress="return moverConEnter(event)"
                            style="width:100%;padding:6px;border:1px solid #ccc;border-radius:4px;"
                        >
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <!-- Botón para agregar más filas -->
        <div style="text-align:center; margin-bottom:15px;">
            <button type="button" onclick="agregarFilas(5)" 
                style="background:#2ecc71;color:#fff;padding:10px 15px;border:none;border-radius:6px;font-size:14px;cursor:pointer;">
                ➕ Agregar más filas
            </button>
        </div>

        <!-- Botones de acción -->
        <div style="text-align:center; margin-top:20px;">
            <!-- Botón Guardar -->
            <button
                type="submit"
                style="background:#3498db;color:#fff;padding:12px 20px;border:none;border-radius:8px;font-size:16px;cursor:pointer;margin-right:10px;"
            >
                💾 Guardar Metrajes
            </button>

            <!-- Botón Atrás -->
            <a href="/produccion/telas/inventariodetelas/metrajes_modal.php?codigo=<?= urlencode($codigo_tela) ?>" 
               style="background:#95a5a6;color:#fff;padding:12px 20px;border-radius:8px;font-size:16px;text-decoration:none;">
                🔙 Atrás
            </a>
        </div>
    </form>
</div>

<script>
// ✅ Función para agregar más filas dinámicamente
function agregarFilas(cantidad) {
    const tabla = document.getElementById('metrajesTable').getElementsByTagName('tbody')[0];
    let numFilas = tabla.rows.length;

    for (let i = 1; i <= cantidad; i++) {
        const fila = tabla.insertRow();
        const celdaNum = fila.insertCell(0);
        const celdaMetros = fila.insertCell(1);
        const celdaAncho = fila.insertCell(2);

        celdaNum.innerHTML = numFilas + i;
        celdaMetros.innerHTML = `<input type="number" name="metros[]" step="0.01" placeholder=" "
                                  onkeydown="return bloquearFlechas(event)" 
                                  onkeypress="return moverConEnter(event)"
                                  style="width:100%;padding:6px;border:1px solid #ccc;border-radius:4px;">`;
        celdaAncho.innerHTML = `<input type="number" name="ancho[]" step="0.01" placeholder=" "
                                  onkeydown="return bloquearFlechas(event)" 
                                  onkeypress="return moverConEnter(event)"
                                  style="width:100%;padding:6px;border:1px solid #ccc;border-radius:4px;">`;
    }
}

// ✅ Desactivar flechas arriba/abajo en los inputs
function bloquearFlechas(event) {
    if (event.key === "ArrowUp" || event.key === "ArrowDown") {
        return false;
    }
    return true;
}

// ✅ Mover con Enter al siguiente campo verticalmente
function moverConEnter(event) {
    if (event.key === "Enter") {
        event.preventDefault(); // Evitar enviar formulario
        const inputs = Array.from(document.querySelectorAll('#metrajesTable input'));
        const index = inputs.indexOf(event.target);
        if (index >= 0 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
        return false;
    }
    return true;
}
</script>
