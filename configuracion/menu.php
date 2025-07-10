<?php
// componentes/menu.php

// Inicia sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detecta ruta relativa para acceder correctamente a rutas desde cualquier nivel
$basePath = '';
$path = __DIR__;
$depth = substr_count(str_replace($_SERVER['DOCUMENT_ROOT'], '', $path), DIRECTORY_SEPARATOR);
for ($i = 0; $i < $depth; $i++) {
    $basePath .= '../';
}
?>

<style>
    .top-menu {
        background-color: #2c3e50;
        padding: 10px;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
    }
    .menu-item {
        background-color: #34495e;
        color: white;
        text-align: center;
        text-decoration: none;
        padding: 20px;
        border-radius: 12px;
        width: 120px;
        transition: transform 0.2s, background 0.3s;
        font-family: 'Segoe UI', sans-serif;
    }
    .menu-item:hover {
        transform: scale(1.05);
        background-color: #1abc9c;
    }
    .menu-item .icon {
        font-size: 28px;
        margin-bottom: 8px;
        display: block;
    }
</style>

<div class="top-menu">
    <a href="<?= $basePath ?>inicio/inicio.php" class="menu-item">
        <span class="icon">🏠</span>
        Inicio
    </a>
    <a href="<?= $basePath ?>tablas/index.php" class="menu-item">
        <span class="icon">📃</span>
        Tablas
    </a>
    <a href="<?= $basePath ?>inventario/index.php" class="menu-item">
        <span class="icon">📦</span>
        Inventario
    </a>
    <a href="<?= $basePath ?>facturacion/index.php" class="menu-item">
        <span class="icon">💳</span>
        Facturación
    </a>
    <a href="<?= $basePath ?>impuestos/index.php" class="menu-item">
        <span class="icon">💰</span>
        Impuestos
    </a>
    <a href="<?= $basePath ?>nomina/index.php" class="menu-item">
        <span class="icon">👥</span>
        Nómina
    </a>
    <a href="<?= $basePath ?>configuracion/index.php" class="menu-item">
        <span class="icon">⚙</span>
        Config.
    </a>
</div>