<?php
$nivel = '../../';
require_once("{$nivel}componentes/plantilla.php");
?>
<?php require_once("../submenuproduccion.php"); ?>
<!-- Submenú Telas -->
<div class="contenido-telas">
    <div class="dashboard">
        <div class="card saldo-telas" onclick="location.href='saldodetelas/index.php'">
            <i data-lucide="layers"></i>
            <div class="card-title">Saldo de Telas</div>
        </div>
        <div class="card inventario-telas" onclick="location.href='inventariodetelas/index.php'">
            <i data-lucide="package-search"></i>
            <div class="card-title">Inventario de Telas</div>
        </div>
        <div class="card compras" onclick="location.href='compradetelas/index.php'">
            <i data-lucide="shopping-cart"></i>
            <div class="card-title">Compras</div>
        </div>
        <div class="card entradas" onclick="location.href='entradadetelas/index.php'">
            <i data-lucide="arrow-down-circle"></i>
            <div class="card-title">Entrada de Telas</div>
        </div>
        <div class="card planillas" onclick="location.href='planillas/index.php'">
            <i data-lucide="file-text"></i>
            <div class="card-title">Planillas</div>
        </div>
    </div>
</div>

<!-- Estilos -->
<style>
.contenido-telas {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 100px);
    background-color: #f4f6f9;
    padding: 20px;
}
.dashboard {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    width: 100%;
    max-width: 1000px;
}
.dashboard .card {
    border-radius: 16px;
    padding: 30px 15px;
    text-align: center;
    color: #fff;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
}
.dashboard .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.2);
}
.dashboard .card i {
    font-size: 48px;
    margin-bottom: 10px;
}
.dashboard .card-title {
    font-size: 16px;
    font-weight: bold;
    margin-top: 5px;
}

/* Colores diferentes por tarjeta */
.saldo-telas {
    background: linear-gradient(135deg, #1abc9c, #16a085); /* Verde */
}
.inventario-telas {
    background: linear-gradient(135deg, #e67e22, #d35400); /* Naranja oscuro */
}
.compras {
    background: linear-gradient(135deg, #9b59b6, #8e44ad); /* Morado */
}
.entradas {
    background: linear-gradient(135deg, #3498db, #2980b9); /* Azul */
}
.planillas {
    background: linear-gradient(135deg, #f39c12, #f1c40f); /* Amarillo */
}
</style>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
