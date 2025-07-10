<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}

require_once('../componentes/plantilla.php');
require_once('../conexion/conexion.php');
require_once('../componentes/plantilla_nomina.php');

// Leer indicadores desde JSON
$indicadores = [];
$configPath = __DIR__ . '/../configuracion/indicadores_laborales.json';
if (file_exists($configPath)) {
    $json = file_get_contents($configPath);
    $indicadores = json_decode($json, true);
} else {
    $indicadores = [
        'salario_minimo' => 0,
        'auxilio_transporte' => 0,
        'seguridad_social' => 0,
        'prestaciones_sociales' => 0
    ];
}

// Consultas
$totalFijos = $conexion->query("SELECT COUNT(*) FROM listadoempleados WHERE tipo_contrato = 'Fijo'")->fetchColumn();
$totalOPS = $conexion->query("SELECT COUNT(*) FROM listadoempleados WHERE tipo_contrato = 'OPS'")->fetchColumn();
$totalProduccion = $conexion->query("SELECT COUNT(*) FROM listadoempleados WHERE area = 'Producción'")->fetchColumn();

// Nueva consulta: contar registros en cuentasbancarias
$totalCuentas = $conexion->query("SELECT COUNT(*) FROM cuentasbancarias")->fetchColumn();
?>

<style>
.dashboard {
    max-width: 1200px;
    margin: auto;
    padding: 40px 20px;
    font-family: 'Segoe UI', sans-serif;
}
.dashboard h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #2c3e50;
}
.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
}
.card {
    background: white;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.07);
    text-align: center;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-4px);
}
.card-icon {
    font-size: 36px;
    color: #3498db;
    margin-bottom: 10px;
}
.card-title {
    font-size: 16px;
    color: #2c3e50;
    margin-bottom: 5px;
}
.card-value {
    font-size: 22px;
    font-weight: bold;
    color: #1a73e8;
}
.card-value.green { color: #27ae60; }
.card-value.orange { color: #e67e22; }
.card-value.purple { color: #8e44ad; }
.card-value.gray { color: #7f8c8d; }

.edit-btn {
    display: inline-block;
    margin-top: 30px;
    background: #1a73e8;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
</style>

<div class="dashboard">
    <h2>📊 Panel de Control - Nómina</h2>

    <div class="card-grid">
        <div class="card">
            <div class="card-icon">👨‍💼</div>
            <div class="card-title">Empleados Directos</div>
            <div class="card-value"><?= $totalFijos ?></div>
        </div>

        <div class="card">
            <div class="card-icon">🛠️</div>
            <div class="card-title">Prestadores de Servicios</div>
            <div class="card-value"><?= $totalOPS ?></div>
        </div>

        <div class="card">
            <div class="card-icon">🏭</div>
            <div class="card-title">En Producción</div>
            <div class="card-value"><?= $totalProduccion ?></div>
        </div>

        <div class="card">
            <div class="card-icon">💰</div>
            <div class="card-title">Salario Mínimo</div>
            <div class="card-value green">$<?= number_format($indicadores['salario_minimo'], 0, ',', '.') ?></div>
        </div>

        <div class="card">
            <div class="card-icon">🚌</div>
            <div class="card-title">Auxilio Transporte</div>
            <div class="card-value green">$<?= number_format($indicadores['auxilio_transporte'], 0, ',', '.') ?></div>
        </div>

        <div class="card">
            <div class="card-icon">🛡️</div>
            <div class="card-title">Seguridad Social</div>
            <div class="card-value orange">$<?= number_format($indicadores['seguridad_social'], 0, ',', '.') ?></div>
        </div>

        <div class="card">
            <div class="card-icon">📦</div>
            <div class="card-title">Prestaciones Sociales</div>
            <div class="card-value purple">$<?= number_format($indicadores['prestaciones_sociales'], 0, ',', '.') ?></div>
        </div>

        <!-- ✅ NUEVA TARJETA: Cuentas Bancarias -->
        <a href="cuentasbancarias/index.php" style="text-decoration:none;">
            <div class="card">
                <div class="card-icon">🏦</div>
                <div class="card-title">Cuentas Bancarias</div>
                <div class="card-value gray"><?= $totalCuentas ?></div>
            </div>
        </a>
    </div>

    <div style="text-align:center;">
        <a href="../configuracion/indicadores.php" class="edit-btn">✏️ Editar Indicadores Laborales</a>
    </div>
</div>
