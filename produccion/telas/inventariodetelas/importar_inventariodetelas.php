<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

require_once("../../../conexion/conexion.php");
require_once("../../../componentes/plantilla.php");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_csv'])) {
    $archivo = $_FILES['archivo_csv']['tmp_name'];
    $extension = pathinfo($_FILES['archivo_csv']['name'], PATHINFO_EXTENSION);

    if (strtolower($extension) === 'csv') {
        try {
            $handle = fopen($archivo, "r");
            if ($handle !== false) {
                $insertados = 0;

                // Obtener columnas de la tabla (excepto 'id')
                $columnas = $conexion->query("PRAGMA table_info(inventariodetelas)")->fetchAll(PDO::FETCH_ASSOC);
                $columnasTabla = array_filter(array_column($columnas, 'name'), fn($col) => $col !== 'id');
                $placeholders = implode(',', array_fill(0, count($columnasTabla), '?'));

                $sql = "INSERT INTO inventariodetelas (" . implode(',', $columnasTabla) . ") VALUES ($placeholders)";
                $stmt = $conexion->prepare($sql);

                $filaNum = 0;
                while (($linea = fgets($handle)) !== false) {
                    // Detectar delimitador automáticamente (coma o punto y coma)
                    $delimitador = (substr_count($linea, ";") > substr_count($linea, ",")) ? ";" : ",";
                    $fila = str_getcsv($linea, $delimitador);

                    // Saltar la primera fila si es encabezado
                    if ($filaNum === 0) {
                        $filaNum++;
                        continue;
                    }

                    // Ajustar la cantidad de columnas
                    $filaDatos = array_slice($fila, 0, count($columnasTabla));
                    $stmt->execute($filaDatos);
                    $insertados++;
                    $filaNum++;
                }
                fclose($handle);

                $mensaje = "✅ Se importaron <strong>$insertados</strong> registros correctamente.";
            } else {
                $mensaje = "❌ No se pudo abrir el archivo CSV.";
            }
        } catch (Exception $e) {
            $mensaje = "❌ Error al procesar el archivo: " . $e->getMessage();
        }
    } else {
        $mensaje = "❌ Solo se aceptan archivos CSV delimitados por comas o punto y coma.";
    }
}
?>

<div style="padding: 30px; max-width: 600px; margin: auto;">
    <h2 style="font-family:'Segoe UI'; font-size:22px; margin-bottom:20px;">📤 Importar Inventario de Telas (CSV)</h2>

    <?php if ($mensaje): ?>
        <div style="background: #f1f8e9; border: 1px solid #c5e1a5; padding: 10px; border-radius: 8px; margin-bottom: 15px; color: #33691e;">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="background:#fff; padding:20px; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
        <label for="archivo_csv" style="font-weight:bold;">📂 Selecciona un archivo CSV (UTF-8, delimitado por comas o punto y coma):</label>
        <input type="file" name="archivo_csv" accept=".csv" required style="display:block; margin-top:10px; margin-bottom:20px;">

        <button type="submit" style="background:#3498db; color:#fff; padding:10px 16px; border:none; border-radius:6px; cursor:pointer;">📤 Importar Archivo</button>
        <a href="index.php" style="margin-left:10px; text-decoration:none; color:#2980b9;">⬅️ Volver al Inventario</a>
    </form>
</div>

<!-- Estilos -->
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f7fa;
}
input[type="file"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 6px;
    width: 100%;
}
button:hover {
    background: #2980b9;
}
</style>
