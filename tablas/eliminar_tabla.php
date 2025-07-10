<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
require_once('../conexion/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tabla'])) {
    $tabla = trim($_POST['tabla']);

    // Validar que la tabla exista
    $existe = $conexion->query("SELECT name FROM sqlite_master WHERE type='table' AND name='$tabla'")->fetchColumn();
    if ($existe) {
        try {
            $conexion->exec("DROP TABLE IF EXISTS `$tabla`");
            header("Location: index.php?mensaje=eliminada");
            exit();
        } catch (PDOException $e) {
            echo "❌ Error al eliminar la tabla: " . $e->getMessage();
        }
    } else {
        echo "⚠️ La tabla no existe.";
    }
} else {
    echo "⚠️ Solicitud inválida.";
}
