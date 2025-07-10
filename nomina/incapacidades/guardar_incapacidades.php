<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once("../../conexion/conexion.php");

$tabla = "incapacidades";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Obtener columnas de la tabla
        $columnas = $conexion->query("PRAGMA table_info($tabla)")->fetchAll(PDO::FETCH_ASSOC);

        $campos = [];
        $valores = [];
        $placeholders = [];

        foreach ($columnas as $col) {
            $nombreCampo = $col['name'];
            if ($nombreCampo !== 'id' && isset($_POST[$nombreCampo])) {
                $campos[] = $nombreCampo;
                $valores[] = trim($_POST[$nombreCampo]);
                $placeholders[] = '?';
            }
        }

        $sql = "INSERT INTO $tabla (" . implode(',', $campos) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $conexion->prepare($sql);
        $stmt->execute($valores);

        $_SESSION['mensaje'] = "✅ Registro guardado correctamente.";
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "❌ Error al guardar: " . $e->getMessage();
    }

    header("Location: index.php");
    exit();
}
?>
