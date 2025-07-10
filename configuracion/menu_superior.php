<!-- componentes/menu_superior.php -->
<style>
.menu-superior {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: #2c3e50;
    color: white;
    display: flex;
    padding: 10px 20px;
    gap: 15px;
    font-size: 14px;
    justify-content: center;
    z-index: 1000;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
.menu-superior a {
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 5px;
    transition: background 0.3s;
}
.menu-superior a:hover {
    background: #34495e;
}
</style>

<div class="menu-superior">
    <a href="/inicio/inicio.php">🏠 Inicio</a>
    <a href="/tablas/index.php">📊 Tablas</a>
    <a href="/inventario/index.php">📦 Inventario</a>
    <a href="/facturacion/index.php">🧾 Facturación</a>
    <a href="/impuestos/index.php">💰 Impuestos</a>
    <a href="/nomina/index.php">🧑‍💼 Nómina</a>
    <a href="/produccion/index.php">🏭 Producción</a>
    <a href="/configuracion/index.php">⚙️ Configuración</a>
</div>
