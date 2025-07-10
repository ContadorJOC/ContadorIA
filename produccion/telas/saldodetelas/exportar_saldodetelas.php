<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once("../../../conexion/conexion.php");

// ✅ Consultar datos: código, nombre, proveedor y saldo calculado
$consulta = $conexion->query("
    SELECT 
        s.codigo,
        e.referencia AS nombre,
        e.proveedor,
        ROUND(IFNULL(SUM(m.metros), 0), 2) AS saldo_total
    FROM saldodetelas s
    LEFT JOIN entradadetelas e ON s.codigo = e.codigo
    LEFT JOIN metrajes m ON s.codigo = m.codigo_tela
    GROUP BY s.codigo
    HAVING saldo_total > 0
    ORDER BY s.codigo ASC
");

// ✅ Encabezados HTTP para Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=SaldoDeTelas.xls");
header("Pragma: no-cache");
header("Expires: 0");

// ✅ Inicio de documento HTML (Excel lo interpreta como tabla)
echo '
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: Segoe UI, sans-serif;
            }
            table {
                border-collapse: collapse;
                width: 100%;
                font-size: 14px;
            }
            th {
                background-color: #3498db;
                color: #fff;
                font-weight: bold;
                text-align: center;
                padding: 8px;
                border: 1px solid #ddd;
            }
            td {
                border: 1px solid #ddd;
                padding: 6px;
                text-align: center;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <table>
            <tr>
                <th>CÓDIGO</th>
                <th>NOMBRE</th>
                <th>PROVEEDOR</th>
                <th>SALDO</th>
            </tr>
';

// ✅ Escribir filas
while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($fila['codigo']) . "</td>";
    echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
    echo "<td>" . htmlspecialchars($fila['proveedor']) . "</td>";
    echo "<td>" . number_format($fila['saldo_total'], 2, '.', '') . "</td>";
    echo "</tr>";
}

echo '
        </table>
    </body>
</html>';
exit();
?>
