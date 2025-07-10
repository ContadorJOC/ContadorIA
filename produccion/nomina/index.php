<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}
$nivel = "../../"; // Ajusta según la ubicación de plantilla.php
require_once($nivel . "componentes/plantilla.php");
?>
<?php require_once("../submenuproduccion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nómina - Producción</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }
        .contenido-nomina {
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
        .liquidacion { background: #1abc9c; }
        .historico { background: #3498db; }
        .ahorro { background: #e67e22; }
        .procesos { background: #9b59b6; }
    </style>
</head>
<body>
    <div class="contenido-nomina">
        <div class="dashboard">
            <div class="card liquidacion" onclick="location.href='liquidacion/index.php'">
                <i data-lucide="file-text"></i>
                <div class="card-title">Liquidación</div>
            </div>
            <div class="card historico" onclick="location.href='historico/index.php'">
                <i data-lucide="clock"></i>
                <div class="card-title">Histórico de Producción</div>
            </div>
            <div class="card ahorro" onclick="location.href='ahorro/index.php'">
                <i data-lucide="piggy-bank"></i>
                <div class="card-title">Ahorro de Producción</div>
            </div>
            <div class="card procesos" onclick="location.href='procesos/index.php'">
                <i data-lucide="settings"></i>
                <div class="card-title">Procesos & Precios</div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
