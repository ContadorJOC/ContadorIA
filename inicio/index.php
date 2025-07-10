<?php 
include '../componentes/plantilla.php'; 
?>

<div class="main-content" style="max-width: 900px; margin: auto; padding-top: 120px; font-family: 'Segoe UI', sans-serif;">

    <h2 style="text-align: center; color: #2c3e50; margin-bottom: 20px;">📊 Módulo de Tablas</h2>

    <p style="text-align: center; max-width: 700px; margin: auto; margin-bottom: 40px; color: #555;">
        Este módulo está diseñado para administrar las tablas maestras del sistema contable. Aquí podrás gestionar listas como centros de costo, conceptos contables, cuentas, y más.
    </p>

    <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">

        <!-- Crear nueva tabla -->
        <a href="crear_tabla.php" style="
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 160px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: transform 0.2s;
        " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <div style="font-size: 42px;">➕</div>
            <div style="margin-top: 10px; font-size: 16px; font-weight: bold;">Crear Tabla</div>
        </a>

        <!-- Ver todas las tablas -->
        <a href="ver_tablas.php" style="
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 160px;
            background-color: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: transform 0.2s;
        " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <div style="font-size: 42px;">📋</div>
            <div style="margin-top: 10px; font-size: 16px; font-weight: bold;">Ver Tablas</div>
        </a>

        <!-- Configurar estructura -->
        <a href="configurar_tablas.php" style="
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 160px;
            background-color: #f39c12;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: transform 0.2s;
        " onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
            <div style="font-size: 42px;">🛠️</div>
            <div style="margin-top: 10px; font-size: 16px; font-weight: bold;">Configurar</div>
        </a>

    </div>

</div> <!-- Cierra main-content abierto por plantilla.php -->