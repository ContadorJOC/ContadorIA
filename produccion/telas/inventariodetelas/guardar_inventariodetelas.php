<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

require_once("../../../conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = trim($_POST['codigo'] ?? '');
    $nombre_de_tela = trim($_POST['nombre_de_tela'] ?? '');
    $saldo = trim($_POST['saldo'] ?? '');

    if ($codigo && $nombre_de_tela && $saldo !== '') {
        try {
            $stmt = $conexion->prepare("INSERT INTO inventariodetelas (codigo, nombre_de_tela, saldo) VALUES (?, ?, ?)");
            $stmt->execute([$codigo, $nombre_de_tela, $saldo]);

            // Redirigir con mensaje de éxito
            header("Location: index.php?exito=1");
            exit();
        } catch (PDOException $e) {
            echo "<div style='color:red; text-align:center; margin-top:50px;'>
                ❌ Error al guardar: " . htmlspecialchars($e->getMessage()) . "
            </div>";
        }
    } else {
        echo "<div style='color:red; text-align:center; margin-top:50px;'>
            ⚠️ Todos los campos son obligatorios.
        </div>";
    }
}
?>
