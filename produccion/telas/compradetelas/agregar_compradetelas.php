<?php
session_start();

// Calcular ruta automáticamente
$nivel = str_repeat("../", substr_count(__DIR__, DIRECTORY_SEPARATOR) - substr_count(realpath($_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR));

if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}

require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

// ... resto del código ...

?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">➕ Agregar <?= strtoupper("compradetelas") ?></h2>

<form action="guardar_compradetelas.php" method="POST" style="max-width:600px;margin:auto;">
    
    <label for="fecha" style="margin-top:10px;">FECHA:</label>
    <input type="text" name="fecha" id="fecha" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="factura_de_compra" style="margin-top:10px;">FACTURA DE COMPRA:</label>
    <input type="text" name="factura_de_compra" id="factura_de_compra" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="proveedor" style="margin-top:10px;">PROVEEDOR:</label>
    <input type="text" name="proveedor" id="proveedor" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="codigo" style="margin-top:10px;">CODIGO:</label>
    <input type="text" name="codigo" id="codigo" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="referencia" style="margin-top:10px;">REFERENCIA:</label>
    <input type="text" name="referencia" id="referencia" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="metros" style="margin-top:10px;">METROS:</label>
    <input type="text" name="metros" id="metros" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="costo_sin_iva" style="margin-top:10px;">COSTO SIN IVA:</label>
    <input type="text" name="costo_sin_iva" id="costo_sin_iva" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="costo_con_iva" style="margin-top:10px;">COSTO CON IVA:</label>
    <input type="text" name="costo_con_iva" id="costo_con_iva" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="base" style="margin-top:10px;">BASE:</label>
    <input type="text" name="base" id="base" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="total_con_iva" style="margin-top:10px;">TOTAL CON IVA:</label>
    <input type="text" name="total_con_iva" id="total_con_iva" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <label for="observaciones" style="margin-top:10px;">OBSERVACIONES:</label>
    <input type="text" name="observaciones" id="observaciones" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">
    <button type="submit" style="margin-top:15px;background:#27ae60;color:#fff;padding:10px 15px;border:none;border-radius:6px;">💾 Guardar</button>
</form>