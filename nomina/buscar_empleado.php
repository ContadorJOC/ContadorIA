<?php
require_once("../../conexion/conexion.php");

$term = $_GET['term'] ?? '';

$sql = "SELECT cedula, nombre_completo FROM listadoempleados 
        WHERE nombre_completo LIKE :term 
        ORDER BY nombre_completo ASC LIMIT 20";

$stmt = $conexion->prepare($sql);
$stmt->execute([':term' => "%$term%"]);
$resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($resultados as $row) {
    $data[] = [
        'id' => $row['cedula'], // Lo que se guarda
        'text' => $row['nombre_completo'] . " (" . $row['cedula'] . ")"
    ];
}

echo json_encode(['results' => $data]);
