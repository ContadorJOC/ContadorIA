<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once("../../conexion/conexion.php");

// Obtener el ID del registro
$id = intval($_GET['id'] ?? 0);

// Verificar si el registro existe
$registro = $conexion->query("SELECT * FROM cuentasbancarias WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
if (!$registro) {
    echo "<p style='color:red;text-align:center;'>❌ Registro no encontrado</p>";
    exit();
}

// Procesar actualización al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $empleado = trim($_POST['empleado']);
    $banco = trim($_POST['banco']);
    $cuenta = trim($_POST['cuenta']);

    $sql = "UPDATE cuentasbancarias SET empleado=?, banco=?, cuenta=? WHERE id=?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$empleado, $banco, $cuenta, $id]);

    header("Location: index.php");
    exit();
}

require_once("../../componentes/plantilla.php");
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">✏️ Editar <?= strtoupper("cuentasbancarias") ?></h2>

<form method="POST" style="max-width:600px;margin:auto;">
    <label for="empleado" style="margin-top:10px;">EMPLEADO:</label>
    <input type="text" name="empleado" id="empleado" value="<?= htmlspecialchars($registro['empleado']) ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <label for="banco" style="margin-top:10px;">BANCO:</label>
    <input type="text" name="banco" id="banco" value="<?= htmlspecialchars($registro['banco']) ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <label for="cuenta" style="margin-top:10px;">CUENTA:</label>
    <input type="text" name="cuenta" id="cuenta" value="<?= htmlspecialchars($registro['cuenta']) ?>" required style="width:100%;padding:8px;border-radius:6px;border:1px solid #ccc;">

    <button type="submit" style="margin-top:15px;background:#2980b9;color:#fff;padding:10px 15px;border:none;border-radius:6px;">💾 Actualizar</button>
    <a href="index.php" style="margin-left:10px;background:#7f8c8d;color:#fff;padding:10px 15px;border:none;border-radius:6px;text-decoration:none;">↩️ Cancelar</a>
</form>
