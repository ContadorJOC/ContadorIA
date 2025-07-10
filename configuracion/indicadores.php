<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}

require_once('../componentes/plantilla.php');
require_once('../conexion/conexion.php');

// Ruta al archivo de indicadores
$archivo = __DIR__ . '/indicadores_laborales.json';
$datos = file_exists($archivo) ? json_decode(file_get_contents($archivo), true) : [];

// Guardar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevos = [
        'salario_minimo' => floatval($_POST['salario_minimo']),
        'auxilio_transporte' => floatval($_POST['auxilio_transporte']),
        'seguridad_social' => floatval($_POST['seguridad_social']),
        'prestaciones' => floatval($_POST['prestaciones'])
    ];
    file_put_contents($archivo, json_encode($nuevos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $datos = $nuevos;
    echo "<div style='background:#e0ffe0; padding:10px; border:1px solid #a5d6a7;'>✅ Datos actualizados correctamente</div>";
}
?>

<div style="max-width:600px; margin:auto; padding-top:100px; font-family:'Segoe UI', sans-serif;">
    <h2 style="color:#2c3e50;">✏️ Editar Indicadores Laborales</h2>
    <form method="POST">
        <label>💰 Salario Mínimo Mensual</label>
        <input type="number" step="100" name="salario_minimo" value="<?= $datos['salario_minimo'] ?? '' ?>" required style="width:100%; margin-bottom:10px;">

        <label>🚎 Auxilio de Transporte</label>
        <input type="number" step="100" name="auxilio_transporte" value="<?= $datos['auxilio_transporte'] ?? '' ?>" required style="width:100%; margin-bottom:10px;">

        <label>🛡️ Seguridad Social (mensual)</label>
        <input type="number" step="100" name="seguridad_social" value="<?= $datos['seguridad_social'] ?? '' ?>" required style="width:100%; margin-bottom:10px;">

        <label>🏦 Prestaciones Sociales (mensual)</label>
        <input type="number" step="100" name="prestaciones" value="<?= $datos['prestaciones'] ?? '' ?>" required style="width:100%; margin-bottom:10px;">

        <button type="submit" style="background:#3498db; color:white; padding:10px 16px; border:none; border-radius:6px;">💾 Guardar</button>
    </form>
</div>
