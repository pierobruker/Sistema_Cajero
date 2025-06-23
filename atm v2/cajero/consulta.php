<?php
include 'db.php';
session_start();

$cliente_id = $_SESSION["cliente_id"] ?? null;
$saldo = 0;

if ($cliente_id) {
    $stmt = $conn->prepare("SELECT saldo_actual FROM mae_cliente WHERE id_cliente = ?");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    $saldo = $cliente["saldo_actual"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Consulta de Saldo</title>
  <link rel="stylesheet" href="css/consulta.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Consulta de Saldo</h2>
      <p class="saldo">Saldo actual</p>
      <p class="monto">S/ <?php echo number_format($saldo, 2); ?></p>
      <form action="menu.php" method="post">
        <button type="submit">Volver al Menú</button>
      </form>
    </div>
  </div>
</body>
</html>
