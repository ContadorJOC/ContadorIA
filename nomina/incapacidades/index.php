<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /login/formulario.php");
    exit();
}
require_once('../../componentes/plantilla.php');
require_once('../../conexion/conexion.php');

$columnas = $conexion->query("PRAGMA table_info(incapacidades)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM incapacidades ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_incapacidades.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f7fa;
}

h2 {
    font-size: 22px;
    color: #34495e;
}

.btn-accion {
    display: inline-block;
    background: #3498db;
    color: #fff;
    padding: 8px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease;
    margin-right: 6px;
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

#buscador {
    margin-top: 15px;
    padding: 8px;
    width: 300px;
    border: 1px solid #ccc;
    border-radius: 8px;
}

.tabla-moderna {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-top: 15px;
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

<h2>📋 <?= strtoupper("incapacidades") ?></h2>

<!-- Botones de acción -->
<a href="agregar_incapacidades.php" class="btn-accion">➕ Agregar</a>
<a href="exportar_incapacidades.php" class="btn-accion btn-exportar">📥 Exportar Excel</a>
<input type="text" id="buscador" placeholder="🔍 Buscar...">

<div style="overflow-x:auto;">
    <table class="tabla-moderna">
        <thead>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_',' ',$col['name']))) ?></th>
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
                    <a href="editar_incapacidades.php?id=<?= $fila['id'] ?>" class="btn-icon editar" title="Editar">✏️</a>
                    <a href="eliminar_incapacidades.php?id=<?= $fila['id'] ?>" class="btn-icon eliminar" title="Eliminar" onclick="return confirm('¿Eliminar este registro?')">🗑️</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Filtro de búsqueda
const buscador = document.getElementById("buscador");
buscador.addEventListener("input", function() {
    const texto = this.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach(tr => {
        tr.style.display = tr.innerText.toLowerCase().includes(texto) ? "" : "none";
    });
});
</script>
