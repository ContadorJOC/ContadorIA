<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}
require_once('../../conexion/conexion.php');
$id = intval($_GET['id']);
$conexion->prepare("DELETE FROM incapacidades WHERE id=?")->execute([$id]);
header("Location: index.php");
exit();