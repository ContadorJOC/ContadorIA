<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
require_once('../componentes/plantilla.php');

// Función recursiva para listar archivos PHP dentro de /tablas y subcarpetas
function listarArchivosPHP($directorio) {
    $archivos = [];
    $items = scandir($directorio);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $rutaCompleta = $directorio . '/' . $item;
        if (is_dir($rutaCompleta)) {
            $archivos = array_merge($archivos, listarArchivosPHP($rutaCompleta));
        } elseif (is_file($rutaCompleta) && pathinfo($rutaCompleta, PATHINFO_EXTENSION) === 'php') {
            $archivos[] = $rutaCompleta;
        }
    }
    return $archivos;
}

// Carpetas destino disponibles (exceptuando '.' y '..')
$carpetas_disponibles = array_filter(glob('../*'), 'is_dir');
$mensaje = "";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archivos'], $_POST['destino'])) {
    $archivos = $_POST['archivos'];
    $destino = rtrim($_POST['destino'], '/') . '/';

    if (!is_dir($destino)) {
        $mensaje = "❌ Carpeta destino no válida.";
    } else {
        foreach ($archivos as $rutaRelativa) {
            $origen = "../tablas/" . $rutaRelativa;
            $nombreArchivo = basename($rutaRelativa);
            if (!file_exists($origen)) {
                $mensaje .= "❌ No se encontró el archivo: $rutaRelativa<br>";
                continue;
            }
            $copiado = copy($origen, $destino . $nombreArchivo);
            if (!$copiado) {
                $mensaje .= "❌ No se pudo copiar: $nombreArchivo<br>";
            }
        }
        if ($mensaje === "") {
            $mensaje = "✅ Archivos copiados exitosamente.";
        }
    }
}

// Listar archivos PHP desde /tablas incluyendo subcarpetas
$archivos_php = listarArchivosPHP('../tablas');
?>

<h2 style="font-family:'Segoe UI', sans-serif;">📄 Copiar Archivos PHP desde <code>/tablas</code></h2>

<?php if ($mensaje): ?>
    <div style="margin-top: 15px; padding: 10px; background: #eafaf1; color: #2e7d32; border: 1px solid #c8e6c9; border-radius: 6px;">
        <?= $mensaje ?>
    </div>
<?php endif; ?>

<form method="POST" style="margin-top: 20px;">
    <label for="archivos">Selecciona los archivos que deseas copiar:</label><br>
    <select name="archivos[]" multiple size="15" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
        <?php foreach ($archivos_php as $archivo): 
            $relativo = ltrim(str_replace('../tablas/', '', $archivo), '/');
        ?>
            <option value="<?= htmlspecialchars($relativo) ?>"><?= htmlspecialchars($relativo) ?></option>
        <?php endforeach; ?>
    </select>

    <label for="destino" style="margin-top: 15px; display: block;">Selecciona carpeta destino:</label>
    <select name="destino" style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;" required>
        <?php foreach ($carpetas_disponibles as $carpeta): ?>
            <option value="<?= $carpeta ?>"><?= basename($carpeta) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit" style="margin-top: 20px; background-color: #27ae60; color: white; padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer;">Copiar Archivos</button>
</form>