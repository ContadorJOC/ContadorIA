<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /produccion/login/formulario.php");
    exit();
}

require_once(__DIR__ . "/../../../conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_tela = $_POST['codigo_tela'] ?? '';
    $cortes = $_POST['cortado'] ?? [];

    try {
        foreach ($cortes as $id => $valorCorte) {
            $stmt = $conexion->prepare("UPDATE metrajes SET cortado = ? WHERE id = ?");
            $stmt->execute([trim($valorCorte), $id]);
        }
        header("Location: ../inventariodetelas/metrajes_modal.php?codigo=" . urlencode($codigo_tela) . "&msg=cortes_guardados");
        exit();
    } catch (Exception $e) {
        echo "<p style='color:red;'>❌ Error al guardar cortes: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
