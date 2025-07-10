<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

// Obtener columnas de novedades
$columnas = $conexion->query("PRAGMA table_info(novedades)")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_novedades.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];

// Campos tipo checkbox
$casillas = ['EXTRA', 'LRN', 'INGRESO', 'RETIRO', 'DCTO', 'LICENCIA/INCAPACIDAD', 'LICENCIA_INCAPACIDAD', 'SUSPENCION'];
?>

<h2 style='font-family:Segoe UI; margin-bottom:20px;'>➕ Agregar a NOVEDADES</h2>

<form action='guardar_novedades.php' method='POST' style='max-width:800px; margin:auto; background:#fff; padding:30px; border-radius:14px; box-shadow:0 0 15px rgba(0,0,0,0.05); font-family:Segoe UI;'>
    <div style='display:flex; flex-wrap:wrap; gap:20px;'>
        <?php foreach ($columnas as $col): 
            $nombre = $col['name'];
            if ($nombre == 'id') continue;

            $etiqueta = $etiquetas[$nombre] ?? strtoupper(str_replace('_', ' ', $nombre));
            $esCasilla = in_array(strtoupper($nombre), $casillas);
        ?>
            <div style='flex:1 1 calc(50% - 20px); min-width:250px;'>
                <label style='font-weight:600;'><?= $etiqueta ?></label><br>

                <?php if (strtolower($nombre) == 'cedula'): ?>
                    <select name='cedula' class='select-empleado' style='width:100%;' required></select>

                <?php elseif (strtolower($nombre) == 'aplica' || strtolower($nombre) == 'pila?'): ?>
                    <select name='<?= $nombre ?>' style='width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;' required>
                        <option value="">Seleccione</option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>

                <?php elseif ($esCasilla): ?>
                    <input type='checkbox' name='<?= $nombre ?>' value='X' style='transform:scale(1.4); margin-top:6px;'>

                <?php else: ?>
                    <input type='text' name='<?= $nombre ?>' style='width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;'>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div style='text-align:center; margin-top:30px;'>
        <button type='submit' style='background:#27ae60; color:white; padding:12px 24px; border:none; border-radius:8px; font-size:16px;'>Guardar</button>
    </div>
</form>

<!-- Carga Select2 desde CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Inicializar Select2 con AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  $('.select-empleado').select2({
    placeholder: 'Escriba el nombre del empleado',
    ajax: {
      url: 'buscar_empleado.php',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return { term: params.term };
      },
      processResults: function (data) {
        return { results: data.results };
      },
      cache: true
    },
    minimumInputLength: 1
  });
});
</script>
