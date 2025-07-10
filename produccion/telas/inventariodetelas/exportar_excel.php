<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/conexion/conexion.php");

// ✅ Recibir código de tela y proveedor
$codigo_tela = isset($_GET['codigo']) ? $_GET['codigo'] : null;
$proveedor = isset($_GET['proveedor']) ? $_GET['proveedor'] : "";

if (!$codigo_tela) {
    die("❌ Código de tela no proporcionado.");
}

// ✅ Obtener datos de la tela
$tela = $conexion->prepare("SELECT * FROM inventariodetelas WHERE codigo = ?");
$tela->execute([$codigo_tela]);
$datos_tela = $tela->fetch(PDO::FETCH_ASSOC);
if (!$datos_tela) {
    die("❌ Tela no encontrada.");
}

// ✅ Obtener metrajes
$metrajes = $conexion->prepare("SELECT metros, ancho, cortado FROM metrajes WHERE codigo_tela = ? ORDER BY id ASC");
$metrajes->execute([$codigo_tela]);
$datos_metrajes = $metrajes->fetchAll(PDO::FETCH_ASSOC);

// ✅ Nombre del archivo
$filename = "Metrajes_" . strtoupper($codigo_tela) . ".xls";

// ✅ Encabezados para Excel
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ Totales
$total_metros = 0;
$total_cortado = 0;
$total_saldo = 0;

// ✅ Comenzar tabla
echo "
<table border='1' style='border-collapse:collapse; font-family:Arial, sans-serif;'>
    <tr>
        <th colspan='5' style='font-size:16px; text-transform:uppercase; text-align:left;'>
            <b>TELA:</b> " . htmlspecialchars($datos_tela['codigo']) . "
        </th>
    </tr>
    <tr>
        <th colspan='5' style='font-size:14px; text-transform:uppercase; text-align:left;'>
            <b>PROVEEDOR:</b> " . htmlspecialchars($proveedor) . "
        </th>
    </tr>
    <tr style='background-color:#4F81BD; color:#FFFFFF; font-weight:bold; text-align:center;'>
        <th>ROLLO</th>
        <th>METROS</th>
        <th>ANCHO</th>
        <th>CORTADO</th>
        <th>SALDO</th>
    </tr>
";

// ✅ Rellenar filas
$contador = 1;
foreach ($datos_metrajes as $fila) {
    $metros = (float)$fila['metros'];
    $cortado = (float)$fila['cortado'];
    $saldo = $metros - $cortado;

    $total_metros += $metros;
    $total_cortado += $cortado;
    $total_saldo += $saldo;

    echo "<tr style='text-align:center;'>";
    echo "<td>" . $contador++ . "</td>";
    echo "<td>" . number_format($metros, 2, '.', '') . "</td>";
    echo "<td>" . number_format((float)$fila['ancho'], 2, '.', '') . "</td>";
    echo "<td>" . number_format($cortado, 2, '.', '') . "</td>";
    echo "<td>" . number_format($saldo, 2, '.', '') . "</td>";
    echo "</tr>";
}

// ✅ Totales (sin texto extra)
echo "
<tr style='font-weight:bold; background-color:#D9E1F2; text-align:center;'>
    <td></td>
    <td>" . number_format($total_metros, 2, '.', '') . "</td>
    <td></td>
    <td>" . number_format($total_cortado, 2, '.', '') . "</td>
    <td>" . number_format($total_saldo, 2, '.', '') . "</td>
</tr>
";

echo "</table>";
?>
