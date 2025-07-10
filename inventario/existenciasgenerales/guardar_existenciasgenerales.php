<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$columnas = $conexion->query("PRAGMA table_info(existenciasgenerales)")->fetchAll(PDO::FETCH_ASSOC);
$campos = [];
$valores = [];

foreach ($columnas as $col) {
    if ($col['name'] == 'id') continue;
    $campos[] = $col['name'];
    $valores[] = $_POST[$col['name']] ?? '';
}

$sql = "INSERT INTO existenciasgenerales (" . implode(",", $campos) . ") VALUES (" . rtrim(str_repeat("?,", count($valores)), ",") . ")";
$conexion->prepare($sql)->execute($valores);

header("Location: existenciasgenerales.php");
exit();