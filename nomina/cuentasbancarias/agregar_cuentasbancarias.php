<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once("../../componentes/plantilla.php");
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">➕ Agregar <?= strtoupper("cuentasbancarias") ?></h2>

<form action="guardar_cuentasbancarias.php" method="POST" style="max-width:600px;margin:auto;">
    
    <label for="empleado" style="margin-top:10px;">EMPLEADO:</label>
    <input type="text" name="empleado" id="empleado" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="banco" style="margin-top:10px;">BANCO:</label>
    <input type="text" name="banco" id="banco" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="cuenta" style="margin-top:10px;">CUENTA:</label>
    <input type="text" name="cuenta" id="cuenta" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <button type="submit" style="margin-top:15px;background:#27ae60;color:#fff;padding:10px 15px;border:none;border-radius:6px;">💾 Guardar</button>
</form>