<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

$columnas = $conexion->query("PRAGMA table_info(existenciasgenerales)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM existenciasgenerales ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_existenciasgenerales.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<style>
    .tabla-container {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        font-family: 'Segoe UI', sans-serif;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 15px;
    }

    th, td {
        text-align: center;
        padding: 12px 8px;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f5f5f5;
        font-weight: 600;
    }

    tr:hover {
        background-color: #f9f9f9;
    }

    .acciones a {
        margin: 0 6px;
        text-decoration: none;
        font-size: 17px;
    }

    .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }

    .top-bar .izquierda {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .top-bar a {
        background-color: #3498db;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
    }

    .top-bar .derecha a {
        background-color: #2ecc71;
    }

    .top-bar input[type="search"] {
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
    }

    .pagination {
        text-align: center;
        margin-top: 15px;
    }

    .pagination button {
        padding: 6px 10px;
        margin: 0 4px;
        border: none;
        border-radius: 4px;
        background-color: #ddd;
        cursor: pointer;
    }

    .pagination button.active {
        background-color: #3498db;
        color: white;
    }

    .pagination button:hover {
        background-color: #2980b9;
        color: white;
    }
</style>

<div class="tabla-container">
    <div class="top-bar">
        <div class="izquierda">
            <a href="agregar_existenciasgenerales.php">➕ Agregar</a>
            <input type="search" placeholder="🔍 Buscar..." onkeyup="filtrarTabla(this)">
        </div>
        <div class="derecha">
            <a href="importar_existencias.php">📥 Importar Existencias</a>
        </div>
    </div>

    <h2 style='font-family:Segoe UI;'>📦 Existencias Generales</h2>

    <table id="tabla-existencias">
        <thead>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <?php if ($col['name'] != 'id'): ?>
                        <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila): ?>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <?php if ($col['name'] != 'id'): ?>
                            <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td class="acciones">
                        <a href='editar_existenciasgenerales.php?id=<?= $fila['id'] ?>'>✏️</a>
                        <a href='eliminar_existenciasgenerales.php?id=<?= $fila['id'] ?>' onclick="return confirm('¿Eliminar este registro?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination" id="paginacion"></div>
</div>

<script>
function filtrarTabla(input) {
    const texto = input.value.toLowerCase();
    const filas = document.querySelectorAll("#tabla-existencias tbody tr");
    filas.forEach(fila => {
        const mostrar = [...fila.cells].some(td => td.textContent.toLowerCase().includes(texto));
        fila.style.display = mostrar ? '' : 'none';
    });
}

// Paginación simple
const filas = Array.from(document.querySelectorAll("#tabla-existencias tbody tr"));
const filasPorPagina = 50;
let paginaActual = 1;

function mostrarPagina(pagina) {
    const inicio = (pagina - 1) * filasPorPagina;
    const fin = inicio + filasPorPagina;
    filas.forEach((fila, index) => {
        fila.style.display = (index >= inicio && index < fin) ? '' : 'none';
    });

    const paginacion = document.getElementById("paginacion");
    paginacion.innerHTML = '';
    const totalPaginas = Math.ceil(filas.length / filasPorPagina);
    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.classList.toggle("active", i === pagina);
        btn.onclick = () => {
            paginaActual = i;
            mostrarPagina(paginaActual);
        };
        paginacion.appendChild(btn);
    }
}

mostrarPagina(paginaActual);
</script>
