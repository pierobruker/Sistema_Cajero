<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administrador</title>
  <link rel="stylesheet" href="css/crear_cliente.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Panel del Administrador</h2>
      <p>Bienvenido, administrador. Selecciona una acción:</p>

      <form action="crear_cliente.php" method="get">
        <button type="submit">Crear Cliente</button>
      </form>

      <form action="crear_admin.php" method="get">
        <button type="submit">Crear Otro Administrador</button>
      </form>

      <form action="auditoria.php" method="get">
        <button type="submit">Ver Auditoría</button>
      </form>

      <form action="logout_admin.php" method="post">
        <button type="submit" style="background-color: #dc3545;">Cerrar Sesión</button>
      </form>
    </div>
  </div>
</body>
</html>
