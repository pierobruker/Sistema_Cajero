<?php
include 'db.php';
session_start();

$cliente_id = $_SESSION["cliente_id"] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <div class="contenedor">
    <h2>Historial de Transacciones</h2>
    <table>
      <tr>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Saldo después</th>
        <th>Estado</th>
      </tr>
      <?php
      if ($cliente_id) {
          $stmt = $conn->prepare("
              SELECT t.monto, t.fecha_hora, t.saldo_resultante, t.estado
              FROM trs_transaccion t
              INNER JOIN mae_cuenta c ON t.id_cuenta = c.id_cuenta
              WHERE c.id_cliente = ?
              ORDER BY t.fecha_hora DESC
          ");
          $stmt->bind_param("i", $cliente_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>S/ " . number_format($row["monto"], 2) . "</td>";
                  echo "<td>" . $row["fecha_hora"] . "</td>";
                  echo "<td>S/ " . number_format($row["saldo_resultante"], 2) . "</td>";
                  echo "<td>" . $row["estado"] . "</td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='4'>No hay transacciones registradas.</td></tr>";
          }
      } else {
          echo "<tr><td colspan='4'>Usuario no autenticado.</td></tr>";
      }
      ?>
    </table>
    <form action="menu.php" method="post">
      <button type="submit">Volver al menú</button>
    </form>
  </div>
</body>
</html>
