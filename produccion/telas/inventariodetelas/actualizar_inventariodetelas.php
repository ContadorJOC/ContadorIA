<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once(__DIR__ . "/../../../autoload.php"); // 📦 Carga el autoload global
require_project_file("plantilla.php");            // 📂 Plantilla

// Validar que la tabla existe
validarTabla($conexion, "inventariodetelas");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizar y validar entradas
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $codigo = isset($_POST['codigo']) ? trim($_POST['codigo']) : '';
    $nombre_de_tela = isset($_POST['nombre_de_tela']) ? trim($_POST['nombre_de_tela']) : '';
    $saldo = isset($_POST['saldo']) ? floatval($_POST['saldo']) : 0;

    if ($id <= 0 || $codigo === "" || $nombre_de_tela === "") {
        die("❌ Error: Todos los campos son obligatorios y el ID debe ser válido.");
    }

    try {
        // Preparar y ejecutar la actualización
        $sql = "UPDATE inventariodetelas 
                SET codigo = ?, nombre_de_tela = ?, saldo = ? 
                WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$codigo, $nombre_de_tela, $saldo, $id]);

        if ($stmt->rowCount() > 0) {
            // ✅ Registro actualizado correctamente
            header("Location: index.php?success=1");
            exit();
        } else {
            // ⚠️ No se modificó ningún registro (ID inexistente o mismos datos)
            header("Location: index.php?success=0&msg=sin-cambios");
            exit();
        }
    } catch (PDOException $e) {
        // 🛑 Mostrar error claro
        die("❌ Error al actualizar el registro: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("❌ Solicitud no válida. Debes enviar datos mediante POST.");
}
?>
