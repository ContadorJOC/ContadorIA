<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$id = $_GET['id'];
$conexion->prepare("DELETE FROM bodegas WHERE id = ?")->execute([$id]);

header("Location: bodegas.php");
exit();