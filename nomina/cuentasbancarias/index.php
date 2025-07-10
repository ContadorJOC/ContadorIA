<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once("../../componentes/plantilla.php");
require_once("../../conexion/conexion.php");

$columnas = $conexion->query("PRAGMA table_info(cuentasbancarias)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM cuentasbancarias ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$json = "encabezados_cuentasbancarias.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h2 style="font-family:'Segoe UI'; font-size:22px;">📋 <?= strtoupper("cuentasbancarias") ?></h2>

<!-- Botones de acción -->
<a href="agregar_cuentasbancarias.php" class="btn-accion">➕ Agregar</a>
<a href="exportar_cuentasbancarias.php" class="btn-accion btn-exportar">📥 Exportar Excel</a>
<a href="importar_cuentasbancarias.php" class="btn-accion btn-importar">📤 Importar Excel</a>

<!-- Buscador -->
<input type="text" id="buscador" placeholder="🔍 Buscar..." style="margin-top:15px;padding:8px;width:300px;border:1px solid #ccc;border-radius:8px;">

<!-- Tabla -->
<div class="contenedor-tabla" style="margin-top:20px; overflow-x:auto;">
    <table class="tabla-moderna">
        <thead>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila): ?>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
                <?php endforeach; ?>
                <td>
                    <a href="editar_cuentasbancarias.php?id=<?= $fila['id'] ?>" class="btn-icon editar" title="Editar">✏️</a>
                    <a href="eliminar_cuentasbancarias.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este registro?')" class="btn-icon eliminar" title="Eliminar">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div id="paginacion" style="margin-top:15px;text-align:center;"></div>

<script>
// Buscador
const buscador = document.getElementById("buscador");
buscador.addEventListener("input", function() {
    const filtro = this.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filtro) ? "" : "none";
    });
});

// Paginación
const filas = document.querySelectorAll("tbody tr");
const filasPorPagina = 50;
let paginaActual = 1;
const totalPaginas = Math.ceil(filas.length / filasPorPagina);
const paginacion = document.getElementById("paginacion");

function mostrarPagina(pagina) {
    filas.forEach((fila, index) => {
        fila.style.display = (index >= (pagina - 1) * filasPorPagina && index < pagina * filasPorPagina) ? "" : "none";
    });
    paginacion.innerHTML = "";
    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement("button");
        btn.innerText = i;
        btn.style.margin = "0 5px";
        btn.style.padding = "6px 12px";
        btn.style.borderRadius = "6px";
        btn.style.border = "1px solid #ccc";
        btn.style.background = (i === pagina) ? "#3498db" : "#ecf6fd";
        btn.style.color = (i === pagina) ? "#fff" : "#333";
        btn.onclick = () => mostrarPagina(i);
        paginacion.appendChild(btn);
    }
}
mostrarPagina(paginaActual);
</script>

<style>
/* Botones principales */
.btn-accion {
    display: inline-block;
    background: #3498db;
    color: #fff;
    padding: 8px 14px;
    margin: 5px 5px 15px 0;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease;
}
.btn-accion:hover { background: #2980b9; }
.btn-exportar { background: #2ecc71; }
.btn-exportar:hover { background: #27ae60; }
.btn-importar { background: #f39c12; }
.btn-importar:hover { background: #e67e22; }

/* Tabla moderna */
.tabla-moderna {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.tabla-moderna thead {
    background: #2980b9;
    color: #fff;
    font-size: 15px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.tabla-moderna th, .tabla-moderna td {
    text-align: center;
    padding: 12px 10px;
    border-bottom: 1px solid #ecf0f1;
}
.tabla-moderna tbody tr:hover {
    background: #f1f6fa;
}

/* Botones de acción */
.btn-icon {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    margin: 0 3px;
    transition: background 0.2s ease, transform 0.2s ease;
}
.btn-icon.editar { background: #27ae60; }
.btn-icon.eliminar { background: #e74c3c; }
.btn-icon:hover {
    transform: scale(1.1);
    opacity: 0.9;
}
</style>
