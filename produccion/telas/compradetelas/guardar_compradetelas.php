<?php
session_start();

// Calcular ruta automáticamente
$nivel = str_repeat("../", substr_count(__DIR__, DIRECTORY_SEPARATOR) - substr_count(realpath($_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR));

if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}

require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

// ... resto del código ...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $columnas = $conexion->query("PRAGMA table_info(compradetelas)")->fetchAll(PDO::FETCH_ASSOC);
        $campos = [];
        $valores = [];
        $placeholders = [];

        foreach ($columnas as $col) {
            if ($col['name'] !== 'id' && isset($_POST[$col['name']])) {
                $campos[] = $col['name'];
                $valores[] = trim($_POST[$col['name']]);
                $placeholders[] = '?';
            }
        }

        $sql = "INSERT INTO compradetelas (" . implode(',', $campos) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $conexion->prepare($sql);
        $stmt->execute($valores);

        header("Location: index.php?msg=guardado");
        exit();
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}