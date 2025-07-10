<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

require_once("../../../componentes/plantilla.php");
require_once("../../../conexion/conexion.php");
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">➕ Agregar <?= strtoupper("inventariodetelas") ?></h2>

<form action="guardar_inventariodetelas.php" method="POST" style="max-width:600px;margin:auto;">
    <label for="codigo" style="margin-top:10px;">CODIGO:</label>
    <input type="text" name="codigo" id="codigo" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <label for="nombre_de_tela" style="margin-top:10px;">NOMBRE DE TELA:</label>
    <input type="text" name="nombre_de_tela" id="nombre_de_tela" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <label for="saldo" style="margin-top:10px;">SALDO:</label>
    <input type="number" name="saldo" id="saldo" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <button type="submit" style="margin-top:15px;background:#27ae60;color:#fff;padding:10px 15px;border:none;border-radius:6px;">💾 Guardar</button>
</form>
