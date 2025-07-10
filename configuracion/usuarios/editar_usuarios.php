<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

// Obtener ID de usuario
$id = intval($_GET['id']); // Evitar inyecciones SQL

// Traer datos del usuario
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$dato = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dato) {
    echo "<p style='color:red;'>❌ Usuario no encontrado.</p>";
    exit();
}

// Traer columnas
$columnas = $conexion->query("PRAGMA table_info(usuarios)")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_usuarios.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];

// Traer roles
$roles = $conexion->query("SELECT rol FROM roles ORDER BY rol ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 style="font-family:Segoe UI;">✏️ Editar Usuario</h2>

<form action="guardar_usuarios.php?id=<?= $id ?>" method="POST" style="max-width:600px;margin:auto;background:#fff;padding:20px;border-radius:10px;box-shadow:0 4px 12px rgba(0,0,0,0.1);">
<?php foreach ($columnas as $col): if ($col['name'] == 'id') continue; ?>
    <label><b><?= $etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name'])) ?>:</b></label><br>

    <?php if ($col['name'] === 'rol'): ?>
        <select name="rol" required style="width:100%;padding:8px;margin-bottom:15px;">
            <option value="">-- Seleccione un rol --</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= htmlspecialchars($r['rol']) ?>" <?= $dato['rol'] === $r['rol'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars(ucfirst($r['rol'])) ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php elseif ($col['name'] === 'clave'): ?>
        <input type="password" name="clave" placeholder="🔒 Dejar vacío para no cambiar" style="width:100%;padding:8px;margin-bottom:15px;">
    <?php else: ?>
        <input type="text" name="<?= $col['name'] ?>" value="<?= htmlspecialchars($dato[$col['name']]) ?>" style="width:100%;padding:8px;margin-bottom:15px;">
    <?php endif; ?>
<?php endforeach; ?>
    <button type="submit" style="background:#3498db;color:#fff;padding:10px 20px;border:none;border-radius:6px;">💾 Actualizar Usuario</button>
    <a href="usuarios.php" style="display:inline-block;margin-left:10px;text-decoration:none;color:#555;">🔙 Cancelar</a>
</form>
