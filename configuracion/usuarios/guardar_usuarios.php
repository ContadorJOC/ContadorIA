<?php
$nivel = '../../';
require_once("{$nivel}conexion/conexion.php");

$columnas = $conexion->query("PRAGMA table_info(usuarios)")->fetchAll(PDO::FETCH_ASSOC);
$campos = [];
$valores = [];

foreach ($columnas as $col) {
    $nombre = $col['name'];
    if ($nombre === 'id') continue;

    $valor = $_POST[$nombre] ?? '';

    // Encriptar la clave si el campo se llama 'clave'
    if ($nombre === 'clave') {
        $valor = password_hash(trim($valor), PASSWORD_DEFAULT);
    } else {
        $valor = trim($valor);
    }

    $campos[] = $nombre;
    $valores[] = $valor;
}

$sql = "INSERT INTO usuarios (" . implode(",", $campos) . ") VALUES (" . rtrim(str_repeat("?,", count($valores)), ",") . ")";
$conexion->prepare($sql)->execute($valores);

header("Location: usuarios.php");
exit();
