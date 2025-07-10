<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$columnas = $conexion->query("PRAGMA table_info(novedades)")->fetchAll(PDO::FETCH_ASSOC);
$campos = [];
$valores = [];
$marcadores = [];

foreach ($columnas as $col) {
    $nombre = $col['name'];
    if ($nombre === 'id') continue;
    $campos[] = "\"$nombre\""; // Comillas dobles para nombres con caracteres especiales
    $valores[] = $_POST[$nombre] ?? '';
    $marcadores[] = '?';
}

$sql = "INSERT INTO novedades (" . implode(",", $campos) . ") VALUES (" . implode(",", $marcadores) . ")";
$conexion->prepare($sql)->execute($valores);

header("Location: novedades.php");
exit();
