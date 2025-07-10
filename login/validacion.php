<?php
$nivel = '../'; // Ajusta según tu estructura
session_start();
require_once("{$nivel}conexion/conexion.php");

// Validar si se enviaron usuario y clave
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($usuario && $clave) {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? AND estado = 'activo'");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($clave, $user['clave'])) {
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol']; // Guardamos el rol para control de acceso
            header("Location: {$nivel}inicio/inicio.php");
            exit();
        }
    }

    // Si llega aquí, hubo error
    echo "<div style='margin:100px auto; max-width:400px; font-family:Segoe UI; text-align:center; background:#fff; padding:25px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,0.1);'>
            <h3 style='color:red;'>❌ Usuario o contraseña incorrectos</h3>
            <p><a href='formulario.php' style='color:#3498db;'>🔁 Intentar de nuevo</a></p>
          </div>";
} else {
    // Acceso directo sin POST
    header("Location: formulario.php");
    exit();
}
