<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

$nivel = "../"; // Ajusta según la ubicación de plantilla.php
require_once($nivel . "componentes/plantilla.php");
?>
<?php require_once("submenuproduccion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Producción - Sistema Contable</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }
        .contenido-produccion {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px); /* resta altura del menú */
            padding: 20px;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            max-width: 1000px;
            width: 100%;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            text-align: center;
            padding: 30px 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            color: #fff;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
        }
        .card i {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
        }

        /* Colores distintos para cada tarjeta */
        .costeo { background: #1abc9c; }
        .nomina { background: #3498db; }
        .modulo { background: #9b59b6; }
        .talleres { background: #e67e22; }
        .compras-mp { background: #f39c12; }
        .compras-servicios { background: #16a085; }
        .telas { background: #e74c3c; }
        .informes { background: #2ecc71; }
        .herramientas { background: #34495e; }
        .lavanderias { background: #8e44ad; }
    </style>
</head>
<body>
    <div class="contenido-produccion">
        <div class="dashboard">
            <div class="card costeo" onclick="location.href='costeo/index.php'">
                <i data-lucide="layers"></i>
                <div class="card-title">Costeo</div>
            </div>
            <div class="card nomina" onclick="location.href='nomina/index.php'">
                <i data-lucide="users"></i>
                <div class="card-title">Producción Fabrica</div>
            </div>
            <div class="card modulo" onclick="location.href='#'">
                <i data-lucide="settings"></i>
                <div class="card-title">Producción Módulo</div>
            </div>
            <div class="card talleres" onclick="location.href='#'">
                <i data-lucide="factory"></i>
                <div class="card-title">Producción Talleres</div>
            </div>
            <div class="card compras-mp" onclick="location.href='#'">
                <i data-lucide="package"></i>
                <div class="card-title">Compras de MP</div>
            </div>
            <div class="card compras-servicios" onclick="location.href='#'">
                <i data-lucide="truck"></i>
                <div class="card-title">Compras de Servicios</div>
            </div>
            <div class="card telas" onclick="location.href='telas/index.php'">
                <i data-lucide="scissors"></i>
                <div class="card-title">Telas</div>
            </div>
            <div class="card informes" onclick="location.href='#'">
                <i data-lucide="bar-chart-3"></i>
                <div class="card-title">Informes</div>
            </div>
            <div class="card lavanderias" onclick="location.href='lavanderias/index.php'">
                <i data-lucide="washing-machine"></i>
                <div class="card-title">Lavanderías</div>
            </div>
            <div class="card herramientas" onclick="location.href='#'">
                <i data-lucide="wrench"></i>
                <div class="card-title">Herramientas</div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
