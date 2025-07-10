<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo Nómina</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }
        .contenido {
            padding: 20px;
        }
        a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: bold;
        }
        a:hover {
            color: #2980b9;
        }
    </style>
</head>
<body>

<?php include_once 'submenu_nomina.php'; ?>

<div class="contenido">