<?php
session_start();

// Calcular ruta automáticamente
$nivel = str_repeat("../", substr_count(__DIR__, DIRECTORY_SEPARATOR) - substr_count(realpath($_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR));

if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}

require_once("{$nivel}conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['archivo_csv'])) {
    $archivo = $_FILES['archivo_csv']['tmp_name'];

    if (($gestor = fopen($archivo, "r")) !== FALSE) {
        $conexion->beginTransaction();

        try {
            $fila = 0;
            while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                // Saltar la primera fila si es encabezado
                if ($fila === 0) {
                    $fila++;
                    continue;
                }

                // Preparar inserción
                $stmt = $conexion->prepare("INSERT INTO entradadetelas 
                    (codigo, referencia, proveedor, cantidad, fecha) 
                    VALUES (?, ?, ?, ?, ?)");

                // Limpiar valores
                $datos = array_map('trim', $datos);

                // Ejecutar inserción
                $stmt->execute([
                    $datos[0], // codigo
                    $datos[1], // referencia
                    $datos[2], // proveedor
                    $datos[3], // cantidad
                    $datos[4]  // fecha
                ]);

                $fila++;
            }

            $conexion->commit();
            fclose($gestor);
            $mensaje = "✅ Archivo importado correctamente. Se insertaron " . ($fila - 1) . " registros.";
        } catch (Exception $e) {
            $conexion->rollBack();
            $mensaje = "❌ Error al importar: " . $e->getMessage();
        }
    } else {
        $mensaje = "❌ No se pudo abrir el archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>📤 Importar Datos - Entrada de Telas</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        h2 {
            color: #34495e;
            margin-bottom: 20px;
        }
        input[type="file"] {
            margin: 15px 0;
        }
        button {
            background: #3498db;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
        }
        button:hover {
            background: #2980b9;
        }
        .mensaje {
            margin-top: 15px;
            color: #2c3e50;
            font-size: 14px;
        }
        .btn-volver {
            display: inline-block;
            margin-top: 15px;
            background: #2ecc71;
            color: #fff;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
        }
        .btn-volver:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📤 Importar Datos<br>Entrada de Telas</h2>
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="archivo_csv">Selecciona un archivo CSV:</label><br>
            <input type="file" name="archivo_csv" id="archivo_csv" accept=".csv" required><br>
            <button type="submit">📥 Importar</button>
        </form>
        <a href="index.php" class="btn-volver">🔙 Volver</a>
    </div>
</body>
</html>
