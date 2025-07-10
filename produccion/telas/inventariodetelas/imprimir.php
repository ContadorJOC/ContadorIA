<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/conexion/conexion.php");

// ✅ Recibir código de tela
$codigo_tela = isset($_GET['codigo']) ? $_GET['codigo'] : null;
if (!$codigo_tela) {
    echo "<p style='color:red;'>❌ Código de tela no proporcionado.</p>";
    exit();
}

// ✅ Obtener datos de la tela
$tela = $conexion->prepare("SELECT * FROM inventariodetelas WHERE codigo = ?");
$tela->execute([$codigo_tela]);
$datos_tela = $tela->fetch(PDO::FETCH_ASSOC);
if (!$datos_tela) {
    echo "<p style='color:red;'>❌ Tela no encontrada.</p>";
    exit();
}

// ✅ Obtener metrajes
$metrajes = $conexion->prepare("SELECT codigo_tela, metros, ancho, cortado, saldo FROM metrajes WHERE codigo_tela = ? ORDER BY id DESC");
$metrajes->execute([$codigo_tela]);
$datos_metrajes = $metrajes->fetchAll(PDO::FETCH_ASSOC);

// ✅ Calcular total de metros
$total_metros = 0;
foreach ($datos_metrajes as $fila) {
    $total_metros += (float)$fila['metros'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Metrajes - <?= htmlspecialchars($codigo_tela) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 40px;
            background: #f8f9fa;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 28px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: center;
        }
        th {
            background: #3498db;
            color: #fff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background: #f2f6fc;
        }
        tr:hover {
            background: #eaf1fb;
        }
        tfoot td {
            font-weight: bold;
            background: #dfeffb;
            color: #2c3e50;
        }
    </style>
</head>
<body onload="window.print()">
    <h1><?= htmlspecialchars(strtoupper($datos_tela['codigo'])) ?></h1>

    <table>
        <thead>
            <tr>
                <th>CODIGO TELA</th>
                <th>METROS</th>
                <th>ANCHO</th>
                <th>CORTADO</th>
                <th>SALDO</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos_metrajes as $fila): ?>
            <tr>
                <td><?= htmlspecialchars($fila['codigo_tela']) ?></td>
                <td><?= number_format($fila['metros'], 2) ?></td>
                <td><?= htmlspecialchars($fila['ancho']) ?></td>
                <td><?= number_format($fila['cortado'], 2) ?></td>
                <td><?= number_format($fila['saldo'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong><?= number_format($total_metros, 2) ?> m</strong></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
