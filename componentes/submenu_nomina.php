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
.submenu-nomina {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 10px 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    font-family: 'Segoe UI', sans-serif;
    font-weight: normal; /* evita negrita */
}
.submenu-nomina::-webkit-scrollbar {
    height: 6px;
}
.submenu-nomina::-webkit-scrollbar-thumb {
    background: #cccccc;
    border-radius: 3px;
}

.submenu-nomina a {
    flex: 0 0 auto;
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
    font-weight: normal; /* evita negrita */
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.2s ease;
}
.submenu-nomina a:hover {
    background: #3498db;
    color: #fff;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.submenu-nomina a i {
    font-size: 16px;
    font-weight: normal; /* íconos finos */
}
</style>

<div class="submenu-nomina">
    <a href="/nomina/listadoempleados/listadoempleados.php">
        <i data-lucide="users"></i> Empleados
    </a>
    <a href="/nomina/novedades/novedades.php">
        <i data-lucide="clipboard-list"></i> Novedades
    </a>
    <a href="/nomina/examenes/examenes.php">
        <i data-lucide="stethoscope"></i> Exámenes
    </a>
    <a href="/nomina/incapacidades/index.php">
        <i data-lucide="user-x"></i> Incapacidades
    </a>
    <a href="/nomina/empleados_fijos/empleados_fijos.php">
        <i data-lucide="briefcase"></i> Fijos
    </a>
    <a href="/nomina/prestadores/prestadores.php">
        <i data-lucide="wrench"></i> Prestadores
    </a>
    <a href="/nomina/mano_obra/mano_obra.php">
        <i data-lucide="hammer"></i> Mano de Obra
    </a>
    <a href="/nomina/liquidacion/liquidacion.php">
        <i data-lucide="dollar-sign"></i> Liquidación
    </a>
</div>

<script>
    lucide.createIcons();
</script>
