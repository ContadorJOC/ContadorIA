<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f6f9;
}
.contenedor-submenu {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 100px); /* Ajusta según menú superior */
    padding: 20px;
}
.submenu-inventario {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 30px;
    max-width: 1100px;
    width: 100%;
}
.submenu-inventario a {
    border-radius: 20px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-align: center;
    padding: 40px 20px;
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
    color: #fff;
    text-decoration: none;
    height: 120px;          /* Altura igual que producción */
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}
.submenu-inventario a:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}
.submenu-inventario i {
    display: block;
    width: 60px;
    height: 60px;
    margin-bottom: 15px;
}
.submenu-inventario span {
    font-size: 18px;
    font-weight: bold;
}

/* Colores distintos para cada tarjeta */
.card-1 { background: #1abc9c; }          /* Verde agua */
.card-2 { background: #3498db; }          /* Azul */
.card-3 { background: #9b59b6; }          /* Morado */
.card-4 { background: #e67e22; }          /* Naranja */
.card-5 { background: #f39c12; }          /* Amarillo mostaza */
</style>

<div class="contenedor-submenu">
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
