<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}
require_once("../../conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $columnas = $conexion->query("PRAGMA table_info(cuentasbancarias)")->fetchAll(PDO::FETCH_ASSOC);
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

        $sql = "INSERT INTO cuentasbancarias (" . implode(',', $campos) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $conexion->prepare($sql);
        $stmt->execute($valores);

        header("Location: index.php?msg=guardado");
        exit();
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}