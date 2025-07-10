<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once('../../conexion/conexion.php');

// Obtener todas las columnas excepto 'id'
$columnas = $conexion->query("PRAGMA table_info(listadoempleados)")->fetchAll(PDO::FETCH_ASSOC);
$campos = [];
$valores = [];

foreach ($columnas as $col) {
    $nombre = $col['name'];
    if ($nombre === 'id') continue;

    // Asegúrate de sanitizar cada campo recibido del formulario
    $valor = $_POST[$nombre] ?? '';
    $campos[] = $nombre;
    $valores[$nombre] = $valor;
}

// Validación: verificar si la cédula ya existe
$cedula = $valores['cedula'] ?? '';

$stmt = $conexion->prepare("SELECT COUNT(*) FROM listadoempleados WHERE cedula = ?");
$stmt->execute([$cedula]);
if ($stmt->fetchColumn() > 0) {
    echo "<script>alert('❌ Ya existe un empleado con esta cédula.'); window.history.back();</script>";
    exit();
}

// Construir consulta dinámica
$nombresCampos = implode(",", $campos);
$marcadores = ":" . implode(", :", $campos);
$sql = "INSERT INTO listadoempleados ($nombresCampos) VALUES ($marcadores)";
$stmt = $conexion->prepare($sql);
$stmt->execute($valores);

// Redireccionar al listado
header("Location: listadoempleados.php");
exit();
