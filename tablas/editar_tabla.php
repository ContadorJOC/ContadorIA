<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
require_once('../componentes/plantilla.php');
require_once('../conexion/conexion.php');

$tabla = $_GET['tabla'] ?? '';

if (!$tabla) {
    echo "<p style='color:red;'>No se ha especificado la tabla.</p>";
    exit();
}

// Obtener campos actuales
$columnas = $conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);

// Cargar encabezados personalizados si existen
$configFile = "encabezados_$tabla.json";
$encabezados = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];

// Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevos = [];
    foreach ($columnas as $col) {
        $campo = $col['name'];
        $etiqueta = $_POST['etiqueta'][$campo] ?? $campo;
        $nuevos[$campo] = $etiqueta;
    }
    file_put_contents($configFile, json_encode($nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "<div style='background:#e8f5e9; padding:10px; border:1px solid #c8e6c9;'>✅ Encabezados actualizados correctamente.</div>";
    $encabezados = $nuevos;
}
?>

<div style="max-width: 700px; margin: auto; padding-top: 100px; font-family: 'Segoe UI', sans-serif;">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">✏️ Editar Encabezados de la Tabla: <?= htmlspecialchars($tabla) ?></h2>

    <form method="POST">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#ecf0f1;">
                    <th style="padding:8px; text-align:left;">Campo</th>
                    <th style="padding:8px; text-align:left;">Encabezado Visible</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($columnas as $col): ?>
                    <tr>
                        <td style="padding:8px;"> <?= htmlspecialchars($col['name']) ?> </td>
                        <td style="padding:8px;">
                            <input type="text" name="etiqueta[<?= $col['name'] ?>]" value="<?= htmlspecialchars($encabezados[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?>" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:4px;">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" style="margin-top:20px; padding:10px 20px; background:#3498db; color:white; border:none; border-radius:6px; cursor:pointer;">Guardar Cambios</button>
    </form>
</div>