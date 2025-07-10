<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

require_once(__DIR__ . "/../../../conexion/conexion.php");

// Validar ID recibido
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p style='color:red;'>❌ ID de metraje no válido.</p>";
    exit();
}

// Consultar datos actuales del metraje
$stmt = $conexion->prepare("SELECT * FROM metrajes WHERE id = ?");
$stmt->execute([$id]);
$metraje = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$metraje) {
    echo "<p style='color:red;'>❌ Metraje no encontrado.</p>";
    exit();
}

// Si el formulario se envió, procesar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $metros  = isset($_POST['metros']) ? floatval($_POST['metros']) : 0;
        $ancho   = isset($_POST['ancho']) ? floatval($_POST['ancho']) : 0;
        $cortado = isset($_POST['cortado']) ? floatval($_POST['cortado']) : 0;

        // ✅ Calcula saldo automáticamente en el servidor
        $saldo = $metros - $cortado;

        $update = $conexion->prepare("UPDATE metrajes SET metros=?, ancho=?, cortado=?, saldo=? WHERE id=?");
        $update->execute([$metros, $ancho, $cortado, $saldo, $id]);

        // Redirigir al modal del código de tela
        header("Location: /produccion/telas/inventariodetelas/metrajes_modal.php?codigo=" . urlencode($metraje['codigo_tela']) . "&msg=actualizado");
        exit();
    } catch (PDOException $e) {
        echo "❌ Error al actualizar: " . $e->getMessage();
    }
}
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">✏️ Editar Metraje</h2>

<form action="" method="POST" style="max-width:600px;margin:auto;" oninput="calcularSaldo()">
    <!-- Campo Código Tela (solo lectura) -->
    <label for="codigo_tela" style="margin-top:10px;">CÓDIGO TELA:</label>
    <input type="text" name="codigo_tela" id="codigo_tela" 
           value="<?= htmlspecialchars($metraje['codigo_tela']) ?>" 
           readonly style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;background:#f0f0f0;">

    <!-- Campo Metros -->
    <label for="metros" style="margin-top:10px;">METROS:</label>
    <input type="number" name="metros" id="metros" step="0.01" 
           value="<?= htmlspecialchars($metraje['metros']) ?>" 
           required 
           style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <!-- Campo Ancho -->
    <label for="ancho" style="margin-top:10px;">ANCHO:</label>
    <input type="number" name="ancho" id="ancho" step="0.01" 
           value="<?= htmlspecialchars($metraje['ancho']) ?>" 
           required 
           style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <!-- Campo Cortado -->
    <label for="cortado" style="margin-top:10px;">CORTADO:</label>
    <input type="number" name="cortado" id="cortado" step="0.01" 
           value="<?= htmlspecialchars($metraje['cortado']) ?>" 
           required 
           style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <!-- Campo Saldo (readonly y calculado automáticamente) -->
    <label for="saldo" style="margin-top:10px;">SALDO:</label>
    <input type="number" name="saldo" id="saldo" step="0.01" 
           value="<?= htmlspecialchars($metraje['metros'] - $metraje['cortado']) ?>" 
           readonly 
           style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;background:#f0f0f0;color:#7f8c8d;">

    <button type="submit" style="margin-top:15px;background:#27ae60;color:#fff;padding:10px 15px;border:none;border-radius:6px;">💾 Actualizar</button>
</form>

<script>
// ✅ Calcula saldo automáticamente cuando cambien metros o cortado
function calcularSaldo() {
    const metros = parseFloat(document.getElementById('metros').value) || 0;
    const cortado = parseFloat(document.getElementById('cortado').value) || 0;
    const saldo = metros - cortado;
    document.getElementById('saldo').value = saldo >= 0 ? saldo.toFixed(2) : 0;
}

// ✅ Al abrir el formulario calcula saldo inicial
window.addEventListener('DOMContentLoaded', calcularSaldo);
</script>
