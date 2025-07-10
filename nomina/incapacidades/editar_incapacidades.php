<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

require_once('../../conexion/conexion.php');

$id = intval($_GET['id']);

// ✅ Cargar las columnas de la tabla
$columnas = $conexion->query("PRAGMA table_info(incapacidades)")->fetchAll(PDO::FETCH_ASSOC);

// ✅ Obtener el registro actual
$registro = $conexion->query("SELECT * FROM incapacidades WHERE id=$id")->fetch(PDO::FETCH_ASSOC);

// ✅ Actualizar si envían datos por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sets = [];
    $valores = [];
    foreach ($_POST as $campo => $valor) {
        if ($campo !== 'enviar') {
            $sets[] = "$campo=?";
            $valores[] = trim($valor);
        }
    }
    $valores[] = $id;
    $sql = "UPDATE incapacidades SET " . implode(',', $sets) . " WHERE id=?";
    $conexion->prepare($sql)->execute($valores);
    header("Location: index.php");
    exit();
}
?>

<h2 style="font-family:'Segoe UI';">✏️ Editar <?= strtoupper("incapacidades") ?></h2>
<form method="post" style="max-width:500px;margin:auto;">
    <?php foreach ($columnas as $col): ?>
        <?php if ($col['name'] === 'id') continue; ?>

        <label for="<?= htmlspecialchars($col['name']) ?>" style="display:block;margin-top:10px;">
            <?= strtoupper(str_replace('_',' ',$col['name'])) ?>:
        </label>

        <?php if (in_array($col['name'], ['inicio', 'fin', 'fecha_de_pago'])): ?>
            <!-- 📅 Campo de fecha -->
            <input type="date" 
                name="<?= htmlspecialchars($col['name']) ?>" 
                value="<?= htmlspecialchars($registro[$col['name']]) ?>"
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" required>

        <?php elseif ($col['name'] === 'estado'): ?>
            <!-- 📋 Select para estado -->
            <select name="estado" 
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" required>
                <option value="RADICADA" <?= $registro['estado'] == 'RADICADA' ? 'selected' : '' ?>>RADICADA</option>
                <option value="PAGADA" <?= $registro['estado'] == 'PAGADA' ? 'selected' : '' ?>>PAGADA</option>
                <option value="NEGADA" <?= $registro['estado'] == 'NEGADA' ? 'selected' : '' ?>>NEGADA</option>
            </select>

        <?php else: ?>
            <!-- 📝 Campo texto -->
            <input type="text" 
                name="<?= htmlspecialchars($col['name']) ?>" 
                value="<?= htmlspecialchars($registro[$col['name']]) ?>"
                style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;" required>
        <?php endif; ?>
    <?php endforeach; ?>

    <button type="submit" name="enviar" 
        style="margin-top:20px;width:100%;background:#2980b9;color:white;padding:10px;border:none;border-radius:6px;">
        💾 Actualizar
    </button>
    <a href="index.php" 
        style="margin-top:10px;display:inline-block;background:#e74c3c;color:white;padding:10px 15px;border-radius:6px;text-decoration:none;">
        ↩️ Cancelar
    </a>
</form>
