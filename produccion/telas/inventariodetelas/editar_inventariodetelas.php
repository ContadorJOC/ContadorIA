<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

// ✅ Cargar la plantilla y la conexión directamente (sin autoload)
require_once("../../../componentes/plantilla.php");
require_once("../../../conexion/conexion.php");

// ✅ Validar que se recibe el ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div style='
        max-width:600px;
        margin:50px auto;
        padding:20px;
        background:#ffe6e6;
        color:#c0392b;
        border:1px solid #e74c3c;
        border-radius:8px;
        text-align:center;
        font-family:Segoe UI;
    '>
    ❌ <strong>Error:</strong> ID no especificado.
    <br><br><a href='index.php' style='color:#2980b9;'>⬅️ Volver</a>
    </div>");
}

$id = intval($_GET['id']);

// ✅ Obtener datos del registro
$stmt = $conexion->prepare("SELECT * FROM inventariodetelas WHERE id = ?");
$stmt->execute([$id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$registro) {
    die("<div style='
        max-width:600px;
        margin:50px auto;
        padding:20px;
        background:#ffe6e6;
        color:#c0392b;
        border:1px solid #e74c3c;
        border-radius:8px;
        text-align:center;
        font-family:Segoe UI;
    '>
    ❌ <strong>Error:</strong> Registro no encontrado.
    <br><br><a href='index.php' style='color:#2980b9;'>⬅️ Volver</a>
    </div>");
}
?>

<h2 style="font-family:'Segoe UI'; font-size:22px; text-align:center;">✏️ Editar <?= strtoupper("Inventario de Telas") ?></h2>

<form action="actualizar_inventariodetelas.php" method="POST" style="max-width:600px;margin:30px auto;padding:20px;border:1px solid #ccc;border-radius:10px;background:#f9f9f9;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
    <!-- Campo oculto con el ID -->
    <input type="hidden" name="id" value="<?= htmlspecialchars($registro['id']) ?>">

    <label for="codigo" style="margin-top:10px;display:block;">🔢 CODIGO:</label>
    <input type="text" name="codigo" id="codigo" value="<?= htmlspecialchars($registro['codigo']) ?>" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="nombre_de_tela" style="margin-top:10px;display:block;">📝 NOMBRE DE TELA:</label>
    <input type="text" name="nombre_de_tela" id="nombre_de_tela" value="<?= htmlspecialchars($registro['nombre_de_tela']) ?>" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <label for="saldo" style="margin-top:10px;display:block;">📊 SALDO:</label>
    <input type="number" step="0.01" name="saldo" id="saldo" value="<?= htmlspecialchars($registro['saldo']) ?>" required style="width:100%;padding:10px;border-radius:6px;border:1px solid #ccc;">

    <button type="submit" style="margin-top:20px;background:#f39c12;color:#fff;padding:12px 20px;border:none;border-radius:8px;font-size:16px;cursor:pointer;">💾 Actualizar</button>
    <a href="index.php" style="margin-left:10px; text-decoration:none; color:#2980b9;">⬅️ Cancelar</a>
</form>
