<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
require_once('../componentes/plantilla.php');
?>

<h2 style="font-family:'Segoe UI', sans-serif; text-align:center; margin-top:20px;">⚙️ Configuración del Sistema</h2>
<p style="font-family:'Segoe UI', sans-serif; text-align:center;">Desde aquí puedes administrar herramientas internas del sistema contable.</p>

<div style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px; margin-top:30px;">

    <!-- Copiar Archivos -->
    <a href="copiar_archivos.php" style="background:#3498db; color:white; padding:20px; border-radius:12px; text-decoration:none; width:220px; display:flex; flex-direction:column; align-items:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        📂
        <span style="margin-top:10px; font-size:16px;">Copiar Archivos PHP</span>
    </a>

    <!-- Submenús -->
    <a href="gestionar_submenus.php" style="background:#27ae60; color:white; padding:20px; border-radius:12px; text-decoration:none; width:220px; display:flex; flex-direction:column; align-items:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        🧩
        <span style="margin-top:10px; font-size:16px;">Gestionar Submenús</span>
    </a>

    <!-- Indicadores Laborales -->
    <a href="indicadores.php" style="background:#8e44ad; color:white; padding:20px; border-radius:12px; text-decoration:none; width:220px; display:flex; flex-direction:column; align-items:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        📊
        <span style="margin-top:10px; font-size:16px;">Indicadores Laborales</span>
    </a>

    <!-- Gestión de Usuarios -->
    <a href="usuarios/usuarios.php" style="background:#e67e22; color:white; padding:20px; border-radius:12px; text-decoration:none; width:220px; display:flex; flex-direction:column; align-items:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        👥
        <span style="margin-top:10px; font-size:16px;">Gestionar Usuarios</span>
    </a>


    <!-- Roles -->
    <a href="roles/roles.php" style="background:#8e44ad; color:white; padding:20px; border-radius:12px; text-decoration:none; width:220px; display:flex; flex-direction:column; align-items:center; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
        📊
        <span style="margin-top:10px; font-size:16px;">Roles</span>
    </a>
    
</div>

