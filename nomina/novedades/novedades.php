<?php
$nivel = '../../'; // Desde /nomina/novedades/
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

$columnas = $conexion->query("PRAGMA table_info(novedades)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM novedades ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_novedades.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
}
input[type="text"] {
    padding: 8px;
    width: 300px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 6px;
}
.tabla-moderna {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
.tabla-moderna thead {
    background-color: #3498db;
    color: white;
}
.tabla-moderna th,
.tabla-moderna td {
    padding: 12px 15px;
    text-align: left;
}
.tabla-moderna tbody tr:nth-child(even) {
    background-color: #f4f8fb;
}
.tabla-moderna tbody tr:hover {
    background-color: #e0f3ff;
}
.tabla-moderna th {
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}
.pagination {
    margin-top: 20px;
    text-align: center;
}
.pagination button {
    padding: 6px 12px;
    margin: 2px;
    border: none;
    border-radius: 4px;
    background-color: #3498db;
    color: white;
    cursor: pointer;
}
.pagination button.active {
    background-color: #2c3e50;
}
.pagination button:hover {
    background-color: #2980b9;
}
</style>

<h2 style="font-family:Segoe UI; font-size:28px; margin-bottom:20px;">📋 <?= strtoupper("novedades") ?></h2>

<div style="margin-bottom: 20px;">
    <a href='agregar_novedades.php' style='background:#27ae60; color:white; padding:10px 16px; border-radius:8px; text-decoration:none; font-family:Segoe UI;'>➕ Agregar Registro</a>
</div>

<input type="text" id="buscador" placeholder="🔍 Buscar registro...">

<div class='contenedor-tabla' style='overflow-x:auto;'>
<table class="tabla-moderna" id="tablaNovedades">
    <thead>
        <tr>
            <?php foreach ($columnas as $col): ?>
                <th>
                    <?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?>
                </th>
            <?php endforeach; ?>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="cuerpoTabla">
        <?php foreach ($datos as $fila): ?>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
                <?php endforeach; ?>
                <td>
                    <a href='editar_novedades.php?id=<?= $fila['id'] ?>' title='Editar' style='margin-right:8px; text-decoration:none; color:#2980b9;'>✏️</a>
                    <a href='eliminar_novedades.php?id=<?= $fila['id'] ?>' title='Eliminar' onclick="return confirm('¿Eliminar este registro?')" style='text-decoration:none; color:#c0392b;'>🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<div class="pagination" id="paginador"></div>
</div>

<script>
// PAGINACIÓN + BUSQUEDA
const filasPorPagina = 10;
const tabla = document.getElementById("tablaNovedades");
const cuerpo = document.getElementById("cuerpoTabla");
const buscador = document.getElementById("buscador");
let filas = Array.from(cuerpo.querySelectorAll("tr"));
let paginaActual = 1;

function mostrarTabla() {
    const filtro = buscador.value.toLowerCase();
    const filtradas = filas.filter(fila =>
        fila.innerText.toLowerCase().includes(filtro)
    );

    const totalPaginas = Math.ceil(filtradas.length / filasPorPagina);
    const inicio = (paginaActual - 1) * filasPorPagina;
    const fin = inicio + filasPorPagina;

    cuerpo.innerHTML = "";
    filtradas.slice(inicio, fin).forEach(fila => cuerpo.appendChild(fila));

    const paginador = document.getElementById("paginador");
    paginador.innerHTML = "";
    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = (i === paginaActual) ? "active" : "";
        btn.onclick = () => {
            paginaActual = i;
            mostrarTabla();
        };
        paginador.appendChild(btn);
    }
}

buscador.addEventListener("input", () => {
    paginaActual = 1;
    mostrarTabla();
});

window.onload = () => mostrarTabla();
</script>
