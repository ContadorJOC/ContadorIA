<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");


$columnas = $conexion->query("PRAGMA table_info(existenciasgenerales)")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_existenciasgenerales.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h2 style='font-family:Segoe UI;'>➕ Agregar a <?= 'existenciasgenerales' ?></h2>

<form action='guardar_existenciasgenerales.php' method='POST' style='max-width:600px; margin:auto; background:#f9f9f9; padding:20px; border-radius:12px; box-shadow:0 0 10px rgba(0,0,0,0.05);'>
<?php foreach ($columnas as $col): if ($col['name'] == 'id') continue; ?>
    <label style='display:block; margin-top:10px;'><?= $etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name'])) ?></label>
    <input type='text' name='<?= $col['name'] ?>' style='width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;'>
<?php endforeach; ?>
    <button type='submit' style='margin-top:20px; background:#27ae60; color:white; padding:10px 16px; border:none; border-radius:6px;'>Guardar</button>
</form>