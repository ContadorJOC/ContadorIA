<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$id = $_GET['id'];
$conexion->prepare("DELETE FROM permisos WHERE id = ?")->execute([$id]);

header("Location: permisos.php");
exit();