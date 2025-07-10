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

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo'])) {
    $archivoTmp = $_FILES['archivo']['tmp_name'];
    $nombreArchivo = $_FILES['archivo']['name'];

    if (is_uploaded_file($archivoTmp)) {
        $handle = fopen($archivoTmp, "r");
        if ($handle !== FALSE) {
            $conexion->beginTransaction();

            try {
                // Saltar la primera fila (encabezados)
                $primeraLinea = true;

                while (($datos = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    if ($primeraLinea) {
                        $primeraLinea = false;
                        continue;
                    }

                    // Si la línea está vacía, la saltamos
                    if (count($datos) < 11) {
                        continue;
                    }

                    // Asignar valores a las columnas
                    $fecha             = $datos[0];
                    $factura_de_compra = $datos[1];
                    $proveedor         = $datos[2];
                    $codigo            = $datos[3];
                    $referencia        = $datos[4];
                    $metros            = $datos[5];
                    $costo_sin_iva     = $datos[6];
                    $costo_con_iva     = $datos[7];
                    $base              = $datos[8];
                    $total_con_iva     = $datos[9];
                    $observaciones     = $datos[10];

                    // Insertar en la base de datos
                    $stmt = $conexion->prepare("INSERT INTO compradetelas 
                        (fecha, factura_de_compra, proveedor, codigo, referencia, metros, costo_sin_iva, costo_con_iva, base, total_con_iva, observaciones) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $fecha,
                        $factura_de_compra,
                        $proveedor,
                        $codigo,
                        $referencia,
                        $metros,
                        $costo_sin_iva,
                        $costo_con_iva,
                        $base,
                        $total_con_iva,
                        $observaciones
                    ]);
                }

                fclose($handle);
                $conexion->commit();
                $mensaje = "<div style='background:#2ecc71;color:#fff;padding:10px;border-radius:6px;'>✅ Datos importados correctamente desde <b>$nombreArchivo</b></div>";
            } catch (Exception $e) {
                $conexion->rollBack();
                $mensaje = "<div style='background:#e74c3c;color:#fff;padding:10px;border-radius:6px;'>❌ Error al importar: " . htmlspecialchars($e->getMessage()) . "</div>";
            }
        } else {
            $mensaje = "<div style='background:#e74c3c;color:#fff;padding:10px;border-radius:6px;'>❌ No se pudo abrir el archivo.</div>";
        }
    } else {
        $mensaje = "<div style='background:#e74c3c;color:#fff;padding:10px;border-radius:6px;'>❌ No se recibió ningún archivo.</div>";
    }
}
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;'>📤 Importar datos a COMPRAS DE TELAS</h2>

<a href="index.php" style="background:#3498db;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none;">🔙 Volver</a>

<div style="margin-top:20px; padding:20px; background:#f8f8f8; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
    <?= $mensaje ?>
    <form method="post" enctype="multipart/form-data">
        <label style="font-weight:bold;">📁 Selecciona un archivo CSV (separado por ;):</label><br><br>
        <input type="file" name="archivo" accept=".csv" required style="padding:8px;"><br><br>
        <button type="submit" style="background:#f39c12;color:#fff;padding:10px 16px;border:none;border-radius:6px;cursor:pointer;">📤 Importar</button>
    </form>
</div>
