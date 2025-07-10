<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once("../../componentes/plantilla.php");
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">➕ Agregar <?= strtoupper("entradadetelas") ?></h2>

<form action="guardar_entradadetelas.php" method="POST" style="max-width:600px;margin:auto;">
    
    <label for="codigo" style="margin-top:10px;">CODIGO:</label>
    <input type="text" name="codigo" id="codigo" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="referencia" style="margin-top:10px;">REFERENCIA:</label>
    <input type="text" name="referencia" id="referencia" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="proveedor" style="margin-top:10px;">PROVEEDOR:</label>
    <input type="text" name="proveedor" id="proveedor" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="cantidad" style="margin-top:10px;">CANTIDAD:</label>
    <input type="text" name="cantidad" id="cantidad" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="fecha" style="margin-top:10px;">FECHA:</label>
    <input type="text" name="fecha" id="fecha" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <button type="submit" style="margin-top:15px;background:#27ae60;color:#fff;padding:10px 15px;border:none;border-radius:6px;">💾 Guardar</button>
</form>