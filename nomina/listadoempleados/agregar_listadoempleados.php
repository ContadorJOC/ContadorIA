<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once('../../componentes/plantilla.php');
require_once('../../conexion/conexion.php');

$columnas = $conexion->query("PRAGMA table_info(listadoempleados)")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_listadoempleados.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Empleado</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 20px;
}
h2 {
    text-align: center;
    color: #2c3e50;
}
form {
    max-width: 700px;
    margin: auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.form-group {
    position: relative;
    margin-bottom: 20px;
}
.form-group input {
    width: 100%;
    padding: 12px 10px 12px 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background: #fdfdfd;
    font-size: 15px;
    outline: none;
}
.form-group label {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    background: #fff;
    padding: 0 4px;
    color: #888;
    transition: all 0.2s;
    pointer-events: none;
}
.form-group input:focus + label,
.form-group input:not(:placeholder-shown) + label {
    top: -8px;
    left: 10px;
    font-size: 12px;
    color: #3498db;
}
small {
    color: red;
    display: none;
    margin-top: -15px;
    margin-bottom: 10px;
    font-size: 13px;
}
button {
    background-color: #27ae60;
    color: white;
    padding: 12px 18px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    display: block;
    width: 100%;
}
button:hover {
    background-color: #219150;
}
</style>
</head>
<body>

<h2>➕ Agregar a listadoempleados</h2>

<form id="formEmpleado" action='guardar_listadoempleados.php' method='POST'>
<?php foreach ($columnas as $col): if ($col['name'] == 'id') continue; ?>
    <div class="form-group">
        <input 
            type='text' 
            name='<?= $col['name'] ?>' 
            id='<?= $col['name'] ?>' 
            placeholder=' ' 
            <?= $col['name'] === 'cedula' ? 'required' : '' ?>>
        <label for='<?= $col['name'] ?>'><?= $etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name'])) ?></label>
    </div>
    <?php if ($col['name'] === 'cedula'): ?>
        <small id="cedulaMensaje">⚠️ Esta cédula ya está registrada.</small>
    <?php endif; ?>
<?php endforeach; ?>
    <button type='submit' id="btnGuardar">💾 Guardar Empleado</button>
</form>

<script>
document.getElementById('cedula').addEventListener('input', async function () {
    const cedula = this.value.trim();
    const mensaje = document.getElementById('cedulaMensaje');
    const boton = document.getElementById('btnGuardar');

    if (cedula.length > 0) {
        const res = await fetch(`verificar_cedula.php?cedula=${cedula}`);
        const data = await res.json();

        if (data.existe) {
            mensaje.style.display = 'block';
            boton.disabled = true;
        } else {
            mensaje.style.display = 'none';
            boton.disabled = false;
        }
    } else {
        mensaje.style.display = 'none';
        boton.disabled = false;
    }
});
</script>

</body>
</html>
