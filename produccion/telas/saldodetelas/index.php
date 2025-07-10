<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once("../../../componentes/plantilla.php");
require_once("../../../conexion/conexion.php");
require_once("../../submenuproduccion.php");

// ✅ Actualizar saldo en saldodetelas con suma de metros de metrajes
$conexion->exec("
    UPDATE saldodetelas
    SET saldo = (
        SELECT IFNULL(SUM(m.metros), 0)
        FROM metrajes m
        WHERE m.codigo_tela = saldodetelas.codigo
    )
");

// 🔄 Obtener estructura de columnas
$columnas = $conexion->query("PRAGMA table_info(saldodetelas)")->fetchAll(PDO::FETCH_ASSOC);

// 🔄 Traer datos con nombre y proveedor desde entradadetelas
$datos = $conexion->query("
    SELECT s.*, 
           e.referencia AS nombre, 
           e.proveedor
    FROM saldodetelas s
    LEFT JOIN entradadetelas e ON s.codigo = e.codigo
    WHERE s.saldo > 0
    ORDER BY s.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

// 📝 Cargar encabezados personalizados
$json = "encabezados_saldodetelas.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h2 style="font-family:'Segoe UI'; font-size:22px; margin-bottom:15px;">📋 <?= strtoupper("saldodetelas") ?></h2>
<div style="margin-bottom: 15px;">
    <a href="agregar_saldodetelas.php" class="btn-accion btn-agregar">➕ Agregar</a>
    <a href="exportar_saldodetelas.php" class="btn-accion btn-exportar">📥 Exportar Excel</a>
</div>

<input type="text" id="buscador" placeholder="🔎 Buscar en la tabla..." style="width: 100%; padding: 8px 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px;">

<div class="contenedor-tabla" style="overflow-x:auto;">
    <table class="tabla-moderna" id="tablaDatos">
        <thead>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <?php if (!in_array($col['name'], ['id', 'saldo_inicial', 'entradas', 'salidas', 'nombre', 'proveedor'])): ?>
                        <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
                        <?php if ($col['name'] == 'codigo'): ?>
                            <th>NOMBRE</th>
                            <th>PROVEEDOR</th>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $fila): ?>
            <tr>
                <?php foreach ($columnas as $col): ?>
                    <?php if (!in_array($col['name'], ['id', 'saldo_inicial', 'entradas', 'salidas', 'nombre', 'proveedor'])): ?>
                        <td>
                            <?php
                            if ($col['name'] == 'saldo') {
                                // ✅ Mostrar saldo con 2 decimales
                                echo number_format($fila[$col['name']], 2);
                            } else {
                                echo htmlspecialchars($fila[$col['name']] ?? '');
                            }
                            ?>
                        </td>
                        <?php if ($col['name'] == 'codigo'): ?>
                            <td><?= htmlspecialchars($fila['nombre'] ?? '') ?></td>
                            <td><?= htmlspecialchars($fila['proveedor'] ?? '') ?></td>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td>
                    <a href="editar_saldodetelas.php?id=<?= $fila['id'] ?>" class="btn-icon editar" title="Editar">
                        <i class="fas fa-pen"></i>
                    </a>
                    <a href="eliminar_saldodetelas.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este registro?')" class="btn-icon eliminar" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="paginacion" style="margin-top: 15px; text-align: center;"></div>

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
    margin-right: 8px;
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

#buscador {
    border: 1px solid #ccc;
    transition: border-color 0.3s ease;
}
#buscador:focus {
    outline: none;
    border-color: #2980b9;
    box-shadow: 0 0 5px rgba(41, 128, 185, 0.3);
}

#paginacion button {
    background: #3498db;
    color: #fff;
    border: none;
    padding: 6px 12px;
    margin: 2px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
}
#paginacion button:hover {
    background: #2980b9;
}
#paginacion button.active {
    background: #2ecc71;
}
</style>

<!-- Scripts buscador y paginación -->
<script>
const filasPorPagina = 50;
let paginaActual = 1;

function mostrarPagina(pagina) {
    const tabla = document.getElementById('tablaDatos');
    const filas = tabla.querySelectorAll('tbody tr');
    const totalPaginas = Math.ceil(filas.length / filasPorPagina);

    filas.forEach((fila, index) => {
        fila.style.display = (index >= (pagina - 1) * filasPorPagina && index < pagina * filasPorPagina) ? '' : 'none';
    });

    const paginacion = document.getElementById('paginacion');
    paginacion.innerHTML = '';
    for (let i = 1; i <= totalPaginas; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = (i === pagina) ? 'active' : '';
        btn.onclick = () => mostrarPagina(i);
        paginacion.appendChild(btn);
    }

    paginaActual = pagina;
}

document.getElementById('buscador').addEventListener('keyup', function () {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaDatos tbody tr');
    filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
    mostrarPagina(1);
});

document.addEventListener('DOMContentLoaded', () => {
    mostrarPagina(1);
});
</script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
