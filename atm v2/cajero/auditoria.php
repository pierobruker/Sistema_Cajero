<?php
include 'db.php';
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login_admin.php");
    exit();
}

$result = $conn->query("SELECT * FROM mae_auditoria ORDER BY fecha_creacion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Auditoría</title>
  <link rel="stylesheet" href="css/crear_cliente.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Registro de Auditoría</h2>
      <table border="1" width="100%" style="font-size:0.9rem;">
        <tr>
          <th>ID</th>
          <th>Detalle</th>
          <th>Fecha</th>
          <th>Usuario</th>
          <th>Estado</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row["id_auditoria"] ?></td>
          <td><?= htmlspecialchars($row["detalle_del_cambio"]) ?></td>
          <td><?= $row["fecha_creacion"] ?></td>
          <td><?= htmlspecialchars($row["usuario_creacion"]) ?></td>
          <td><?= htmlspecialchars($row["estado"]) ?></td>
        </tr>
        <?php endwhile; ?>
      </table>
      <form action="admin_panel.php" method="get">
        <button type="submit">Volver al Panel</button>
      </form>
    </div>
  </div>
</body>
</html>
