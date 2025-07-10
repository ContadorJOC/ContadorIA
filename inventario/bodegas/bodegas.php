<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");


$columnas = $conexion->query("PRAGMA table_info(bodegas)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM bodegas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_bodegas.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h2 style='font-family:Segoe UI;'>📋 <?= strtoupper("bodegas") ?></h2>
<a href='agregar_bodegas.php' style='background:#3498db;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;'>➕ Agregar</a>

<div class='contenedor-tabla' style='margin-top:20px;'>
<table border='1' cellpadding='5' cellspacing='0' style='width:100%; border-collapse:collapse;'>
<thead>
<tr>
<?php foreach ($columnas as $col): ?>
    <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
<?php endforeach; ?>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php foreach ($datos as $fila): ?>
<tr>
<?php foreach ($columnas as $col): ?>
    <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
<?php endforeach; ?>
    <td>
        <a href='editar_bodegas.php?id=<?= $fila['id'] ?>'>✏️</a>
        |
        <a href='eliminar_bodegas.php?id=<?= $fila['id'] ?>' onclick="return confirm('¿Eliminar este registro?')">🗑️</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>