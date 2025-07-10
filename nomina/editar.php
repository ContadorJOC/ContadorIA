<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once '../componentes/plantilla_nomina.php';
require_once('../../conexion/conexion.php');

// Obtener ID desde URL
$id = $_GET['id'] ?? null;

// Cargar datos actuales del empleado
$stmt = $conexion->prepare("SELECT * FROM empleados WHERE id = ?");
$stmt->execute([$id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

// Si se envió el formulario, actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conexion->prepare("UPDATE empleados SET
        t_id=?, cedula=?, nombre=?, celular=?, fecha_ingreso=?, hora=?, fecha_contrato=?,
        cargo=?, jornada=?, tipo_contrato=?, tipo_soporte=?, documento=?, correo=?,
        direccion=?, salud=?, pension=?, caja=?, arl=?, objeto_cargo=?, area=?,
        estado=?, fecha_examen=?, fecha_retiro=?
        WHERE id=?");

    $stmt->execute([
        $_POST['t_id'], $_POST['cedula'], $_POST['nombre'], $_POST['celular'], $_POST['fecha_ingreso'], $_POST['hora'],
        $_POST['fecha_contrato'], $_POST['cargo'], $_POST['jornada'], $_POST['tipo_contrato'], $_POST['tipo_soporte'],
        $_POST['documento'], $_POST['correo'], $_POST['direccion'], $_POST['salud'], $_POST['pension'],
        $_POST['caja'], $_POST['arl'], $_POST['objeto_cargo'], $_POST['area'], $_POST['estado'],
        $_POST['fecha_examen'], $_POST['fecha_retiro'], $id
    ]);

    header("Location: index.php?editado=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Empleado</title>
</head>
<body>
    <h2>Editar Empleado: <?= $empleado['nombre'] ?></h2>
    <form method="POST">
        <input name="t_id" value="<?= $empleado['t_id'] ?>" required>
        <input name="cedula" value="<?= $empleado['cedula'] ?>" required>
        <input name="nombre" value="<?= $empleado['nombre'] ?>" required>
        <input name="celular" value="<?= $empleado['celular'] ?>">
        <input name="fecha_ingreso" value="<?= $empleado['fecha_ingreso'] ?>">
        <input name="hora" value="<?= $empleado['hora'] ?>">
        <input name="fecha_contrato" value="<?= $empleado['fecha_contrato'] ?>">
        <input name="cargo" value="<?= $empleado['cargo'] ?>">
        <input name="jornada" value="<?= $empleado['jornada'] ?>">
        <input name="tipo_contrato" value="<?= $empleado['tipo_contrato'] ?>">
        <input name="tipo_soporte" value="<?= $empleado['tipo_soporte'] ?>">
        <input name="documento" value="<?= $empleado['documento'] ?>">
        <input name="correo" value="<?= $empleado['correo'] ?>">
        <input name="direccion" value="<?= $empleado['direccion'] ?>">
        <input name="salud" value="<?= $empleado['salud'] ?>">
        <input name="pension" value="<?= $empleado['pension'] ?>">
        <input name="caja" value="<?= $empleado['caja'] ?>">
        <input name="arl" value="<?= $empleado['arl'] ?>">
        <input name="objeto_cargo" value="<?= $empleado['objeto_cargo'] ?>">
        <input name="area" value="<?= $empleado['area'] ?>">
        <input name="estado" value="<?= $empleado['estado'] ?>">
        <input name="fecha_examen" value="<?= $empleado['fecha_examen'] ?>">
        <input name="fecha_retiro" value="<?= $empleado['fecha_retiro'] ?>">
        <button type="submit">Guardar Cambios</button>
    </form>
    <br>
    <a href="index.php">⬅ Volver</a>
</body>
</html>
