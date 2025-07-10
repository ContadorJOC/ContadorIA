<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/formulario.php");
    exit();
}

require_once('../../conexion/conexion.php');

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("❌ ID inválido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traer columnas y datos actuales
    $columnas = $conexion->query("PRAGMA table_info(listadoempleados)")->fetchAll(PDO::FETCH_ASSOC);
    $updates = [];
    $valores = [];

    foreach ($columnas as $col) {
        $columna = $col['name'];
        if ($columna === 'id') continue; // no actualizamos el ID
        $valor = trim($_POST[$columna] ?? '');
        $updates[] = "$columna = ?";
        $valores[] = $valor;
    }

    if (!empty($updates)) {
        $valores[] = $id; // ID al final para WHERE
        $sql = "UPDATE listadoempleados SET " . implode(", ", $updates) . " WHERE id = ?";
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->execute($valores);
            header("Location: listadoempleados.php?msg=✅ Registro actualizado");
            exit();
        } catch (PDOException $e) {
            die("❌ Error al actualizar: " . $e->getMessage());
        }
    } else {
        die("❌ No se recibieron datos para actualizar.");
    }
}

// Si no es POST, traer datos del empleado
$dato = $conexion->query("SELECT * FROM listadoempleados WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
if (!$dato) {
    die("❌ Empleado no encontrado.");
}

$json = "encabezados_listadoempleados.json";
$etiquetas = file_exists($json) ? json_decode(file_get_contents($json), true) : [];
?>

<h2>✏️ Editar Registro</h2>
<form action="editar_listadoempleados.php?id=<?= $id ?>" method="POST">
    <?php foreach ($dato as $campo => $valor): ?>
        <?php if ($campo === 'id') continue; ?>
        <label><?= htmlspecialchars($etiquetas[$campo] ?? strtoupper(str_replace('_', ' ', $campo))) ?>:</label><br>
        <?php if ($campo === 'cedula'): ?>
            <!-- La cedula no se puede modificar pero la enviamos oculta -->
            <input type="text" value="<?= htmlspecialchars($valor) ?>" readonly style="width:100%; background:#eee;"><br>
            <input type="hidden" name="cedula" value="<?= htmlspecialchars($valor) ?>">
        <?php else: ?>
            <input type="text" name="<?= $campo ?>" value="<?= htmlspecialchars($valor) ?>" style="width:100%;"><br><br>
        <?php endif; ?>
    <?php endforeach; ?>
    <button type="submit" style="padding:10px 20px;">💾 Actualizar</button>
</form>
