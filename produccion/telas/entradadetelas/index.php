<?php
session_start();

// Calcular ruta automáticamente
$nivel = str_repeat("../", substr_count(__DIR__, DIRECTORY_SEPARATOR) - substr_count(realpath($_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR));

if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}

require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");
require_once("../../submenuproduccion.php");
// Obtener columnas y datos
$columnas = $conexion->query("PRAGMA table_info(entradadetelas)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM entradadetelas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Etiquetas personalizadas
$json = "encabezados_entradadetelas.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<!-- Estilos modernos -->
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f7fa;
}

.btn-accion {
    display: inline-block;
    background: #3498db;
    color: #fff;
    padding: 8px 14px;
    margin-left: 8px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease;
}
.btn-accion:hover {
    background: #2980b9;
}
.btn-exportar {
    background: #2ecc71;
}
.btn-exportar:hover {
    background: #27ae60;
}
.btn-importar {
    background: #f39c12;
}
.btn-importar:hover {
    background: #e67e22;
}

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

.btn-icon {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    margin: 0 4px;
    transition: background 0.2s ease, transform 0.2s ease;
}
.btn-icon.editar {
    background: #27ae60;
}
.btn-icon.eliminar {
    background: #e74c3c;
}
.btn-icon:hover {
    transform: scale(1.1);
    opacity: 0.9;
}
</style>

<h2 style="font-family:'Segoe UI', sans-serif; font-size:22px; color:#34495e;">📋 <?= strtoupper("entradadetelas") ?></h2>

<!-- Botones de acción -->
<div style="margin-bottom:15px;">
    <a href="agregar_entradadetelas.php" class="btn-accion">➕ Agregar</a>
    <a href="exportar_entradadetelas.php" class="btn-accion btn-exportar">📥 Exportar Excel</a>
    <a href="importar_entradadetelas.php" class="btn-accion btn-importar">📤 Importar Excel</a>
</div>

<!-- Filtro de búsqueda -->
<input type="text" id="filtro" placeholder="🔎 Buscar en la tabla..." style="width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;margin-bottom:15px;">

<!-- Tabla con estilos modernos -->
<div class="contenedor-tabla" style="overflow-x:auto;">
    <table id="tabla" class="tabla-moderna">
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
                    <td>
                        <?php
                        // Mostrar campos monetarios con formato si existen
                        if (in_array($col['name'], ['costo_sin_iva', 'costo_con_iva', 'base', 'total_con_iva'])) {
                            $valor = str_replace(',', '.', $fila[$col['name']]);
                            $numero = is_numeric($valor) ? (float)$valor : 0;
                            echo "$ " . number_format($numero, 2, ',', '.');
                        } else {
                            echo htmlspecialchars($fila[$col['name']]);
                        }
                        ?>
                    </td>
                <?php endforeach; ?>
                <td>
                    <a href="editar_entradadetelas.php?id=<?= $fila['id'] ?>" class="btn-icon editar" title="Editar">✏️</a>
                    <a href="eliminar_entradadetelas.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este registro?')" class="btn-icon eliminar" title="Eliminar">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Controles de paginación -->
<div id="paginacion" style="margin-top:15px; text-align:center;"></div>

<script>
// Filtro de búsqueda
document.getElementById("filtro").addEventListener("keyup", function() {
    let filtro = this.value.toLowerCase();
    let filas = document.querySelectorAll("#tabla tbody tr");
    filas.forEach(function(fila) {
        let texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? "" : "none";
    });
});

// Paginación
const filasPorPagina = 100;
let tabla = document.getElementById("tabla");
let tbody = tabla.querySelector("tbody");
let filas = Array.from(tbody.querySelectorAll("tr"));
let paginacion = document.getElementById("paginacion");

function mostrarPagina(pagina) {
    let inicio = (pagina - 1) * filasPorPagina;
    let fin = inicio + filasPorPagina;
    filas.forEach((fila, i) => {
        fila.style.display = (i >= inicio && i < fin) ? "" : "none";
    });
}

function crearPaginacion() {
    paginacion.innerHTML = "";
    let totalPaginas = Math.ceil(filas.length / filasPorPagina);
    for (let i = 1; i <= totalPaginas; i++) {
        let btn = document.createElement("button");
        btn.textContent = i;
        btn.style.margin = "0 3px";
        btn.style.padding = "6px 10px";
        btn.style.border = "1px solid #3498db";
        btn.style.background = "#fff";
        btn.style.color = "#3498db";
        btn.style.borderRadius = "4px";
        btn.style.cursor = "pointer";
        btn.addEventListener("click", function() {
            mostrarPagina(i);
        });
        paginacion.appendChild(btn);
    }
    mostrarPagina(1);
}

crearPaginacion();
</script>
