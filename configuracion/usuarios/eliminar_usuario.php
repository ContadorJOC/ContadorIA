<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}

require_once("{$nivel}conexion/conexion.php");

$id = $_GET['id'] ?? null;

if ($id !== null) {
    try {
        $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        echo "❌ Error al eliminar usuario: " . $e->getMessage();
        exit();
    }
}

header("Location: usuarios.php");
exit();
