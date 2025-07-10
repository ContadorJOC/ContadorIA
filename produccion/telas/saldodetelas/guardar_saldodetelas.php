<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../../login/formulario.php");
    exit();
}

// ✅ Incluye la conexión directamente (sin autoload)
require_once("../../../conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 📥 Recibir datos del formulario
        $codigo = trim($_POST['codigo'] ?? '');
        $nombre = trim($_POST['nombre'] ?? '');
        $proveedor = trim($_POST['proveedor'] ?? '');
        $saldo_inicial = trim($_POST['saldo_inicial'] ?? '');
        $entradas = trim($_POST['entradas'] ?? '');
        $salidas = trim($_POST['salidas'] ?? '');
        $saldo = trim($_POST['saldo'] ?? '');

        // ✅ Validar que todos los campos tengan datos
        if ($codigo && $nombre && $proveedor && $saldo_inicial !== '' && $entradas !== '' && $salidas !== '' && $saldo !== '') {
            // 📌 Insertar en la base de datos
            $sql = "INSERT INTO saldodetelas (codigo, nombre, proveedor, saldo_inicial, entradas, salidas, saldo) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                $codigo, $nombre, $proveedor, $saldo_inicial, $entradas, $salidas, $saldo
            ]);

            // ✅ Redirigir al index con mensaje de éxito
            header("Location: index.php?msg=guardado");
            exit();
        } else {
            // ⚠️ Faltan campos
            echo "<div style='
                    max-width:600px;
                    margin:50px auto;
                    padding:20px;
                    background:#fff3cd;
                    color:#856404;
                    border:1px solid #ffeeba;
                    border-radius:8px;
                    text-align:center;
                    font-family:Segoe UI;
                '>
                ⚠️ <strong>Todos los campos son obligatorios.</strong>
                <br><br><a href='javascript:history.back()' style='color:#2980b9;'>⬅ Volver</a>
            </div>";
        }
    } catch (PDOException $e) {
        // ❌ Mostrar error en caso de fallo al guardar
        echo "<div style='
                max-width:600px;
                margin:50px auto;
                padding:20px;
                background:#ffe6e6;
                color:#c0392b;
                border:1px solid #e74c3c;
                border-radius:8px;
                text-align:center;
                font-family:Segoe UI;
            '>
            <strong>❌ Error al guardar:</strong><br>" . htmlspecialchars($e->getMessage()) . "
            <br><br><a href='javascript:history.back()' style='color:#2980b9;'>⬅ Volver</a>
        </div>";
    }
} else {
    // 🚫 Acceso directo no permitido
    header("Location: index.php");
    exit();
}
?>
