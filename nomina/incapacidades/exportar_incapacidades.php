<?php
require_once('../../conexion/conexion.php');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="incapacidades.xls"');
header('Cache-Control: max-age=0');
$datos = $conexion->query("SELECT * FROM incapacidades")->fetchAll(PDO::FETCH_ASSOC);
echo "<table border='1'>";
echo "<tr>";
foreach (array_keys($datos[0]) as $columna) {
    echo "<th>" . htmlspecialchars($columna) . "</th>";
}
echo "</tr>";
foreach ($datos as $fila) {
    echo "<tr>";
    foreach ($fila as $valor) {
        echo "<td>" . htmlspecialchars($valor) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";
exit();