<?php
require_once("../../conexion/conexion.php");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="entradadetelas.csv"');

$salida = fopen('php://output', 'w');
$columnas = $conexion->query("PRAGMA table_info(entradadetelas)")->fetchAll(PDO::FETCH_ASSOC);
$encabezados = array_column($columnas, 'name');
fputcsv($salida, $encabezados);

$filas = $conexion->query("SELECT * FROM entradadetelas")->fetchAll(PDO::FETCH_NUM);
foreach ($filas as $fila) {
    fputcsv($salida, $fila);
}
fclose($salida);
exit();