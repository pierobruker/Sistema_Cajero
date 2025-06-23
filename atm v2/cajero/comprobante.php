<?php
session_start();

$monto = $_SESSION["ultimo_monto"] ?? 0;
$saldo = $_SESSION["nuevo_saldo"] ?? 0;
$fecha = date("d/m/Y H:i:s");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprobante</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <div class="contenedor">
    <h2>Comprobante de Retiro</h2>
    <p>Monto retirado: S/ <?php echo number_format($monto, 2); ?></p>
    <p>Saldo restante: S/ <?php echo number_format($saldo, 2); ?></p>
    <p>Fecha y hora: <?php echo $fecha; ?></p>

    <form action="menu.php" method="post" style="display:inline;">
      <button type="submit">Realizar otra operación</button>
    </form>

    <form action="cerrar_sesion.php" method="post" style="display:inline;">
      <button type="submit">Cerrar sesión</button>
    </form>
  </div>
</body>
</html>
