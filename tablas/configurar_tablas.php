<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}

include_once('../componentes/submenu_tablas.php');
require_once('../componentes/plantilla.php');
require_once('../conexion/conexion.php');

// Eliminar columna (SQLite no permite eliminar directamente)
if (isset($_GET['eliminar']) && isset($_GET['tabla'])) {
    $tabla = $_GET['tabla'];
    $columna = $_GET['eliminar'];

    // Obtener la estructura actual
    $columnas = $conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);

    // Validar columna existente
    if (in_array($columna, array_column($columnas, 'name'))) {
        try {
            // Filtrar columnas (excepto la que se va a eliminar)
            $columnas_nuevas = array_filter($columnas, fn($col) => $col['name'] !== $columna);

            // Generar SQL para nueva tabla
            $definiciones = array_map(fn($col) => "`{$col['name']}` {$col['type']}", $columnas_nuevas);
            $sqlCrear = "CREATE TABLE nueva_$tabla (" . implode(', ', $definiciones) . ")";
            $conexion->exec($sqlCrear);

            // Copiar datos
            $nombres = implode(', ', array_column($columnas_nuevas, 'name'));
            $conexion->exec("INSERT INTO nueva_$tabla SELECT $nombres FROM $tabla");

            // Reemplazar tabla
            $conexion->exec("DROP TABLE $tabla");
            $conexion->exec("ALTER TABLE nueva_$tabla RENAME TO $tabla");

            header("Location: configurar_tablas.php?tabla=$tabla&msg=eliminado");
            exit();
        } catch (Exception $e) {
            echo "❌ Error al eliminar columna: " . $e->getMessage();
        }
    }
}

// Obtener tablas
$tablas = $conexion->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);

// Tabla seleccionada
$estructura = [];
$tablaSeleccionada = $_GET['tabla'] ?? '';
if ($tablaSeleccionada && in_array($tablaSeleccionada, $tablas)) {
    $estructura = $conexion->query("PRAGMA table_info($tablaSeleccionada)")->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Contenido principal -->
<div class="main-content" style="max-width: 1000px; margin: auto; padding: 30px;">
    <h2>⚙️ Configurar Tablas</h2>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'eliminado'): ?>
        <div style="margin-top: 15px; padding: 10px; background: #eafaf1; border: 1px solid #c8e6c9; color: #2e7d32; border-radius: 6px;">
            ✅ Columna eliminada correctamente.
        </div>
    <?php endif; ?>

    <form method="get">
        <label for="tabla">Selecciona una tabla:</label>
        <select name="tabla" id="tabla" onchange="this.form.submit()" style="padding: 8px; border-radius: 6px; border: 1px solid #ccc; width: 100%; margin-top: 10px;">
            <option value="">-- Elegir tabla --</option>
            <?php foreach ($tablas as $tabla): ?>
                <option value="<?= $tabla ?>" <?= $tabla === $tablaSeleccionada ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tabla) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($estructura): ?>
        <h3 style="margin-top: 30px;">📋 Estructura de la tabla: <strong><?= htmlspecialchars($tablaSeleccionada) ?></strong></h3>
        <table style="width: 100%; border-collapse: collapse; background: white; margin-top: 20px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
            <thead>
                <tr style="background: #ecf0f1;">
                    <th style="padding: 10px; border: 1px solid #ccc;">ID</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Nombre</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Tipo</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">¿PK?</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">¿NULL?</th>
                    <th style="padding: 10px; border: 1px solid #ccc;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($estructura as $col): ?>
                    <tr>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?= $col['cid'] ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($col['name']) ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?= htmlspecialchars($col['type']) ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?= $col['pk'] ? '✔️' : '❌' ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc;"><?= $col['notnull'] ? '❌' : '✔️' ?></td>
                        <td style="padding: 10px; border: 1px solid #ccc; text-align:center;">
                            <?php if (!$col['pk']): ?>
                                <form method="get" onsubmit="return confirmarEliminacion();" style="display:inline;">
                                    <input type="hidden" name="tabla" value="<?= htmlspecialchars($tablaSeleccionada) ?>">
                                    <input type="hidden" name="eliminar" value="<?= htmlspecialchars($col['name']) ?>">
                                    <button type="submit" style="background:#e74c3c; color:white; border:none; padding:6px 10px; border-radius:5px; cursor:pointer;">
                                        🗑️ Eliminar
                                    </button>
                                </form>
                            <?php else: ?>
                                🔒
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($tablaSeleccionada): ?>
        <p style="margin-top: 20px;">⚠️ No se pudo obtener la estructura de la tabla.</p>
    <?php endif; ?>
</div>

<script>
function confirmarEliminacion() {
    return confirm("¿Desea eliminar este campo de la tabla?");
}
</script>