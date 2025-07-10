<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

// Corregimos la ruta porque ahora está 3 niveles más profundo
require_once("../../../conexion/conexion.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $stmt = $conexion->prepare("DELETE FROM inventariodetelas WHERE id = ?");
        $stmt->execute([$id]);

        // Redirige con un mensaje de éxito
        header("Location: index.php?msg=eliminado");
        exit();
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
} else {
    echo "⚠️ ID no proporcionado.";
}
