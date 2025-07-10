<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: /produccion/login/formulario.php"); // ✅ Ruta absoluta al login
    exit();
}

// ✅ Ajuste de rutas absolutas con __DIR__
require_once(__DIR__ . "/../../../componentes/plantilla.php");
require_once(__DIR__ . "/../../../conexion/conexion.php");
require_once(__DIR__ . "/../../submenuproduccion.php");

// Obtener las columnas y datos
$columnas = $conexion->query("PRAGMA table_info(inventariodetelas)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM inventariodetelas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

// Leer etiquetas desde JSON si existe
$json = __DIR__ . "/encabezados_inventariodetelas.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Telas</title>
    <!-- ✅ Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* 🎨 ESTILOS COMPLETOS */
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .spinner {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
        .btn-icon.ver {
            background: #8e44ad;
        }
        .btn-icon.editar {
            background: #27ae60;
        }
        .btn-icon.eliminar {
            background: #e74c3c;
        }
        .btn-icon.cortar {
            background: #f39c12; /* 🟠 Un naranja llamativo */
        }
        .btn-icon:hover {
            transform: scale(1.1);
            opacity: 0.9;
        }
        #paginacion button {
            background: #ecf0f1;
            border: none;
            margin: 2px;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        #paginacion button.active {
            background: #3498db;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Loader -->
    <div id="loader" style="position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.9); display:flex; justify-content:center; align-items:center; z-index:9999;">
        <div class="spinner"></div>
    </div>

    <!-- Título y botones -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; display:none;" id="contenido">
        <h2 style="font-size:22px;">📋 <?= strtoupper("Inventario de Telas") ?></h2>
        <div>
            <a href="agregar_inventariodetelas.php" class="btn-accion btn-agregar">➕ Agregar</a>
            <a href="exportar_inventariodetelas.php" class="btn-accion btn-exportar">📥 Exportar Excel</a>
            <a href="importar_inventariodetelas.php" class="btn-accion btn-importar">📤 Importar</a>
        </div>
    </div>

    <!-- Buscador -->
    <input type="text" id="buscador" placeholder="🔎 Buscar en la tabla..." style="width: 100%; padding: 8px 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; display:none;">

    <!-- Tabla -->
    <div class="contenedor-tabla" style="overflow-x:auto; display:none;" id="tabla-container">
        <?php
        // ✅ Consulta todos los saldos de una vez
        $saldos_stmt = $conexion->query("
            SELECT codigo_tela, SUM(saldo) as total_saldo
            FROM metrajes
            GROUP BY codigo_tela
        ");
        $saldos_array = [];
        foreach ($saldos_stmt as $row) {
            // Normalizar para comparación
            $codigo_normalizado = strtoupper(trim($row['codigo_tela']));
            $saldos_array[$codigo_normalizado] = $row['total_saldo'];
        }
        ?>
        <table class="tabla-moderna" id="tablaDatos">
            <thead>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <?php if ($col['name'] !== 'id'): ?>
                            <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos as $fila): ?>
                <?php
                    // ✅ Buscar el saldo total de la tela
                    $codigo_actual = strtoupper(trim($fila['codigo']));
                    $saldo_total = isset($saldos_array[$codigo_actual]) ? $saldos_array[$codigo_actual] : 0;
                    $saldo_total = number_format($saldo_total, 2, '.', '');
                ?>
                <tr>
                    <?php foreach ($columnas as $col): ?>
                        <?php if ($col['name'] !== 'id'): ?>
                            <?php if ($col['name'] === 'saldo'): ?>
                                <!-- ✅ Mostrar saldo total calculado -->
                                <td style="font-weight:bold; text-align:center; color: <?= $saldo_total > 0 ? '#27ae60' : '#e74c3c' ?>;">
                                    <?= $saldo_total ?>
                                </td>
                            <?php else: ?>
                                <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <td>
                        <!-- Botón ver metrajes -->
                        <a href="javascript:void(0);" class="btn-icon ver" onclick="abrirModalMetrajes('<?= $fila['codigo'] ?>')" title="Ver Metrajes">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Botón cortar -->
                        <a href="javascript:void(0);" class="btn-icon cortar" onclick="abrirModalCorte('<?= $fila['codigo'] ?>')" title="Cortar Rollo">
                            <i class="fas fa-scissors"></i>
                        </a>

                        <!-- Botón editar -->
                        <a href="editar_inventariodetelas.php?id=<?= $fila['id'] ?>" class="btn-icon editar" title="Editar">
                            <i class="fas fa-pen"></i>
                        </a>

                        <!-- Botón eliminar -->
                        <a href="eliminar_inventariodetelas.php?id=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este registro?')" class="btn-icon eliminar" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="paginacion" style="margin-top: 15px; text-align: center; display:none;"></div>
    <!-- Modal -->
    <div id="modalMetrajes" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:10000;">
        <div style="background:#fff; border-radius:10px; max-width:800px; width:95%; max-height:90%; overflow:auto; padding:20px; position:relative;">
            <span style="position:absolute; top:10px; right:15px; font-size:22px; cursor:pointer;" onclick="cerrarModalMetrajes()">✖</span>
            <div id="contenidoModalMetrajes"></div>
        </div>
    </div>

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

        document.getElementById('buscador').addEventListener('input', function () {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaDatos tbody tr');
            const paginacion = document.getElementById('paginacion');

            if (filtro.trim() !== "") {
                filas.forEach(fila => {
                    const texto = fila.textContent.toLowerCase();
                    fila.style.display = texto.includes(filtro) ? '' : 'none';
                });
                paginacion.style.display = "none";
            } else {
                filas.forEach(fila => fila.style.display = '');
                paginacion.style.display = "block";
                mostrarPagina(1);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('loader').style.display = 'none';
            document.getElementById('contenido').style.display = 'flex';
            document.getElementById('tabla-container').style.display = 'block';
            document.getElementById('buscador').style.display = 'block';
            document.getElementById('paginacion').style.display = 'block';
            mostrarPagina(1);
        });

        function abrirModalMetrajes(codigo) {
            fetch('metrajes_modal.php?codigo=' + encodeURIComponent(codigo))
                .then(res => res.text())
                .then(data => {
                    document.getElementById('contenidoModalMetrajes').innerHTML = data;
                    document.getElementById('modalMetrajes').style.display = 'flex';
                });
        }

        function cerrarModalMetrajes() {
            document.getElementById('modalMetrajes').style.display = 'none';
        }

        function abrirModalCorte(codigo) {
            fetch('metrajes_corte_modal.php?codigo=' + encodeURIComponent(codigo))
                .then(res => res.text())
                .then(data => {
                    document.getElementById('contenidoModalMetrajes').innerHTML = data;
                    document.getElementById('modalMetrajes').style.display = 'flex';

                    // 👇 Volver a enganchar funciones al cargar el modal dinámicamente
                    actualizarTotalCortado();
                    document.querySelectorAll('.cortado-input').forEach(input => {
                        input.addEventListener('input', actualizarTotalCortado);
                    });
                    document.querySelectorAll('.metros-clickable').forEach(td => {
                        td.addEventListener('click', function() {
                            const metros = parseFloat(td.textContent);
                            const inputCortado = td.parentElement.querySelector('.cortado-input');
                            if (!isNaN(metros) && inputCortado) {
                                inputCortado.value = metros.toFixed(2);
                                actualizarTotalCortado();
                            }
                        });
                    });
                });
        }

        // 🔥 Función para actualizar el total cortado
        function actualizarTotalCortado() {
            let total = 0;
            document.querySelectorAll('.cortado-input').forEach(input => {
                const valor = parseFloat(input.value);
                if (!isNaN(valor)) {
                    total += valor;
                }
            });
            const totalElement = document.getElementById('total-cortado');
            if (totalElement) totalElement.textContent = total.toFixed(2);
        }
    </script>
</body>
</html>
