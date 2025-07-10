<?php
require_once('../../conexion/conexion.php');

$cedula = $_GET['cedula'] ?? '';
$existe = false;

if ($cedula !== '') {
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM listadoempleados WHERE cedula = ?");
    $stmt->execute([$cedula]);
    $existe = $stmt->fetchColumn() > 0;
}

echo json_encode(['existe' => $existe]);
