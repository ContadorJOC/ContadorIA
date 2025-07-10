<?php
require_once("../../conexion/conexion.php");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="historicocontroldecalidad.csv"');

$salida = fopen('php://output', 'w');
$columnas = $conexion->query("PRAGMA table_info(historicocontroldecalidad)")->fetchAll(PDO::FETCH_ASSOC);
$encabezados = array_column($columnas, 'name');
fputcsv($salida, $encabezados);

$filas = $conexion->query("SELECT * FROM historicocontroldecalidad")->fetchAll(PDO::FETCH_NUM);
foreach ($filas as $fila) {
    fputcsv($salida, $fila);
}
fclose($salida);
exit();