<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$id = $_GET['id'];
$conexion->prepare("DELETE FROM existenciasgenerales WHERE id = ?")->execute([$id]);

header("Location: existenciasgenerales.php");
exit();