<?php
require_once("../../../conexion/conexion.php");

// Configurar cabeceras para descargar como Excel (aunque sea CSV)
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="inventariodetelas.xls"');
header('Cache-Control: max-age=0');

// Abrir flujo de salida
$salida = fopen('php://output', 'w');

// Obtener columnas, excluir la columna "id"
$columnas = $conexion->query("PRAGMA table_info(inventariodetelas)")->fetchAll(PDO::FETCH_ASSOC);
$columnasFiltradas = array_filter($columnas, fn($col) => $col['name'] !== 'id');
$encabezados = array_column($columnasFiltradas, 'name');

// Escribir encabezados
fputcsv($salida, array_map('strtoupper', $encabezados), "\t"); // Tab como separador

// Obtener datos sin la columna id
$datos = $conexion->query("SELECT * FROM inventariodetelas")->fetchAll(PDO::FETCH_ASSOC);
foreach ($datos as $fila) {
    $filaFiltrada = [];
    foreach ($encabezados as $columna) {
        $filaFiltrada[] = $fila[$columna];
    }
    fputcsv($salida, $filaFiltrada, "\t"); // Tab como separador
}

fclose($salida);
exit();
