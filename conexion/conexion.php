<?php
// Detecta el número de niveles hacia arriba dependiendo del archivo que lo llama
$pathBase = realpath(__DIR__ . '/../basedatos/bacoa.sqlite');

try {
    $conexion = new PDO('sqlite:' . $pathBase);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
    exit();
}

