<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once("../../conexion/conexion.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    try {
        $conexion->prepare("DELETE FROM saldodetelas WHERE id=?")->execute([$id]);
        header("Location: index.php?msg=eliminado");
        exit();
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}