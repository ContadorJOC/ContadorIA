<?php
$nivel = '../';
require_once("{$nivel}componentes/plantilla.php");
include("submenu_inventario.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Módulo de Inventario</title>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f4f6f9;
        }
        .contenido {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px); /* Resta altura del menú */
            padding: 20px;
        }
        .submenu-inventario {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            max-width: 1000px;
            width: 100%;
        }
        .submenu-inventario a {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            text-align: center;
            padding: 30px 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            color: #fff;
            text-decoration: none;
        }
        .submenu-inventario a:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        }
        .submenu-inventario i {
            display: block;
            width: 50px;
            height: 50px;
            margin: 0 auto 15px auto;
        }
        .submenu-inventario span {
            font-size: 16px;
            font-weight: bold;
        }

        /* Colores distintos para cada tarjeta */
        .card-1 { background: #1abc9c; }      /* Verde agua */
        .card-2 { background: #3498db; }      /* Azul */
        .card-3 { background: #9b59b6; }      /* Morado */
        .card-4 { background: #e67e22; }      /* Naranja */
        .card-5 { background: #f39c12; }      /* Amarillo mostaza */
    </style>
</head>
<body>
    <div class="contenido">
        <div class="submenu-inventario">
            <a href="existenciasgenerales/existenciasgenerales.php" class="card-1">
                <i data-lucide="boxes"></i>
                <span>Artículos</span>
            </a>
            <a href="notas.php" class="card-2">
                <i data-lucide="clipboard-list"></i>
                <span>Notas</span>
            </a>
            <a href="traslados.php" class="card-3">
                <i data-lucide="arrow-left-right"></i>
                <span>Traslados</span>
            </a>
            <a href="cambios.php" class="card-4">
                <i data-lucide="refresh-ccw"></i>
                <span>Cambios</span>
            </a>
            <a href="configuracion.php" class="card-5">
                <i data-lucide="settings"></i>
                <span>Configuración</span>
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
