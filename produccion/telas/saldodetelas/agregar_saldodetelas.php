<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

// ✅ Incluye la plantilla y la conexión directamente (sin autoload)
require_once("../../../componentes/plantilla.php");
require_once("../../../conexion/conexion.php");
?>

<h2 style="font-family:'Segoe UI', sans-serif; font-size:22px; text-align:center;">➕ Agregar <?= strtoupper("saldodetelas") ?></h2>

<form action="guardar_saldodetelas.php" method="POST" style="max-width:600px;margin:30px auto;padding:20px;border:1px solid #ccc;border-radius:10px;background:#f9f9f9;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <label for="codigo" style="margin-top:10px;display:block;">🔢 CODIGO:</label>
    <input type="text" name="codigo" id="codigo" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="nombre" style="margin-top:10px;display:block;">📝 NOMBRE:</label>
    <input type="text" name="nombre" id="nombre" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="proveedor" style="margin-top:10px;display:block;">🏢 PROVEEDOR:</label>
    <input type="text" name="proveedor" id="proveedor" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="saldo_inicial" style="margin-top:10px;display:block;">💰 SALDO INICIAL:</label>
    <input type="number" step="0.01" name="saldo_inicial" id="saldo_inicial" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="entradas" style="margin-top:10px;display:block;">📥 ENTRADAS:</label>
    <input type="number" step="0.01" name="entradas" id="entradas" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="salidas" style="margin-top:10px;display:block;">📤 SALIDAS:</label>
    <input type="number" step="0.01" name="salidas" id="salidas" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="saldo" style="margin-top:10px;display:block;">📊 SALDO:</label>
    <input type="number" step="0.01" name="saldo" id="saldo" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <button type="submit" style="margin-top:20px;background:#27ae60;color:#fff;padding:12px 20px;border:none;border-radius:8px;font-size:16px;cursor:pointer;">💾 Guardar</button>
</form>
