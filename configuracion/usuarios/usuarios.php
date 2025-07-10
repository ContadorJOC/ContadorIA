<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}componentes/plantilla.php");
require_once("{$nivel}conexion/conexion.php");

// Mensaje de estado
$msg = "";

// CREAR usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'crear') {
    $usuario = trim($_POST['usuario']);
    $clave = password_hash(trim($_POST['clave']), PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];

    try {
        $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, clave, rol, estado) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario, $clave, $rol, $estado]);
        $msg = "✅ Usuario creado exitosamente.";
    } catch (PDOException $e) {
        $msg = "❌ Error al crear usuario: " . $e->getMessage();
    }
}

// EDITAR usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'editar') {
    $id = intval($_POST['id']);
    $usuario = trim($_POST['usuario']);
    $rol = $_POST['rol'];
    $estado = $_POST['estado'];
    $clave = trim($_POST['clave']);

    try {
        if (!empty($clave)) {
            $hash = password_hash($clave, PASSWORD_DEFAULT);
            $conexion->prepare("UPDATE usuarios SET usuario=?, clave=?, rol=?, estado=? WHERE id=?")
                     ->execute([$usuario, $hash, $rol, $estado, $id]);
        } else {
            $conexion->prepare("UPDATE usuarios SET usuario=?, rol=?, estado=? WHERE id=?")
                     ->execute([$usuario, $rol, $estado, $id]);
        }
        $msg = "✅ Usuario actualizado correctamente.";
    } catch (PDOException $e) {
        $msg = "❌ Error al actualizar usuario: " . $e->getMessage();
    }
}

// ELIMINAR usuario
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    try {
        $conexion->prepare("DELETE FROM usuarios WHERE id=?")->execute([$id]);
        $msg = "✅ Usuario eliminado.";
    } catch (PDOException $e) {
        $msg = "❌ Error al eliminar usuario: " . $e->getMessage();
    }
}

// Listar usuarios
$usuarios = $conexion->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$roles = $conexion->query("SELECT rol FROM roles ORDER BY rol ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
}
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
    transition: background 0.2s;
}
.header-actions a:hover {
    background: #2c3e50;
}
button {
    cursor: pointer;
}
.tabla-moderna {
    width: 100%;
    border-collapse: collapse;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}
.tabla-moderna thead {
    background-color: #3498db;
    color: white;
}
.tabla-moderna th,
.tabla-moderna td {
    padding: 12px 15px;
    text-align: center;
}
.tabla-moderna tbody tr:nth-child(even) {
    background-color: #f4f8fb;
}
.tabla-moderna tbody tr:hover {
    background-color: #e0f3ff;
}
.tabla-moderna th {
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    position: relative;
}
.modal-content span {
    position: absolute;
    top: 10px;
    right: 15px;
    cursor: pointer;
    color: #888;
}
button.btn-crear {
    background: #27ae60;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    border: none;
    transition: background 0.2s;
}
button.btn-crear:hover {
    background: #219150;
}
button.btn-editar {
    background: #2980b9;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 5px 8px;
    transition: background 0.2s;
}
button.btn-editar:hover {
    background: #2471a3;
}
button.btn-eliminar {
    background: #c0392b;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 5px 8px;
    transition: background 0.2s;
}
button.btn-eliminar:hover {
    background: #a93226;
}
</style>

<h2 style="font-family:Segoe UI;">👥 Gestión de Usuarios</h2>

<!-- Links superiores -->
<div class="header-actions">
    <a href="<?= $nivel ?>configuracion/roles/roles.php">🔑 Roles</a>
    <a href="<?= $nivel ?>configuracion/permisos/permisos.php">🛡️ Permisos</a>
</div>

<?php if ($msg): ?>
    <p style="color:<?= str_starts_with($msg, '✅') ? 'green' : 'red' ?>;font-weight:bold;">
        <?= htmlspecialchars($msg) ?>
    </p>
<?php endif; ?>

<!-- Botón crear -->
<button class="btn-crear" onclick="document.getElementById('modal-crear').style.display='flex'">➕ Crear Usuario</button>

<!-- Tabla de usuarios -->
<table class="tabla-moderna">
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['usuario']) ?></td>
            <td><?= htmlspecialchars($u['rol']) ?></td>
            <td><?= htmlspecialchars($u['estado']) ?></td>
            <td>
                <button class="btn-editar" onclick="editarUsuario(<?= $u['id'] ?>, '<?= htmlspecialchars($u['usuario']) ?>', '<?= htmlspecialchars($u['rol']) ?>', '<?= htmlspecialchars($u['estado']) ?>')">✏️ Editar</button>
                <button class="btn-eliminar" onclick="eliminarUsuario(<?= $u['id'] ?>)">🗑️ Eliminar</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal Crear Usuario -->
<div id="modal-crear" class="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('modal-crear').style.display='none'">❌</span>
        <h3>➕ Crear Usuario</h3>
        <form method="post">
            <input type="hidden" name="accion" value="crear">
            <label>Usuario:</label><br>
            <input type="text" name="usuario" required style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Clave:</label><br>
            <input type="password" name="clave" required style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Rol:</label><br>
            <select name="rol" style="width:100%;padding:6px;margin-bottom:10px;">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= htmlspecialchars($r['rol']) ?>"><?= htmlspecialchars($r['rol']) ?></option>
                <?php endforeach; ?>
            </select><br>
            <label>Estado:</label><br>
            <select name="estado" style="width:100%;padding:6px;margin-bottom:10px;">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select><br>
            <button type="submit" class="btn-crear">💾 Guardar</button>
        </form>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div id="modal-editar" class="modal">
    <div class="modal-content">
        <span onclick="document.getElementById('modal-editar').style.display='none'">❌</span>
        <h3>✏️ Editar Usuario</h3>
        <form method="post">
            <input type="hidden" name="accion" value="editar">
            <input type="hidden" name="id" id="editar-id">
            <label>Usuario:</label><br>
            <input type="text" name="usuario" id="editar-usuario" required style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Clave (dejar vacío si no desea cambiarla):</label><br>
            <input type="password" name="clave" style="width:100%;padding:6px;margin-bottom:10px;"><br>
            <label>Rol:</label><br>
            <select name="rol" id="editar-rol" style="width:100%;padding:6px;margin-bottom:10px;">
                <?php foreach ($roles as $r): ?>
                    <option value="<?= htmlspecialchars($r['rol']) ?>"><?= htmlspecialchars($r['rol']) ?></option>
                <?php endforeach; ?>
            </select><br>
            <label>Estado:</label><br>
            <select name="estado" id="editar-estado" style="width:100%;padding:6px;margin-bottom:10px;">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select><br>
            <button type="submit" class="btn-editar">💾 Actualizar</button>
        </form>
    </div>
</div>

<script>
function editarUsuario(id, usuario, rol, estado) {
    document.getElementById('editar-id').value = id;
    document.getElementById('editar-usuario').value = usuario;
    document.getElementById('editar-rol').value = rol;
    document.getElementById('editar-estado').value = estado;
    document.getElementById('modal-editar').style.display = 'flex';
}

function eliminarUsuario(id) {
    if (confirm("¿Está seguro de que desea eliminar este usuario?")) {
        window.location.href = "?eliminar=" + id;
    }
}
</script>
