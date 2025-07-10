<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/conexion/conexion.php");

// Obtener código de tela
$codigo_tela = isset($_GET['codigo']) ? $_GET['codigo'] : null;
if (!$codigo_tela) {
    echo "<p style='color:red;'>❌ Código de tela no proporcionado.</p>";
    exit();
}

// Datos de la tela
$tela = $conexion->prepare("SELECT * FROM inventariodetelas WHERE codigo = ?");
$tela->execute([$codigo_tela]);
$datos_tela = $tela->fetch(PDO::FETCH_ASSOC);
if (!$datos_tela) {
    echo "<p style='color:red;'>❌ Tela no encontrada.</p>";
    exit();
}

// Metrajes
$metrajes = $conexion->prepare("SELECT * FROM metrajes WHERE codigo_tela = ? ORDER BY id DESC");
$metrajes->execute([$codigo_tela]);
$datos_metrajes = $metrajes->fetchAll(PDO::FETCH_ASSOC);

// Columnas
$columnas = $conexion->query("PRAGMA table_info(metrajes)")->fetchAll(PDO::FETCH_ASSOC);

// JSON de encabezados
$json = $_SERVER['DOCUMENT_ROOT'] . "/telas/inventariodetelas/metrajes/encabezados_metrajes.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];

$total_metros = 0;
$total_cortado = 0;
$total_saldo = 0;
?>

<h3 style="font-family:'Segoe UI'; margin-bottom:10px;">📦 Tela: <?= htmlspecialchars($datos_tela['codigo']) ?></h3>

<!-- ✅ Botones de acciones alineados y centrados -->
<div style="margin-bottom:15px; display:flex; justify-content:center; flex-wrap:wrap; gap:10px;">
    <!-- Botón Agregar Metraje -->
    <a href="agregar_metrajes.php?codigo=<?= urlencode($codigo_tela) ?>" 
       style="background:#3498db;color:#fff;padding:8px 15px;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
       ➕ Agregar
    </a>

    <!-- Botón Inventario -->
    <a href="index.php" 
       style="background:#2ecc71;color:#fff;padding:8px 15px;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
       📋 Inventario
    </a>

    <!-- Botón Imprimir -->
    <a href="imprimir.php?codigo=<?= urlencode($codigo_tela) ?>" target="_blank" 
       style="background:#e67e22;color:#fff;padding:8px 15px;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
       🖨️ Imprimir
    </a>

    <!-- Botón Excel -->
    <a href="exportar_excel.php?codigo=<?= urlencode($codigo_tela) ?>" 
       style="background:#27ae60;color:#fff;padding:8px 15px;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
       📥 Excel
    </a>
    <a href="descargar_plantilla.php?codigo=<?= urlencode($codigo_tela) ?>" 
       style="background:#8e55ad; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none;">
        📥 Plantilla
    </a>
    <!-- Botón Importar -->
    <form action="importar_metrajes.php" method="POST" enctype="multipart/form-data" style="display:inline-block;">
        <input type="hidden" name="codigo_tela" value="<?= htmlspecialchars($codigo_tela) ?>">
        <label style="background:#9b59b6;color:#fff;padding:8px 15px;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 4px rgba(0,0,0,0.1);">
            📤 Importar
            <input type="file" name="archivo" accept=".csv,.xlsx,.xls" style="display:none;" onchange="this.form.submit()">
        </label>
    </form>
</div>

<!-- ✅ Tabla de Metrajes -->
<div class="contenedor-tabla" style="margin-top:15px; overflow-x:auto;">
    <table id="tablaMetrajes" style="width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
        <thead style="background:#ecf6fd; color:#2c3e50;">
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <?php if ($col['name'] !== 'id' && $col['name'] !== 'rollo'): ?>
                        <th style="padding:10px; text-align:center;" data-col="<?= $col['name'] ?>">
                            <?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?>
                        </th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th style="padding:10px; text-align:center;">#</th>
                <th style="padding:10px; text-align:center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($datos_metrajes) > 0): ?>
                <?php $contador = 1; ?>
                <?php foreach ($datos_metrajes as $fila): ?>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <?php if ($col['name'] !== 'id' && $col['name'] !== 'rollo'): ?>
                            <?php 
                            // ✅ Sumar totales
                            if ($col['name'] === 'metros') $total_metros += (float)$fila[$col['name']];
                            if ($col['name'] === 'cortado') $total_cortado += (float)$fila[$col['name']];
                            ?>
                            <?php if ($col['name'] === 'saldo'): ?>
                                <!-- ✅ Calcular saldo dinámico -->
                                <?php 
                                $saldo = (float)$fila['metros'] - (float)$fila['cortado'];
                                $total_saldo += $saldo;
                                ?>
                                <td style="padding:10px; text-align:center;" data-col="<?= $col['name'] ?>">
                                    <?= number_format($saldo, 2) ?>
                                </td>
                            <?php else: ?>
                                <td style="padding:10px; text-align:center;" data-col="<?= $col['name'] ?>">
                                    <?php if ($col['name'] === 'metros'): ?>
                                        <?= number_format((float)$fila[$col['name']], 2) ?>
                                    <?php else: ?>
                                        <?= htmlspecialchars($fila[$col['name']]) ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td style="padding:10px; text-align:center;"><?= $contador ?></td>
                    <td style="padding:10px; text-align:center;" data-no-print>
                        <a href="editar_metrajes.php?id=<?= $fila['id'] ?>" 
                           style="color:#2980b9; text-decoration:none; margin-right:6px;" title="Editar">✏️</a>
                        <a href="eliminar_metrajes.php?id=<?= $fila['id'] ?>" 
                           onclick="return confirm('¿Eliminar este metraje?');" 
                           style="color:#e74c3c; text-decoration:none;" title="Eliminar">🗑️</a>
                    </td>
                </tr>
                <?php $contador++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= count($columnas) + 2 ?>" style="padding:10px; text-align:center; color:#7f8c8d;">
                        No hay metrajes registrados para esta tela.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
        <?php if (count($datos_metrajes) > 0): ?>
        <tfoot>
            <tr style="background:#f1f6fa; font-weight:bold;">
                <?php foreach ($columnas as $col): ?>
                    <?php if ($col['name'] !== 'id' && $col['name'] !== 'rollo'): ?>
                        <?php if ($col['name'] === 'metros'): ?>
                            <td style="padding:10px; text-align:center; background:#ecf6fd;">
                                 <?= number_format($total_metros, 2) ?> 
                            </td>
                        <?php elseif ($col['name'] === 'cortado'): ?>
                            <td style="padding:10px; text-align:center; background:#ecf6fd;">
                                 <?= number_format($total_cortado, 2) ?> 
                            </td>
                        <?php elseif ($col['name'] === 'saldo'): ?>
                            <td style="padding:10px; text-align:center; background:#ecf6fd;">
                                 <?= number_format($total_saldo, 2) ?> 
                            </td>
                        <?php else: ?>
                            <td style="padding:10px; text-align:center;">-</td>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td style="padding:10px; text-align:center;">-</td>
                <td style="padding:10px; text-align:center;" data-no-print>-</td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
</div>
