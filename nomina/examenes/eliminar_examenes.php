<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$id = $_GET['id'];
$conexion->prepare("DELETE FROM examenes WHERE id = ?")->execute([$id]);

header("Location: examenes.php");
exit();