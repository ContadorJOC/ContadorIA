<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

$codigo_tela = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';
if (empty($codigo_tela)) {
    echo "❌ Código de tela no proporcionado.";
    exit();
}

// Encabezados para forzar la descarga como CSV
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="plantilla_metrajes.csv"');

// Abrir la salida como archivo
$output = fopen('php://output', 'w');

// Escribir BOM UTF-8 para Excel
fwrite($output, "\xEF\xBB\xBF");

// Escribir encabezados
fputcsv($output, ['codigo_tela', 'metros', 'ancho'], ';');

// Escribir 10 filas con código y campos vacíos
for ($i = 1; $i <= 10; $i++) {
    fputcsv($output, [$codigo_tela, '', ''], ';');
}

fclose($output);
exit();
?>
