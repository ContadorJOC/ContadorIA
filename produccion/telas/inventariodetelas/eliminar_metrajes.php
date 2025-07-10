<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/conexion/conexion.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>❌ ID no válido.</p>";
    exit();
}

$id = intval($_GET['id']);

// Obtenemos código_tela antes de eliminar
$stmt = $conexion->prepare("SELECT codigo_tela FROM metrajes WHERE id = ?");
$stmt->execute([$id]);
$fila = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$fila) {
    echo "<p style='color:red;'>❌ Metraje no encontrado.</p>";
    exit();
}

$codigo_tela = $fila['codigo_tela'];

// Eliminamos
$delete = $conexion->prepare("DELETE FROM metrajes WHERE id = ?");
$delete->execute([$id]);

// Redirigimos al modal correcto con mensaje opcional
header(
  "Location: /telas/inventariodetelas/metrajes/index.php"
  . "?codigo=" . urlencode($codigo_tela)
  . "&msg=eliminado"
);
exit();
