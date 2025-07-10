<?php
$nivel = '../../';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: {$nivel}login/formulario.php");
    exit();
}
require_once("{$nivel}conexion/conexion.php");

// Traer roles existentes
try {
    $roles = $conexion->query("SELECT id, rol FROM roles ORDER BY rol ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $roles = [];
    $error = "❌ Error al cargar roles: " . $e->getMessage();
}

// Procesar antes de incluir plantilla
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $clave = password_hash(trim($_POST['clave']), PASSWORD_DEFAULT);
    $rol_id = $_POST['rol'];
    $estado = $_POST['estado'];

    try {
        $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, clave, rol, estado) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario, $clave, $rol_id, $estado]);
        header("Location: usuarios.php");
        exit();
    } catch (PDOException $e) {
        $error = "❌ Error al guardar el usuario: " . $e->getMessage();
    }
}

require_once("{$nivel}componentes/plantilla.php");
?>

<style>
    .form-container {
        max-width: 500px;
        margin: 40px auto;
        background: #fff;
        padding: 30px 40px;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', sans-serif;
        font-size: 17px;
    }

    .form-container h2 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 24px;
    }

    .form-container label {
        font-weight: bold;
        margin-top: 15px;
        display: block;
    }

    .form-container input[type="text"],
    .form-container input[type="password"],
    .form-container select {
        width: 100%;
        padding: 10px 12px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
    }

    .form-container button {
        margin-top: 25px;
        width: 100%;
        padding: 12px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 17px;
        font-weight: bold;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #2b7bbb;
    }

    .form-container a {
        display: block;
        text-align: center;
        margin-top: 15px;
        font-size: 16px;
        color: #333;
        text-decoration: none;
    }

    .form-container a:hover {
        text-decoration: underline;
    }

    .error-msg {
        color: red;
        text-align: center;
        margin-bottom: 15px;
        font-size: 16px;
    }
</style>

<div class="form-container">
    <h2>➕ Crear nuevo usuario</h2>

    <?php if (isset($error)) echo "<p class='error-msg'>{$error}</p>"; ?>

    <form method="post">
        <label>Usuario:</label>
        <input type="text" name="usuario" required>

        <label>Clave:</label>
        <input type="password" name="clave" required>

        <label>Rol:</label>
        <select name="rol" required>
            <option value="">-- Seleccione un rol --</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['rol'] ?>"><?= htmlspecialchars($r['rol']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Estado:</label>
        <select name="estado" required>
            <option value="activo">Activo</option>
            <option value="inactivo">Inactivo</option>
        </select>

        <button type="submit">💾 Guardar usuario</button>
    </form>

    <a href="usuarios.php">🔙 Volver al listado</a>
</div>
