<?php
require_once('../../conexion/conexion.php');

$id = $_GET['id'] ?? null;

if ($id) {
    // 1. Eliminar el empleado
    $conexion->prepare("DELETE FROM listadoempleados WHERE id = ?")->execute([$id]);

    // 2. Reordenar IDs
    $conexion->beginTransaction();
    $conexion->exec("CREATE TEMPORARY TABLE temp_empleados AS SELECT * FROM listadoempleados ORDER BY id ASC");
    $conexion->exec("DELETE FROM listadoempleados");
    $conexion->exec("DELETE FROM sqlite_sequence WHERE name='listadoempleados'"); // Reset AUTOINCREMENT
    $stmt = $conexion->query("SELECT * FROM temp_empleados");
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($empleados as $empleado) {
        $campos = array_keys($empleado);
        unset($campos[array_search('id', $campos)]); // quitar id

        $nombres = implode(", ", $campos);
        $marcadores = implode(", :", $campos);
        $valores = [];

        foreach ($campos as $campo) {
            $valores[":$campo"] = $empleado[$campo];
        }

        $sql = "INSERT INTO listadoempleados ($nombres) VALUES (:$marcadores)";
        $conexion->prepare($sql)->execute($valores);
    }

    $conexion->commit();
}

header("Location: listadoempleados.php");
exit();
