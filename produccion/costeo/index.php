<?php
$nivel = '../../';
require_once("{$nivel}componentes/plantilla.php");
?>
<<?php require_once("../submenuproduccion.php"); ?>
<div class="contenido">
    <div class="card">
        <div class="encabezado-costeo">
            <h2>Costeo de Prendas Fabricadas</h2>
            <button class="btn-enviar">Enviar a Costo</button>
        </div>
        <div class="tabla-contenedor">
            <table class="tabla-costeo" id="tablaCosteo">
                <thead>
                    <tr>
                        <th colspan="6" class="titulo-seccion">Producto</th>
                    </tr>
                    <tr>
                        <th>Código</th>
                        <th style="width: 80px;">Cantidad</th>
                        <th style="width: 200px;">Costo</th>
                        <th>Promedio</th>
                        <th>Costura</th>
                        <th>Cant Lavand.</th>
                    </tr>
                    <tr>
                        <td><input type="text"></td>
                        <td><input type="number" id="cantidadProducto" value="0" onchange="calcularSumaTotales()"></td>
                        <td><input type="text" id="costoProducto" readonly></td>
                        <td><input type="number" value="0.00"></td>
                        <td><input type="number" value="0"></td>
                        <td><input type="number" value="0"></td>
                    </tr>
                </thead>
                <thead>
                    <tr>
                        <th colspan="6" class="titulo-seccion">Materia Prima y Mano de Obra</th>
                    </tr>
                    <tr>
                        <th>Materia Prima</th>
                        <th style="width: 300px;">Código Producto</th>
                        <th style="width: 80px;">Unidad</th>
                        <th>Total</th>
                        <th>CU</th>
                        <th>CT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $materias = [
                        "BANDERITA", "BOLSA", "BOTON", "BROCHES", "BOLSA", "BORDADO ESPECIAL",
                        "CADENA", "CARTON", "CAUCHO", "CIERRE", "CIERRE DE LUJO", "CINTA",
                        "COGOTERA", "CORDON", "DIJES", "GARRA", "HEBILLAS", "HILADILLO",
                        "HILAZA", "HILO", "INSTRUCTIVO LAVADO", "INTERLON", "LAVANDERIA",
                        "OJALETE", "PLACA", "PLAQUETA", "PUNTERAS", "SESGO", "TACHE",
                        "TALLA", "TELA", "PLOTER",
                        "MANO DE OBRA", "MANO DE OBRA", "MANO DE OBRA", "MANO DE OBRA", "MANO DE OBRA",
                        "MANO DE OBRA", "MANO DE OBRA", "MANO DE OBRA", "MANO DE OBRA",
                        "COSTOS INDIRECTOS", "BORDADO"
                    ];
                    foreach ($materias as $index => $item) {
                        echo "
                        <tr>
                            <td>{$item}</td>
                            <td><input type='text'></td>
                            <td><input type='number' class='unidad' value='0' onchange='calcularTotalFila(this)'></td>
                            <td class='total'>0,00</td>
                            <td><input type='number' class='cu' value='0' onchange='calcularCT(this)'></td>
                            <td><input type='text' class='ct' value='0,00' readonly></td>
                        </tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="fila-total">
                        <th colspan="3" style="text-align:right;">TOTAL</th>
                        <th id="totalGeneral">0,00</th>
                        <th id="totalCU">0,00</th>
                        <th id="totalCT">0,00</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
body {
    background-color: #f0f2f5;
    font-family: "Segoe UI", sans-serif;
}

.contenido {
    max-width: 1200px;
    margin: 30px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    padding: 25px;
}

.encabezado-costeo {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

h2 {
    color: #2d3436;
    font-size: 1.8em;
    margin: 0;
}

.btn-enviar {
    background: #0984e3;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.btn-enviar:hover {
    background: #74b9ff;
}

.tabla-contenedor {
    overflow-x: auto;
}

.tabla-costeo {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
}

.tabla-costeo th {
    background: #dfe6e9;
    color: #2d3436;
    font-weight: 600;
    text-align: center;
    padding: 10px;
    border-bottom: 2px solid #b2bec3;
}

.titulo-seccion {
    background-color: #74b9ff;
    color: #fff;
    font-size: 1.1em;
    letter-spacing: 0.5px;
}

.tabla-costeo td {
    text-align: center;
    padding: 8px;
    border-bottom: 1px solid #ecf0f1;
}

.fila-total {
    background: #dfe6e9;
    font-weight: bold;
}

.tabla-costeo input[type="text"],
.tabla-costeo input[type="number"] {
    width: 100%;
    padding: 5px 8px;
    border: 1px solid #dcdde1;
    border-radius: 6px;
    font-size: 14px;
    text-align: center;
}

.tabla-costeo input[type="text"]:focus,
.tabla-costeo input[type="number"]:focus {
    outline: none;
    border-color: #0984e3;
    box-shadow: 0 0 5px rgba(9,132,227,0.3);
}
</style>

<script>
function recalcularTotales() {
    document.querySelectorAll('.unidad').forEach(unidadInput => {
        calcularTotalFila(unidadInput);
    });
}

function calcularTotalFila(input) {
    const row = input.closest('tr');
    const cantidadProducto = parseFloat(document.getElementById('cantidadProducto').value.replace(/\./g, '').replace(',', '.')) || 0;
    const unidad = parseFloat(row.querySelector('.unidad').value.replace(/\./g, '').replace(',', '.')) || 0;
    const totalCell = row.querySelector('.total');

    const total = cantidadProducto * unidad;
    totalCell.textContent = formatNumber(total);
    calcularCT(row.querySelector('.cu'));
}

function calcularCT(input) {
    const row = input.closest('tr');
    const total = parseFloat(row.querySelector('.total').textContent.replace(/\./g, '').replace(',', '.')) || 0;
    const cu = parseFloat(input.value.replace(/\./g, '').replace(',', '.')) || 0;
    const ctInput = row.querySelector('.ct');

    const ctValue = total * cu;
    ctInput.value = formatNumber(ctValue);
    calcularSumaTotales();
}

function calcularSumaTotales() {
    let totalCT = 0;

    document.querySelectorAll('.ct').forEach(ctInput => {
        let raw = ctInput.value.replace(/\./g, '').replace(',', '.');
        totalCT += parseFloat(raw) || 0;
    });

    document.getElementById('totalCT').textContent = formatNumber(totalCT);

    const cantidad = parseFloat(document.getElementById('cantidadProducto').value.replace(/\./g, '').replace(',', '.')) || 1;
    const costoUnitario = totalCT / cantidad;
    document.getElementById('costoProducto').value = formatNumber(costoUnitario);
}

function formatNumber(value) {
    return value.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>
