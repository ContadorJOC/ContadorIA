<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}

require_once("{$nivel}conexion/conexion.php");

if (isset($_GET['rol_id'])) {
    $rol_id = intval($_GET['rol_id']);
    try {
        $stmt = $conexion->prepare("SELECT modulo FROM permisos WHERE rol_id = ?");
        $stmt->execute([$rol_id]);
        $permisos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($permisos);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al obtener permisos: ' . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID de rol no proporcionado']);
}
?>
