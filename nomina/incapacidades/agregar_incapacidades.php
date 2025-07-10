<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once("../../conexion/conexion.php");
$tabla = "incapacidades";
$columnas = $conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="font-family:'Segoe UI';">➕ Agregar Registro</h2>
<form method="POST" action="guardar_incapacidades.php" style="max-width:500px;margin:auto;">
    <?php foreach ($columnas as $col): ?>
        <?php if ($col['name'] !== 'id'): // Saltar campo ID ?>
            <label for="<?= htmlspecialchars($col['name']) ?>" style="display:block;margin-top:10px;">
                <?= strtoupper(str_replace('_', ' ', $col['name'])) ?>:
            </label>

            <?php if (in_array($col['name'], ['inicio', 'fin', 'fecha', 'fecha_pago'])): ?>
                <!-- Campos de fecha específicos -->
                <input type="date" name="<?= htmlspecialchars($col['name']) ?>" id="<?= htmlspecialchars($col['name']) ?>"
                    style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" required>

            <?php elseif ($col['name'] === 'estado'): ?>
                <!-- Select con opciones para campo estado -->
                <select name="estado" id="estado" 
                    style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" required>
                    <option value="">-- Seleccione --</option>
                    <option value="RADICADA">RADICADA</option>
                    <option value="PAGADA">PAGADA</option>
                    <option value="NEGADA">NEGADA</option>
                </select>

            <?php else: ?>
                <!-- Input normal para otros campos -->
                <input type="text" name="<?= htmlspecialchars($col['name']) ?>" id="<?= htmlspecialchars($col['name']) ?>"
                    style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" required>
            <?php endif; ?>

        <?php endif; ?>
    <?php endforeach; ?>
    <button type="submit" style="margin-top:20px;width:100%;background-color:#3498db;color:white;padding:10px;border:none;border-radius:6px;">
        💾 Guardar
    </button>
</form>
