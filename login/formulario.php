<?php
session_start();
?>

  <!DOCTYPE html>
  <html lang="es">
  <head>
      <meta charset="UTF-8">
      <title>Iniciar sesión</title>
      <style>
          * {
              box-sizing: border-box;
              font-family: 'Segoe UI', sans-serif;
          }

          body {
              margin: 0;
              padding: 0;
              background: linear-gradient(135deg, #2c3e50, #3498db);
              height: 100vh;
              display: flex;
              justify-content: center;
              align-items: center;
          }

          .login-card {
              background: #ffffff;
              padding: 40px 30px;
              border-radius: 20px;
              box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
              width: 100%;
              max-width: 400px;
          }

          .login-card h2 {
              text-align: center;
              margin-bottom: 25px;
              font-size: 24px;
              color: #2c3e50;
          }

          .login-card label {
              display: block;
              margin-top: 15px;
              font-weight: bold;
              font-size: 15px;
          }

          .login-card input {
              width: 100%;
              padding: 12px;
              margin-top: 5px;
              border: 1px solid #ccc;
              border-radius: 10px;
              font-size: 15px;
          }

          .login-card button {
              width: 100%;
              padding: 12px;
              margin-top: 30px;
              background-color: #3498db;
              color: white;
              font-weight: bold;
              border: none;
              border-radius: 10px;
              font-size: 16px;
              cursor: pointer;
          }

          .login-card button:hover {
              background-color: #2c80b4;
          }

          .error {
              color: red;
              text-align: center;
              margin-top: 15px;
          }

          .footer {
              text-align: center;
              margin-top: 20px;
              font-size: 13px;
              color: #888;
          }
      </style>
  </head>
  <body>

      <div class="login-card">
          <h2>🔐 Iniciar Sesión</h2>

          <?php
          if (isset($_GET['error'])) {
              echo "<p class='error'>❌ Usuario o contraseña incorrectos</p>";
          }
          ?>

  <form action="validacion.php" method="POST">
    <div class="input-container">
      <i class="fas fa-user icon"></i>
      <input type="text" name="usuario" placeholder="Usuario" required>
    </div>
    <div class="input-container">
      <i class="fas fa-lock icon"></i>
      <input type="password" name="clave" placeholder="Contraseña" required>
    </div>
    <button type="submit">Ingresar</button>
  </form>
  <div class="footer">🤖 Sistema contable con inteligencia artificial © 2025</div>
</div>

</body>
</html>

