<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

require_once(__DIR__ . '/../conexion/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tabla = trim($_POST['tabla']);
    $carpeta = trim($_POST['carpeta']);
    $columnas = $conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);

    $dir = "../$carpeta/$tabla";
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // -------- INDEX.PHP --------
    $index = <<<PHP
<?php
session_start();
if (!isset(\$_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once("../../componentes/plantilla.php");
require_once("../../conexion/conexion.php");

\$columnas = \$conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);
\$datos = \$conexion->query("SELECT * FROM $tabla ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

\$json = "encabezados_{$tabla}.json";
\$etiquetas = file_exists(\$json) ? json_decode(file_get_contents(\$json), true) : [];
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">📋 <?= strtoupper("$tabla") ?></h2>
<a href="agregar_{$tabla}.php" style="background:#3498db;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none;">➕ Agregar</a>
<a href="exportar_{$tabla}.php" style="background:#2ecc71;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none; margin-left:5px;">📥 Exportar Excel</a>

<div class="contenedor-tabla" style="margin-top:20px; overflow-x:auto;">
<table style="width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
    <thead style="background:#ecf6fd; color:#2c3e50;">
        <tr>
            <?php foreach (\$columnas as \$col): ?>
                <th style="padding:10px; text-align:left;"><?= htmlspecialchars(\$etiquetas[\$col['name']] ?? strtoupper(str_replace('_', ' ', \$col['name']))) ?></th>
            <?php endforeach; ?>
            <th style="padding:10px; text-align:left;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach (\$datos as \$fila): ?>
        <tr>
            <?php foreach (\$columnas as \$col): ?>
                <td style="padding:10px;"><?= htmlspecialchars(\$fila[\$col['name']]) ?></td>
            <?php endforeach; ?>
            <td style="padding:10px;">
                <a href="editar_{$tabla}.php?id=<?= \$fila['id'] ?>" style="color:#2980b9;">✏️ Editar</a>
                <a href="eliminar_{$tabla}.php?id=<?= \$fila['id'] ?>" onclick="return confirm('¿Eliminar este registro?')" style="color:#e74c3c; margin-left:8px;">🗑️ Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
PHP;
    file_put_contents("$dir/index.php", $index);

    // -------- AGREGAR.PHP --------
    $formFields = "";
    foreach ($columnas as $col) {
        if ($col['name'] !== 'id') {
            $label = strtoupper(str_replace('_', ' ', $col['name']));
            $formFields .= <<<HTML

    <label for="{$col['name']}" style="margin-top:10px;">$label:</label>
    <input type="text" name="{$col['name']}" id="{$col['name']}" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
HTML;
        }
    }

    $agregar = <<<PHP
<?php
session_start();
if (!isset(\$_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once("../../componentes/plantilla.php");
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">➕ Agregar <?= strtoupper("$tabla") ?></h2>

<form action="guardar_{$tabla}.php" method="POST" style="max-width:600px;margin:auto;">
    $formFields
    <button type="submit" style="margin-top:15px;background:#27ae60;color:#fff;padding:10px 15px;border:none;border-radius:6px;">💾 Guardar</button>
</form>
PHP;
    file_put_contents("$dir/agregar_{$tabla}.php", $agregar);

    // -------- GUARDAR.PHP --------
    $guardar = <<<PHP
<?php
session_start();
if (!isset(\$_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once("../../conexion/conexion.php");

if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        \$columnas = \$conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);
        \$campos = [];
        \$valores = [];
        \$placeholders = [];

        foreach (\$columnas as \$col) {
            if (\$col['name'] !== 'id' && isset(\$_POST[\$col['name']])) {
                \$campos[] = \$col['name'];
                \$valores[] = trim(\$_POST[\$col['name']]);
                \$placeholders[] = '?';
            }
        }

        \$sql = "INSERT INTO $tabla (" . implode(',', \$campos) . ") VALUES (" . implode(',', \$placeholders) . ")";
        \$stmt = \$conexion->prepare(\$sql);
        \$stmt->execute(\$valores);

        header("Location: index.php?msg=guardado");
        exit();
    } catch (PDOException \$e) {
        echo "❌ Error: " . \$e->getMessage();
    }
}
PHP;
    file_put_contents("$dir/guardar_{$tabla}.php", $guardar);

    // -------- ELIMINAR.PHP --------
    $eliminar = <<<PHP
<?php
session_start();
if (!isset(\$_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once("../../conexion/conexion.php");

if (isset(\$_GET['id'])) {
    \$id = intval(\$_GET['id']);
    try {
        \$conexion->prepare("DELETE FROM $tabla WHERE id=?")->execute([\$id]);
        header("Location: index.php?msg=eliminado");
        exit();
    } catch (PDOException \$e) {
        echo "❌ Error: " . \$e->getMessage();
    }
}
PHP;
    file_put_contents("$dir/eliminar_{$tabla}.php", $eliminar);

    // -------- EXPORTAR.PHP --------
    $exportar = <<<PHP
<?php
require_once("../../conexion/conexion.php");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="{$tabla}.csv"');

\$salida = fopen('php://output', 'w');
\$columnas = \$conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);
\$encabezados = array_column(\$columnas, 'name');
fputcsv(\$salida, \$encabezados);

\$filas = \$conexion->query("SELECT * FROM $tabla")->fetchAll(PDO::FETCH_NUM);
foreach (\$filas as \$fila) {
    fputcsv(\$salida, \$fila);
}
fclose(\$salida);
exit();
PHP;
    file_put_contents("$dir/exportar_{$tabla}.php", $exportar);

    echo "✅ Módulo completo generado en $dir";
} else {
    echo "⚠️ Debes enviar tabla y carpeta por POST.";
}
?>
