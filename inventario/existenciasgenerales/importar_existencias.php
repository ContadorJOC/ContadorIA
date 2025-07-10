<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}

require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $archivo = $_FILES['archivo']['tmp_name'];

    if (($handle = fopen($archivo, "r")) !== FALSE) {
        // Detectar el separador automáticamente (; o ,)
        $linea = fgets($handle);
        $separador = (strpos($linea, ';') !== false) ? ';' : ',';
        rewind($handle); // Volver al inicio del archivo

        $conexion->beginTransaction();
        $primera = true;
        $importados = 0;
        $actualizados = 0;
        $omitidos = 0;

        try {
            while (($datos = fgetcsv($handle, 1000, $separador)) !== FALSE) {
                if ($primera) { $primera = false; continue; } // Saltar encabezado
                if (count($datos) < 2) continue;

                $codigo = isset($datos[0]) ? trim($datos[0]) : '';
                $cantidad = isset($datos[1]) ? (int) trim($datos[1]) : 0;

                if ($codigo === '') continue;

                // Verificar si ya existe el código
                $stmt = $conexion->prepare("SELECT cantidad FROM existenciasgenerales WHERE codigo = ?");
                $stmt->execute([$codigo]);
                $existente = $stmt->fetchColumn();

                if ($existente !== false) {
                    // Si la cantidad es diferente, actualizar
                    if ((int)$existente !== $cantidad) {
                        $update = $conexion->prepare("UPDATE existenciasgenerales SET cantidad = ? WHERE codigo = ?");
                        $update->execute([$cantidad, $codigo]);
                        $actualizados++;
                    } else {
                        $omitidos++;
                    }
                } else {
                    // Insertar nuevo
                    $insert = $conexion->prepare("INSERT INTO existenciasgenerales (codigo, cantidad) VALUES (?, ?)");
                    $insert->execute([$codigo, $cantidad]);
                    $importados++;
                }
            }

            fclose($handle);
            $conexion->commit();
            $msg = "✅ Importación exitosa. Nuevos: {$importados}, Actualizados: {$actualizados}, Omitidos: {$omitidos}.";
        } catch (Exception $e) {
            $conexion->rollBack();
            $msg = "❌ Error al importar: " . $e->getMessage();
        }
    } else {
        $msg = "❌ No se pudo abrir el archivo.";
    }
}
?>

<div style="max-width:500px; margin:50px auto; background:#fff; padding:25px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1); font-family:'Segoe UI', sans-serif;">
    <h2 style="text-align:center;">📥 Importar Existencias</h2>

    <?php if ($msg): ?>
        <p style="color:<?= str_starts_with($msg, '✅') ? 'green' : 'red' ?>;"><?= $msg ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label><strong>Selecciona un archivo:</strong></label><br><br>
        <input type="file" name="archivo" accept=".csv" required><br><br>
        <button type="submit" style="padding:10px 20px; background:#3498db; color:white; border:none; border-radius:6px; font-size:14px;">📤 Importar</button>
    </form>

    <p style="margin-top:15px; font-size:13px; line-height:1.4;">
        ⚠️ El archivo debe contener columnas: <b>codigo</b>, <b>cantidad</b>.<br>
        Puedes usar Excel para crearlo y luego "Guardar como" → CSV (delimitado por comas o punto y coma).
    </p>

    <a href="existenciasgenerales.php" style="display:inline-block; margin-top:20px; text-decoration:none; color:#3498db;">🔙 Volver</a>
</div>
