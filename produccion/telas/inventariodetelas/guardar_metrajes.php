<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

require_once(__DIR__ . "/../../../conexion/conexion.php"); // ✅ Ruta segura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $codigo_tela = isset($_POST['codigo_tela']) ? trim($_POST['codigo_tela']) : '';

        if (empty($codigo_tela)) {
            throw new Exception("❌ Código de tela no proporcionado.");
        }

        // 🔥 Obtener el número actual de rollo para esta tela
        $stmtRollo = $conexion->prepare("SELECT COALESCE(MAX(rollo), 0) FROM metrajes WHERE codigo_tela = ?");
        $stmtRollo->execute([$codigo_tela]);
        $rollo_actual = (int)$stmtRollo->fetchColumn();

        // Recorrer las filas enviadas
        $filas_guardadas = 0;
        foreach ($_POST['metros'] as $index => $metros) {
            $metros = trim($metros);
            $ancho  = trim($_POST['ancho'][$index] ?? '');

            // 👉 Validar que al menos metros y ancho no estén vacíos
            if ($metros !== '' && $ancho !== '') {
                $rollo_actual++; // Incrementar rollo para esta tela

                // ✅ Si cortado no viene, asumir 0
                $cortado = isset($_POST['cortado'][$index]) && $_POST['cortado'][$index] !== '' 
                    ? floatval($_POST['cortado'][$index]) 
                    : 0;

                // ✅ Calcula saldo automáticamente
                $saldo = floatval($metros) - $cortado;

                // Ejecutar inserción
                $stmt = $conexion->prepare("
                    INSERT INTO metrajes (codigo_tela, metros, ancho, cortado, saldo, rollo)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$codigo_tela, $metros, $ancho, $cortado, $saldo, $rollo_actual]);

                $filas_guardadas++;
            }
        }

        if ($filas_guardadas === 0) {
            throw new Exception("⚠️ No se guardaron registros porque todas las filas estaban vacías.");
        }

        // 🔄 Redirigir de vuelta al modal
        header("Location: /produccion/telas/inventariodetelas/metrajes_modal.php?codigo=" 
               . urlencode($codigo_tela) . "&msg=guardado");
        exit();

    } catch (Exception $e) {
        // Mostrar error en caso de fallo
        echo "<p style='color:red; font-family:Segoe UI;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<a href='/produccion/telas/inventariodetelas/metrajes_modal.php?codigo=" 
             . urlencode($codigo_tela) . "' style='color:#2980b9; text-decoration:underline;'>⬅️ Volver al modal</a>";
    }
}
?>
