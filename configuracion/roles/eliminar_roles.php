<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$id = $_GET['id'];
$conexion->prepare("DELETE FROM roles WHERE id = ?")->execute([$id]);

header("Location: roles.php");
exit();