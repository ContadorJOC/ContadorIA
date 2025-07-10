<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
?>

<style>
.submenu-produccion {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
    padding: 10px 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    font-family: 'Segoe UI', sans-serif;
    box-sizing: border-box;
    position: relative;
}

.submenu-produccion > .menu-item {
    position: relative;
}

.submenu-produccion a {
    display: flex;
    align-items: center;
    gap: 6px;
    background: #ffffff;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    padding: 8px 12px;
    text-decoration: none;
    color: #333333;
    font-size: 14px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
}
.submenu-produccion a:hover {
    background: #3498db;
    color: #fff;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.submenu-produccion a i {
    font-size: 16px;
}

.submenu-produccion .dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    flex-direction: column;
    background: #ffffff;
    border: 1px solid #dcdcdc;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    min-width: 220px;
    z-index: 100;
}

.submenu-produccion .dropdown a {
    padding: 8px 10px;
    font-size: 13px;
    border: none;
    border-bottom: 1px solid #f0f0f0;
    box-shadow: none;
}
.submenu-produccion .dropdown a:hover {
    background: #3498db;
    color: #fff;
}

.submenu-produccion .menu-item:hover .dropdown {
    display: flex;
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px);}
    to { opacity: 1; transform: translateY(0);}
}
</style>

<div class="submenu-produccion">
    <div class="menu-item">
        <a href="/produccion/costeo/index.php"><i data-lucide="layers"></i> Costeo</a>
    </div>
    <div class="menu-item">
        <a href="/produccion/nomina/index.php"><i data-lucide="users"></i> Fábrica</a>
    </div>
    <div class="menu-item">
        <a href="#"><i data-lucide="settings"></i> Módulo</a>
    </div>
    <div class="menu-item">
        <a href="#"><i data-lucide="factory"></i> Talleres</a>
    </div>
    <div class="menu-item">
        <a href="#"><i data-lucide="package"></i> Materia Prima</a>
    </div>

    <!-- Telas con submenú -->
    <div class="menu-item">
        <a href="/produccion/telas/index.php"><i data-lucide="scissors"></i> Telas ▾</a>
        <div class="dropdown">
            <a href="/produccion/telas/saldodetelas/index.php"><i data-lucide="layers"></i> Saldo de Telas</a>
            <a href="/produccion/telas/inventariodetelas/index.php"><i data-lucide="package-search"></i> Inventario de Telas</a>
            <a href="/produccion/telas/compradetelas/index.php"><i data-lucide="shopping-cart"></i> Compras</a>
            <a href="/produccion/telas/entradadetelas/index.php"><i data-lucide="arrow-down-circle"></i> Entradas</a>
            <a href="/produccion/telas/planillas/index.php"><i data-lucide="file-text"></i> Planillas</a>
        </div>
    </div>

    <!-- Lavanderías con submenú -->
    <div class="menu-item">
        <a href="/produccion/lavanderias/index.php"><i data-lucide="washing-machine"></i> Lavanderías ▾</a>
        <div class="dropdown">
            <a href="/produccion/lavanderias/compras/index.php"><i data-lucide="shopping-cart"></i> Compras</a>
            <a href="/produccion/lavanderias/abonos/index.php"><i data-lucide="credit-card"></i> Abonos</a>
            <a href="/produccion/lavanderias/estadodecuentas/index.php"><i data-lucide="file-text"></i> Estado de Cuentas</a>
        </div>
    </div>

    <div class="menu-item">
        <a href="#"><i data-lucide="bar-chart-3"></i> Informes</a>
    </div>
    <div class="menu-item">
        <a href="#"><i data-lucide="wrench"></i> Herramientas</a>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
