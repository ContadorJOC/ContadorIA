<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

$columnas = $conexion->query("PRAGMA table_info(examenes)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM examenes ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_examenes.json";
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
/* Botones de acciones */
.btn-accion {
    display: inline-flex;
    align-items: center;
    border: none;
    border-radius: 6px;
    padding: 6px 10px;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
}
.btn-editar {
    background-color: #3498db;
}
.btn-editar:hover {
    background-color: #2980b9;
}
.btn-eliminar {
    background-color: #e74c3c;
    margin-left: 5px;
}
.btn-eliminar:hover {
    background-color: #c0392b;
}
.btn-accion i {
    margin-right: 5px;
}
</style>

<h2 style="font-family:'Segoe UI'; font-size:24px; margin-bottom:15px;">📋 <?= strtoupper("examenes") ?></h2>

<a href="agregar_examenes.php" style="background:#27ae60;color:#fff;padding:8px 12px;border-radius:6px;text-decoration:none; display:inline-block; margin-bottom:15px;">➕ Agregar</a>

<input type="text" id="filtro" placeholder="🔍 Buscar examen...">

<div class="contenedor-tabla" style="margin-top:20px; overflow-x:auto;">
<table class="tabla-moderna" id="tablaExamenes">
    <thead>
        <tr>
            <?php foreach ($columnas as $col): ?>
                <th>
                    <?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?>
                </th>
            <?php endforeach; ?>
            <th>ACCIONES</th>
        </tr>
    </thead>
    <tbody id="cuerpoTabla">
        <?php foreach ($datos as $fila): ?>
        <tr>
            <?php foreach ($columnas as $col): ?>
                <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
            <?php endforeach; ?>
            <td>
                <a href="editar_examenes.php?id=<?= $fila['id'] ?>" class="btn-accion btn-editar">
                    ✏️ Editar
                </a>
                <a href="eliminar_examenes.php?id=<?= $fila['id'] ?>" class="btn-accion btn-eliminar" onclick="return confirm('¿Eliminar este registro?')">
                    🗑️ Eliminar
                </a>
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
const tabla = document.getElementById("tablaExamenes");
const cuerpo = document.getElementById("cuerpoTabla");
const buscador = document.getElementById("filtro");
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
