<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}

$nivel = "../"; // Ajusta según la ubicación de plantilla.php
require_once($nivel . "componentes/plantilla.php");

$rol = $_SESSION['rol'] ?? 'usuario';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema Contable</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .contenido-dashboard {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 30px; /* Más espacio arriba y abajo */
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); /* Tarjetas más anchas */
            gap: 35px; /* Más espacio entre tarjetas */
            max-width: 1100px;
            width: 100%;
        }

        .card {
            border-radius: 18px; /* Bordes más redondeados */
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
            padding: 50px 20px; /* Más espacio interno */
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.2);
        }
        .card i {
            width: 80px; /* Iconos más grandes */
            height: 80px;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .card:hover i {
            transform: scale(1.2) rotate(8deg);
        }
        .card-title {
            font-size: 22px; /* Texto más grande */
            font-weight: bold;
        }

        /* Colores para las tarjetas */
        .tablas { background: #1abc9c; }
        .inventario { background: #3498db; }
        .facturacion { background: #9b59b6; }
        .impuestos { background: #e67e22; }
        .nomina { background: #f39c12; }
        .configuracion { background: #34495e; }

        /* Botón cerrar sesión */
        .cerrar-sesion {
            position: absolute;
            top: 15px;
            right: 20px;
            background: #e74c3c;
            color: white;
            padding: 10px 16px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 15px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
        }

        /* Responsive para móviles */
        @media (max-width: 600px) {
            .card {
                padding: 30px 15px;
            }
            .card i {
                width: 60px;
                height: 60px;
            }
            .card-title {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <a href="../login/logout.php" class="cerrar-sesion">🔒 Cerrar sesión</a>

    <div class="contenido-dashboard">
        <div class="dashboard">

            <?php if ($rol === 'ADMINISTRADOR'): ?>
                <div class="card tablas" onclick="location.href='../tablas/index.php'">
                    <i data-lucide="database"></i>
                    <div class="card-title">Tablas</div>
                </div>
            <?php endif; ?>

            <div class="card inventario" onclick="location.href='../inventario/index.php'">
                <i data-lucide="archive"></i>
                <div class="card-title">Inventario</div>
            </div>

            <?php if ($rol === 'ADMINISTRADOR'): ?>
                <div class="card facturacion" onclick="location.href='../facturacion/index.php'">
                    <i data-lucide="file-text"></i>
                    <div class="card-title">Facturación</div>
                </div>
                <div class="card impuestos" onclick="location.href='../impuestos/index.php'">
                    <i data-lucide="dollar-sign"></i>
                    <div class="card-title">Impuestos</div>
                </div>
                <div class="card nomina" onclick="location.href='../nomina/index.php'">
                    <i data-lucide="users"></i>
                    <div class="card-title">Nómina</div>
                </div>
                <div class="card configuracion" onclick="location.href='../configuracion/index.php'">
                    <i data-lucide="settings"></i>
                    <div class="card-title">Configuración</div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
