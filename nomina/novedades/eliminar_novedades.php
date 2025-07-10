<?php
$nivel = '../../'; // Desde /nomina/novedades/ hacia la raíz
require_once("{$nivel}conexion/conexion.php");

$id = $_GET['id'];
$conexion->prepare("DELETE FROM novedades WHERE id = ?")->execute([$id]);

header("Location: novedades.php");
exit();
