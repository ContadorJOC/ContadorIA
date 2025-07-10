<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
require_once('../componentes/plantilla.php');
include_once('../componentes/submenu_tablas.php');
require_once('../conexion/conexion.php');

// Obtener tablas
$tablas = $conexion->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="contenedor-tablas" style="max-width: 1200px; margin: auto; padding: 20px; padding-top: 120px; font-family: 'Segoe UI', sans-serif;">
    <h2 style="text-align:center; margin-bottom: 30px; color: #2c3e50;">📋 Tablas Disponibles</h2>

    <div class="grid-tablas" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
        <?php if ($tablas): ?>
            <?php foreach ($tablas as $tabla): 
                $icono = match (true) {
                    str_contains($tabla, 'empleado') => '🧑‍💼',
                    str_contains($tabla, 'factura') => '🧾',
                    str_contains($tabla, 'novedad') => '🕓',
                    str_contains($tabla, 'examen') => '🩺',
                    str_contains($tabla, 'impuesto') => '💰',
                    str_contains($tabla, 'producto') => '📦',
                    str_contains($tabla, 'servicio') => '🛠️',
                    default => '📄'
                };
            ?>
                <div class="tabla-card" style="background:white; border-radius:12px; padding:20px; box-shadow:0 4px 10px rgba(0,0,0,0.05); text-align:center;">
                    <div style="font-size:30px; margin-bottom:10px;"><?= $icono ?></div>
                    <div style="font-size:16px; font-weight:bold; margin-bottom:10px; color:#2c3e50;"><?= htmlspecialchars($tabla) ?></div>
                    <a href="?ver=<?= urlencode($tabla) ?>" style="display:inline-block; margin:4px 0; padding:6px 12px; background:#2980b9; color:white; border-radius:6px; text-decoration:none; font-size:13px;">📂 Ver</a>
                    <form method="POST" action="eliminar_tabla.php" style="display:inline;">
                        <input type="hidden" name="tabla" value="<?= htmlspecialchars($tabla) ?>">
                        <button type="submit" onclick="return confirm('¿Estás seguro de eliminar la tabla <?= $tabla ?>?')" 
                                style="margin-top:4px; background:#e74c3c; color:white; border:none; padding:6px 12px; border-radius:6px; cursor:pointer; font-size:13px;">
                            🗑️ Eliminar
                        </button>
                    </form>
                    <a href="editar_tabla.php?tabla=<?= urlencode($tabla) ?>" 
                       style="display:inline-block; margin-top:4px; padding:6px 12px; background:#f39c12; color:white; border-radius:6px; text-decoration:none; font-size:13px;">
                        📝 Editar
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay tablas disponibles.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['ver']) && in_array($_GET['ver'], $tablas)): 
        $tabla = $_GET['ver'];
        $datos = $conexion->query("SELECT * FROM `$tabla`")->fetchAll(PDO::FETCH_ASSOC);
    ?>
        <hr>
        <h2 style="margin-top: 40px;">📄 Contenido de: <?= htmlspecialchars($tabla) ?></h2>
        <?php if ($datos): ?>
            <div class="tabla-wrapper" style="overflow-x:auto; margin-top: 30px;">
                <table style="width:100%; border-collapse:collapse; font-size:14px;">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($datos[0]) as $col): ?>
                                <th style="border:1px solid #ccc; padding:6px 10px; background:#ecf0f1;"><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos as $fila): ?>
                            <tr>
                                <?php foreach ($fila as $valor): ?>
                                    <td style="border:1px solid #ccc; padding:6px 10px;"><?= htmlspecialchars($valor) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>📭 La tabla no tiene datos aún.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
