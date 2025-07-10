<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

// Ruta base absoluta desde la raíz del servidor
$urlBase = "/";
$rol = strtolower($_SESSION['rol'] ?? 'usuario'); // Rol actual normalizado
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Contable</title>
    <!-- Iconos modernos -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        nav {
            background-color: #2c3e50;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center; /* Centrado horizontal */
            padding: 10px 0;
            position: relative;
        }
        nav a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            font-size: 14px;
            transition: background 0.2s;
        }
        nav a:hover {
            background-color: #34495e;
            border-radius: 5px;
        }
        nav a svg {
            margin-right: 6px;
            width: 18px;
            height: 18px;
        }
        .logout-button {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            padding: 8px;
        }
        .logout-button:hover {
            background-color: #c0392b;
            border-radius: 50%;
        }
        .logout-button i {
            color: #ecf0f1;
            width: 20px;
            height: 20px;
        }
        .main-content {
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- Menú superior -->
<nav>
    <a href="<?= $urlBase ?>inicio/inicio.php"><i data-lucide="home"></i>Inicio</a>

    <?php if ($rol === 'administrador' || $rol === 'admin'): ?>
        <a href="<?= $urlBase ?>tablas/index.php"><i data-lucide="list"></i>Tablas</a>
        <a href="<?= $urlBase ?>inventario/index.php"><i data-lucide="package"></i>Inventario</a>
        <a href="<?= $urlBase ?>facturacion/index.php"><i data-lucide="file-text"></i>Facturación</a>
        <a href="<?= $urlBase ?>impuestos/index.php"><i data-lucide="percent"></i>Impuestos</a>
        <a href="<?= $urlBase ?>nomina/index.php"><i data-lucide="users"></i>Nómina</a>
        <a href="<?= $urlBase ?>produccion/index.php"><i data-lucide="factory"></i>Producción</a>
        <a href="<?= $urlBase ?>configuracion/index.php"><i data-lucide="settings"></i>Configuración</a>
    <?php elseif ($rol === 'usuario'): ?>
        <a href="<?= $urlBase ?>inventario/index.php"><i data-lucide="package"></i>Inventario</a>
    <?php endif; ?>

    <!-- Botón de cerrar sesión separado -->
    <a href="<?= $urlBase ?>login/logout.php" class="logout-button" title="Cerrar sesión">
        <i data-lucide="log-out"></i>
    </a>
</nav>

<script>
    lucide.createIcons();
</script>

<div class="main-content">
