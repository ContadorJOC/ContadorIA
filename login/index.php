<?php
session_start();
if (isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit();
}
?>

<form method="post" action="validar.php">
    <input type="text" name="usuario" placeholder="Usuario" required><br>
    <input type="password" name="clave" placeholder="Clave" required><br>
    <button type="submit">Iniciar sesión</button>
</form>
