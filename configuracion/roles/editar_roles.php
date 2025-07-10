<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");


$id = $_GET['id'];
$dato = $conexion->query("SELECT * FROM roles WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
$columnas = $conexion->query("PRAGMA table_info(roles)")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_roles.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h2>✏️ Editar Registro</h2>
<form action="guardar_roles.php?id=<?= $id ?>" method="POST">
<?php foreach ($columnas as $col): if ($col['name'] == 'id') continue; ?>
    <label><?= $etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name'])) ?>:</label><br>
    <input type="text" name="<?= $col['name'] ?>" value="<?= htmlspecialchars($dato[$col['name']]) ?>" style="width: 100%;"><br><br>
<?php endforeach; ?>
    <button type="submit" style="padding:10px 20px;">Actualizar</button>
</form>