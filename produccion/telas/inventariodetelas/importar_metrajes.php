<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar código de tela
        $codigo_tela = isset($_POST['codigo_tela']) ? trim($_POST['codigo_tela']) : '';
        if (empty($codigo_tela)) {
            throw new Exception("❌ Debe ingresar el código de tela.");
        }

        // Validar archivo
        if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("❌ Error al subir el archivo.");
        }

        $archivo_tmp = $_FILES['archivo']['tmp_name'];
        $delimitador = ",";

        // Detectar delimitador automáticamente
        $handle_prueba = fopen($archivo_tmp, 'r');
        $linea_prueba = fgets($handle_prueba);
        fclose($handle_prueba);

        if (strpos($linea_prueba, ";") !== false) {
            $delimitador = ";";
        }

        $handle = fopen($archivo_tmp, "r");
        if (!$handle) {
            throw new Exception("❌ No se pudo abrir el archivo.");
        }

        $encabezados = fgetcsv($handle, 1000, $delimitador);
        if (!$encabezados || count($encabezados) < 2) {
            throw new Exception("❌ El archivo debe tener al menos las columnas 'metros' y 'ancho'.");
        }

        $index_metros = array_search('metros', array_map('strtolower', $encabezados));
        $index_ancho  = array_search('ancho', array_map('strtolower', $encabezados));

        if ($index_metros === false || $index_ancho === false) {
            throw new Exception("❌ El archivo debe incluir las columnas 'metros' y 'ancho'.");
        }

        // Obtener último número de rollo
        $rollo_actual = $conexion
            ->prepare("SELECT COALESCE(MAX(rollo),0) FROM metrajes WHERE codigo_tela = ?");
        $rollo_actual->execute([$codigo_tela]);
        $rollo = (int)$rollo_actual->fetchColumn();

        $filas_guardadas = 0;

        while (($fila = fgetcsv($handle, 1000, $delimitador)) !== false) {
            // Limpiar valores y reemplazar coma decimal por punto
            foreach ($fila as &$valor) {
                $valor = trim($valor);
                if (preg_match('/^\d+,\d+$/', $valor)) {
                    $valor = str_replace(',', '.', $valor); // convertir coma decimal a punto
                }
            }

            $metros = floatval($fila[$index_metros]);
            $ancho  = floatval($fila[$index_ancho]);

            if ($metros > 0 && $ancho > 0) {
                $rollo++;
                $saldo = $metros; // ✅ cortado = 0, saldo = metros
                $stmt = $conexion->prepare("
                    INSERT INTO metrajes (codigo_tela, metros, ancho, cortado, saldo, rollo)
                    VALUES (?, ?, ?, 0, ?, ?)
                ");
                $stmt->execute([$codigo_tela, $metros, $ancho, $saldo, $rollo]);
                $filas_guardadas++;
            }
        }

        fclose($handle);

        if ($filas_guardadas === 0) {
            throw new Exception("⚠️ No se importaron datos. Verifique el archivo.");
        }

        header("Location: metrajes_modal.php?codigo=" . urlencode($codigo_tela) . "&msg=importado");
        exit();

    } catch (Exception $e) {
        echo "<p style='color:red;font-family:Segoe UI;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='javascript:history.back()' style='color:#2980b9;'>⬅️ Volver</a>";
    }
}
?>
