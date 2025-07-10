<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /produccion/login/formulario.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/produccion/conexion/conexion.php");

// Obtener código de tela
$codigo_tela = isset($_GET['codigo']) ? $_GET['codigo'] : null;
if (!$codigo_tela) {
    echo "<p style='color:red;'>❌ Código de tela no proporcionado.</p>";
    exit();
}

// Obtener datos de la tela
$stmtTela = $conexion->prepare("SELECT * FROM inventariodetelas WHERE codigo = ?");
$stmtTela->execute([$codigo_tela]);
$datos_tela = $stmtTela->fetch(PDO::FETCH_ASSOC);
if (!$datos_tela) {
    echo "<p style='color:red;'>❌ Tela no encontrada.</p>";
    exit();
}

// Traer metrajes de la tela
$stmtMet = $conexion->prepare("SELECT * FROM metrajes WHERE codigo_tela = ? ORDER BY id DESC");
$stmtMet->execute([$codigo_tela]);
$datos_metrajes = $stmtMet->fetchAll(PDO::FETCH_ASSOC);

// Traer columnas
$columnas = $conexion->query("PRAGMA table_info(metrajes)")->fetchAll(PDO::FETCH_ASSOC);

// Cargar encabezados desde JSON
$json = $_SERVER['DOCUMENT_ROOT'] . "/produccion/telas/inventariodetelas/metrajes/encabezados_metrajes.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h3 style="font-family:'Segoe UI'; margin-bottom:10px;">📦 Tela: <?= htmlspecialchars($datos_tela['codigo']) ?></h3>

<a href="agregar_metrajes.php?codigo=<?= urlencode($codigo_tela) ?>" 
   style="background:#3498db;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none;">
   ➕ Agregar Metraje
</a>

<div class="contenedor-tabla" style="margin-top:15px; overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.05);">
        <thead style="background:#ecf6fd;color:#2c3e50;">
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <?php if ($col['name'] !== 'id'): ?>
                        <th style="padding:10px;text-align:left;">
                            <?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?>
                        </th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th style="padding:10px;text-align:left;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($datos_metrajes) > 0): ?>
                <?php foreach ($datos_metrajes as $fila): ?>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <?php if ($col['name'] !== 'id'): ?>
                            <?php if ($col['name'] === 'saldo'): ?>
                                <!-- ✅ Calcular saldo: metros - cortado -->
                                <?php
                                    $metros = (float)$fila['metros'];
                                    $cortado = (float)$fila['cortado'];
                                    $saldo_calculado = $metros - $cortado;
                                    if ($saldo_calculado < 0) $saldo_calculado = 0;
                                ?>
                                <td style="padding:10px; font-weight:bold; color:<?= $saldo_calculado <= 0 ? '#e74c3c' : '#27ae60'; ?>;">
                                    <?= number_format($saldo_calculado, 2, '.', '') ?>
                                </td>
                            <?php else: ?>
                                <td style="padding:10px;"><?= htmlspecialchars($fila[$col['name']]) ?></td>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td style="padding:10px;">
                        <a href="editar_metrajes.php?id=<?= $fila['id'] ?>" style="color:#2980b9;text-decoration:none;">✏️ Editar</a>
                        <a href="eliminar_metrajes.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este metraje?');" style="color:#e74c3c;text-decoration:none;margin-left:8px;">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($columnas) ?>" style="padding:10px;text-align:center;color:#7f8c8d;">
                        No hay metrajes registrados para esta tela.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
