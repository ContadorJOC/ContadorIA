<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirigir si no hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}

$nivel = "../../../"; // Ajusta según la ubicación de plantilla.php
require_once($nivel . "componentes/plantilla.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Producción</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }
        .contenido-historico {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 100px);
            padding: 20px;
        }
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            max-width: 1200px;
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
        .corte { background: #1abc9c; }
        .despeluzado { background: #3498db; }
        .tachado { background: #e67e22; }
        .ojales { background: #9b59b6; }
        .empacado { background: #e74c3c; }
        .control-calidad { background: #2ecc71; }
        .despeluzado-crudo { background: #16a085; }
        .garras { background: #f39c12; }
        .cerrado { background: #34495e; }
        .empretinado { background: #8e44ad; }
        .presilla { background: #d35400; }
        .varios { background: #7f8c8d; }
    </style>
</head>
<body>
    <div class="contenido-historico">
        <div class="dashboard">
            <div class="card corte" onclick="location.href='corte/index.php'">
                <i data-lucide="scissors"></i>
                <div class="card-title">Corte</div>
            </div>
            <div class="card despeluzado" onclick="location.href='despeluzado/index.php'">
                <i data-lucide="wind"></i>
                <div class="card-title">Despeluzado</div>
            </div>
            <div class="card tachado" onclick="location.href='tachado/index.php'">
                <i data-lucide="x"></i>
                <div class="card-title">Tachado</div>
            </div>
            <div class="card ojales" onclick="location.href='ojales/index.php'">
                <i data-lucide="circle-dot"></i>
                <div class="card-title">Ojales</div>
            </div>
            <div class="card empacado" onclick="location.href='empacado/index.php'">
                <i data-lucide="package"></i>
                <div class="card-title">Empacado</div>
            </div>
            <div class="card control-calidad" onclick="location.href='historicocontroldecalidad/index.php'">
                <i data-lucide="check-circle"></i>
                <div class="card-title">Control de Calidad Inicial</div>
            </div>
            <div class="card despeluzado-crudo" onclick="location.href='despeluzado_crudo/index.php'">
                <i data-lucide="wind"></i>
                <div class="card-title">Despeluzado en Crudo</div>
            </div>
            <div class="card garras" onclick="location.href='garras/index.php'">
                <i data-lucide="move"></i>
                <div class="card-title">Garras & Sencamer</div>
            </div>
            <div class="card cerrado" onclick="location.href='cerrado/index.php'">
                <i data-lucide="lock"></i>
                <div class="card-title">Cerrado</div>
            </div>
            <div class="card empretinado" onclick="location.href='empretinado/index.php'">
                <i data-lucide="layers"></i>
                <div class="card-title">Empretinado</div>
            </div>
            <div class="card presilla" onclick="location.href='presilla/index.php'">
                <i data-lucide="anchor"></i>
                <div class="card-title">Presilla</div>
            </div>
            <div class="card varios" onclick="location.href='varios/index.php'">
                <i data-lucide="grid"></i>
                <div class="card-title">Varios</div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
