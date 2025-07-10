<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/formulario.php");
    exit();
}
include_once('../componentes/submenu_tablas.php');
require_once('../componentes/plantilla.php');
require_once('../conexion/conexion.php');

// Carpeta donde están los archivos PHP disponibles para vincular
$carpeta_php = '../nomina'; // CAMBIA según el módulo
$archivos_php = array_filter(scandir($carpeta_php), function($archivo) {
    return pathinfo($archivo, PATHINFO_EXTENSION) === 'php';
});

// 🗂 Buscar automáticamente las carpetas del proyecto para usarlas como módulos
$carpeta_raiz = realpath(__DIR__ . '/../'); // Subimos un nivel desde configuracion/
$modulos = array_filter(scandir($carpeta_raiz), function($archivo) use ($carpeta_raiz) {
    return is_dir($carpeta_raiz . '/' . $archivo) && !in_array($archivo, ['.', '..', 'componentes', 'conexion', 'login']);
});

$mensaje = "";
$nombre = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = strtolower(trim($_POST['nombre_tabla']));
    $campos = trim($_POST['campos']);
    $archivo_php = trim($_POST['vincular_php']);
    $modulo_destino = trim($_POST['modulo_destino']);

    if ($nombre && $campos) {
        $campos_sql = explode(',', $campos);
        $estructura = "";
        foreach ($campos_sql as $campo) {
            $campo_limpio = trim($campo);
            if ($campo_limpio != "") {
                $estructura .= "`$campo_limpio` TEXT, ";
            }
        }
        $estructura .= "`id` INTEGER PRIMARY KEY AUTOINCREMENT";

        $sql = "CREATE TABLE IF NOT EXISTS $nombre ($estructura)";
        try {
            $conexion->exec($sql);
            $mensaje .= "<br>✅ <strong>Módulo generado correctamente.</strong>";
            $mensaje .= "<br>📂 <a href='../$modulo_destino/$nombre/index.php' style='color:#2980b9; text-decoration:underline;'>Ir al módulo $nombre</a>";

            // Link directo para generar módulo
            $mensaje .= <<<HTML
                <br><form method="POST" action="generar_modulo.php" style="margin-top:10px;">
                    <input type="hidden" name="tabla" value="$nombre">
                    <input type="hidden" name="carpeta" value="$modulo_destino">
                    <button type="submit" style="background:#3498db; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer;">⚙️ Generar módulo para $nombre</button>
                </form>
            HTML;

            // Copiar archivos a módulo si se especificó
            if ($modulo_destino) {
                $origen = __DIR__ . "/$nombre";
                $destino = "$carpeta_raiz/$modulo_destino/$nombre";
                if (is_dir($origen)) {
                    if (!is_dir($destino)) mkdir($destino, 0777, true);
                    foreach (glob("$origen/*.php") as $archivo) {
                        copy($archivo, $destino . '/' . basename($archivo));
                    }
                    $mensaje .= "<br>📂 Copia realizada en <strong>$modulo_destino/$nombre</strong>";
                }
            }

            if ($archivo_php) {
                $ruta = $carpeta_php . "/" . $archivo_php;
                if (file_exists($ruta)) {
                    $mensaje .= "<br>🔗 <a href='$ruta' style='color:#2980b9;'>Ir al archivo $archivo_php</a>";
                } else {
                    $mensaje .= "<br>⚠️ El archivo <strong>$archivo_php</strong> no fue encontrado.";
                }
            }
        } catch (PDOException $e) {
            $mensaje = "❌ Error al crear la tabla: " . $e->getMessage();
        }
    } else {
        $mensaje = "❗ Debes completar todos los campos.";
    }
}
?>

<!-- CONTENIDO DE LA PÁGINA -->
<div class="main-content" style="padding: 30px; max-width: 700px; margin: auto;">
    <h2>➕ Crear Nueva Tabla</h2>

    <?php if ($mensaje): ?>
        <div style="margin-top: 20px; padding: 10px; border-radius: 6px; background: #eafaf1; color: #2e7d32; border: 1px solid #c8e6c9;">
            <?= $mensaje ?>
        </div>
    <?php endif; ?>

    <form method="POST" style="margin-top: 20px;">
        <label for="nombre_tabla">Nombre de la Tabla:</label>
        <input type="text" name="nombre_tabla" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">

        <label for="campos" style="margin-top:15px;">Campos (separados por coma):</label>
        <textarea name="campos" rows="4" placeholder="Ej: nombre, descripcion, valor" required style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;"></textarea>

        <label for="vincular_php" style="margin-top:15px;">¿En qué archivo PHP deseas usar esta tabla?</label>
        <select name="vincular_php" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
            <option value="">-- Selecciona un archivo --</option>
            <?php foreach ($archivos_php as $archivo): ?>
                <option value="<?= htmlspecialchars($archivo) ?>"><?= htmlspecialchars($archivo) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="modulo_destino" style="margin-top:15px;">¿En qué módulo deseas copiar los archivos PHP?</label>
        <select name="modulo_destino" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc;">
            <option value="">-- Selecciona un módulo --</option>
            <?php foreach ($modulos as $modulo): ?>
                <option value="<?= htmlspecialchars($modulo) ?>"><?= ucfirst($modulo) ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="margin-top:20px; width:100%; background-color:#27ae60; color:white; padding:12px; border:none; border-radius:8px; cursor:pointer;">Crear Tabla</button>
    </form>
</div>
