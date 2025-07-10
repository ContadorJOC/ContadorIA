<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../../login/formulario.php");
    exit();
}

// ✅ Ajuste de nivel correcto
require_once("../../../../componentes/plantilla.php");
require_once("../../../../conexion/conexion.php");

// ✅ Consultar columnas y datos
try {
    $columnas = $conexion->query("PRAGMA table_info(historicocontroldecalidad)")->fetchAll(PDO::FETCH_ASSOC);
    $datos = $conexion->query("SELECT * FROM historicocontroldecalidad ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div style='
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
    <strong>❌ Error al cargar datos:</strong><br>" . htmlspecialchars($e->getMessage()) . "
    <br><br><a href='javascript:history.back()' style='color:#2980b9;'>⬅ Volver</a>
    </div>";
    exit();
}

// ✅ Leer etiquetas desde JSON si existe
$json = "encabezados_historicocontroldecalidad.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h2 style="font-family:'Segoe UI'; font-size:24px; text-align:center; margin-top:15px;">📋 <?= strtoupper("HISTORICO CONTROL DE CALIDAD") ?></h2>
<div style="text-align:center; margin-bottom:15px;">
    <a href="agregar_historicocontroldecalidad.php" style="background:#3498db;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-size:14px;">➕ Agregar</a>
    <a href="exportar_historicocontroldecalidad.php" style="background:#2ecc71;color:#fff;padding:10px 16px;border-radius:8px;text-decoration:none;font-size:14px; margin-left:8px;">📥 Exportar Excel</a>
</div>

<div class="contenedor-tabla" style="margin: 0 auto; max-width:1200px; overflow-x:auto;">
    <table style="width:100%; border-collapse:collapse; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        <thead style="background:#2980b9; color:#fff;">
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <th style="padding:12px; text-align:center;"><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
                <?php endforeach; ?>
                <th style="padding:12px; text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($datos) > 0): ?>
                <?php foreach ($datos as $fila): ?>
                <tr style="border-bottom:1px solid #ecf0f1;">
                    <?php foreach ($columnas as $col): ?>
                        <td style="padding:10px; text-align:center;"><?= htmlspecialchars($fila[$col['name']]) ?></td>
                    <?php endforeach; ?>
                    <td style="padding:10px; text-align:center;">
                        <a href="editar_historicocontroldecalidad.php?id=<?= $fila['id'] ?>" title="Editar" style="background:#27ae60;color:#fff;padding:6px 10px;border-radius:5px;text-decoration:none;margin-right:5px;">✏️ Editar</a>
                        <a href="eliminar_historicocontroldecalidad.php?id=<?= $fila['id'] ?>" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar este registro?')" style="background:#e74c3c;color:#fff;padding:6px 10px;border-radius:5px;text-decoration:none;">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($columnas) + 1 ?>" style="padding:15px; text-align:center; color:#7f8c8d;">
                        ⚠️ No hay registros para mostrar.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ✅ Estilos -->
<style>
    body {
        background:#f4f6f9;
        font-family:'Segoe UI', sans-serif;
    }
    table thead th {
        font-size:14px;
        text-transform:uppercase;
        letter-spacing:0.5px;
    }
    table tbody tr:hover {
        background:#f1f6f9;
    }
</style>
