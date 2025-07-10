<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once('../../componentes/plantilla.php');
require_once('../../conexion/conexion.php');

$columnas = $conexion->query("PRAGMA table_info(listadoempleados)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM listadoempleados ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$json = "encabezados_listadoempleados.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];

$columnas_visibles = [
    'tipo',
    'cedula',
    'nombre_completo',
    'fecha_ingreso',
    'fecha_de_contrato',
    'cargo',
    'tipo_contrato',
    'salud',
    'pension',
    'ccf',
    'arl',
    'area',
    'estado'
    // Nota: ocultamos visualmente la columna ID
];
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
#modalEmpleado {
    display:none; position:fixed; top:0; left:0; width:100%; height:100%;
    background:rgba(0,0,0,0.6); align-items:center; justify-content:center; z-index:1000;
}
#modalEmpleado > div {
    background:#f9f9f9; padding:25px; border-radius:10px;
    width:90%; max-width:600px; max-height:90vh; overflow:auto;
    box-shadow:0 0 20px rgba(0,0,0,0.3); position:relative;
}
</style>

<h2 style='font-family:Segoe UI;'>📋 LISTADO DE EMPLEADOS</h2>
<a href='agregar_listadoempleados.php' style='background:#27ae60;color:white;padding:8px 12px;border-radius:6px;text-decoration:none;'>➕ Agregar</a>

<input type="text" id="buscador" placeholder="🔍 Buscar empleado...">

<div class='contenedor-tabla' style='margin-top:20px; overflow-x:auto;'>
<table class="tabla-moderna" id="tablaEmpleados">
<thead>
<tr>
<?php foreach ($columnas as $col): ?>
    <?php if (in_array($col['name'], $columnas_visibles)): ?>
        <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
    <?php endif; ?>
<?php endforeach; ?>
<th>Acciones</th>
</tr>
</thead>
<tbody id="cuerpoTabla">
<?php foreach ($datos as $fila): ?>
<tr>
<?php foreach ($columnas as $col): ?>
    <?php if (in_array($col['name'], $columnas_visibles)): ?>
        <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
    <?php endif; ?>
<?php endforeach; ?>
    <td>
        <button onclick='verEmpleado(<?= json_encode($fila, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' style="background:#3498db;color:white;border:none;padding:5px 8px;border-radius:4px;">👁️ Ver</button>
        
        <a href='editar_listadoempleados.php?id=<?= $fila['id'] ?>' style="margin-left:5px;color:#2980b9;">✏️</a>
        
        <a href='eliminar_listadoempleados.php?id=<?= $fila['id'] ?>' style="margin-left:5px;color:#c0392b;" onclick="return confirm('¿Eliminar este registro?')">🗑️</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<div class="pagination" id="paginador"></div>
</div>

<!-- MODAL -->
<div id="modalEmpleado">
  <div>
    <span onclick="cerrarModal()" style="position:absolute; top:10px; right:15px; cursor:pointer; font-weight:bold;">❌</span>
    <h3 style="color:#2c3e50;">👤 Detalles del Empleado</h3>
    <div id="detalleEmpleado"></div>
  </div>
</div>

<script>
// MODAL
function verEmpleado(datos) {
    const contenedor = document.getElementById('detalleEmpleado');
    contenedor.innerHTML = '';
    for (const campo in datos) {
        let valor = datos[campo];
        let etiqueta = campo.replaceAll('_', ' ').toUpperCase();
        if (campo === 'estado') {
            const estado = valor.toLowerCase();
            const color = estado === 'activo' ? 'green' : 'red';
            valor = `<span style="color:${color}; font-weight:bold;">${valor}</span>`;
        }
        contenedor.innerHTML += `
            <div style="margin-bottom:12px; padding:8px; background:#fff; border-radius:6px; box-shadow:0 0 4px rgba(0,0,0,0.1);">
                <strong style="font-size:13px; color:#555;">${etiqueta}</strong>
                <div style="font-size:15px;">${valor}</div>
            </div>`;
    }
    document.getElementById('modalEmpleado').style.display = 'flex';
}
function cerrarModal() {
    document.getElementById('modalEmpleado').style.display = 'none';
}

// PAGINACIÓN + BUSQUEDA
const filasPorPagina = 10;
const tabla = document.getElementById("tablaEmpleados");
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
