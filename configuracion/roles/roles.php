<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

$msg = "";

// Crear rol
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $rol = trim($_POST['rol']);
    $descripcion = trim($_POST['descripcion']);
    $modulos = $_POST['modulos'] ?? [];
    $conexion->beginTransaction();
    try {
        $stmt = $conexion->prepare("INSERT INTO roles (rol, descripcion) VALUES (?, ?)");
        $stmt->execute([$rol, $descripcion]);
        $rol_id = $conexion->lastInsertId();

        // Insertar permisos
        foreach ($modulos as $modulo) {
            $conexion->prepare("INSERT INTO permisos (rol_id, modulo) VALUES (?, ?)")
                     ->execute([$rol_id, $modulo]);
        }
        $conexion->commit();
        $msg = "✅ Rol creado exitosamente.";
    } catch (Exception $e) {
        $conexion->rollBack();
        $msg = "❌ Error al crear el rol: " . $e->getMessage();
    }
}

// Editar rol
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
    $rol_id = intval($_POST['rol_id']);
    $rol = trim($_POST['rol']);
    $descripcion = trim($_POST['descripcion']);
    $modulos = $_POST['modulos'] ?? [];

    $conexion->beginTransaction();
    try {
        $conexion->prepare("UPDATE roles SET rol = ?, descripcion = ? WHERE id = ?")
                 ->execute([$rol, $descripcion, $rol_id]);

        $conexion->prepare("DELETE FROM permisos WHERE rol_id = ?")
                 ->execute([$rol_id]);

        foreach ($modulos as $modulo) {
            $conexion->prepare("INSERT INTO permisos (rol_id, modulo) VALUES (?, ?)")
                     ->execute([$rol_id, $modulo]);
        }
        $conexion->commit();
        $msg = "✅ Rol actualizado exitosamente.";
    } catch (Exception $e) {
        $conexion->rollBack();
        $msg = "❌ Error al actualizar el rol: " . $e->getMessage();
    }
}

// Eliminar rol
if (isset($_GET['eliminar'])) {
    $rol_id = intval($_GET['eliminar']);
    $conexion->beginTransaction();
    try {
        $conexion->prepare("DELETE FROM permisos WHERE rol_id = ?")
                 ->execute([$rol_id]);
        $conexion->prepare("DELETE FROM roles WHERE id = ?")
                 ->execute([$rol_id]);
        $conexion->commit();
        $msg = "✅ Rol eliminado exitosamente.";
    } catch (Exception $e) {
        $conexion->rollBack();
        $msg = "❌ Error al eliminar el rol: " . $e->getMessage();
    }
}

// Traer datos
$columnas = $conexion->query("PRAGMA table_info(roles)")->fetchAll(PDO::FETCH_ASSOC);
$datos = $conexion->query("SELECT * FROM roles ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$json = "encabezados_roles.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
$modulos = ['tablas', 'inventario', 'facturacion', 'impuestos', 'nomina', 'configuracion'];
?>

<style>
.header-actions {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 15px;
}
.header-actions a {
    background: #34495e;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    margin-left: 8px;
    font-size: 14px;
}
.header-actions a:hover {
    background: #2c3e50;
}
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
}
.modal-content {
    background: #fff;
    margin: 5% auto;
    padding: 20px;
    border-radius: 8px;
    max-width: 500px;
    position: relative;
}
.modal-content span {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    color: #888;
}
</style>

<h2 style="font-family:Segoe UI;">📋 <?= strtoupper("Roles") ?></h2>

<!-- Acceso rápido a Usuarios -->
<div class="header-actions">
    <a href="<?= $nivel ?>configuracion/usuarios/usuarios.php">👥 Usuarios</a>
</div>

<!-- Mensaje -->
<?php if ($msg): ?>
    <p style="color:<?= str_starts_with($msg, '✅') ? 'green' : 'red' ?>;"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<!-- Botón para abrir formulario -->
<button onclick="document.getElementById('modal-crear').style.display='block'" style="background:#3498db;color:white;padding:8px 12px;border-radius:6px;border:none;cursor:pointer;">➕ Crear Rol</button>

<!-- Tabla de roles -->
<div class="contenedor-tabla" style="margin-top:20px;">
    <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse; background:#fff; box-shadow:0 0 8px rgba(0,0,0,0.1);">
        <thead style="background:#3498db;color:white;">
        <tr>
            <?php foreach ($columnas as $col): ?>
                <th><?= htmlspecialchars($etiquetas[$col['name']] ?? strtoupper(str_replace('_', ' ', $col['name']))) ?></th>
            <?php endforeach; ?>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($datos as $fila): ?>
            <tr style="text-align:center;">
                <?php foreach ($columnas as $col): ?>
                    <td><?= htmlspecialchars($fila[$col['name']]) ?></td>
                <?php endforeach; ?>
                <td>
                    <button onclick="editarRol(<?= $fila['id'] ?>, '<?= htmlspecialchars($fila['rol']) ?>', '<?= htmlspecialchars($fila['descripcion']) ?>')" style="color:#2980b9;cursor:pointer;">✏️ Editar</button>
                    |
                    <button onclick="eliminarRol(<?= $fila['id'] ?>)" style="color:#c0392b;cursor:pointer;">🗑️ Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Crear Rol -->
<div id="modal-crear" class="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('modal-crear').style.display='none'">❌</span>
        <h3>➕ Crear Rol</h3>
        <form method="post">
            <input type="hidden" name="accion" value="crear">
            <label>Rol:</label><br>
            <input type="text" name="rol" required style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Descripción:</label><br>
            <input type="text" name="descripcion" style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Módulos permitidos:</label><br>
            <?php foreach ($modulos as $modulo): ?>
                <label><input type="checkbox" name="modulos[]" value="<?= $modulo ?>"> <?= ucfirst($modulo) ?></label><br>
            <?php endforeach; ?>
            <br>
            <button type="submit" style="background:#27ae60;color:white;padding:8px 12px;border:none;border-radius:5px;">💾 Guardar</button>
        </form>
    </div>
</div>

<!-- Modal Editar Rol -->
<div id="modal-editar" class="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('modal-editar').style.display='none'">❌</span>
        <h3>✏️ Editar Rol</h3>
        <form method="post">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="rol_id" id="editar-rol-id">
            <label>Rol:</label><br>
            <input type="text" name="rol" id="editar-rol" required style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Descripción:</label><br>
            <input type="text" name="descripcion" id="editar-descripcion" style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Módulos permitidos:</label><br>
            <?php foreach ($modulos as $modulo): ?>
                <label><input type="checkbox" name="modulos[]" value="<?= $modulo ?>" class="editar-modulo"> <?= ucfirst($modulo) ?></label><br>
            <?php endforeach; ?>
            <br>
            <button type="submit" style="background:#2980b9;color:white;padding:8px 12px;border:none;border-radius:5px;">💾 Actualizar</button>
        </form>
    </div>
</div>

<script>
function editarRol(id, rol, descripcion) {
    document.getElementById('editar-rol-id').value = id;
    document.getElementById('editar-rol').value = rol;
    document.getElementById('editar-descripcion').value = descripcion;
    // Desmarca todos los módulos
    document.querySelectorAll('.editar-modulo').forEach(el => el.checked = false);
    // Muestra modal
    document.getElementById('modal-editar').style.display = 'block';
}

function eliminarRol(id) {
    if (confirm("¿Está seguro de que desea eliminar este rol?")) {
        window.location.href = "?eliminar=" + id;
    }
}
</script>
