<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Seleccione Transacción</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <div class="contenedor">
    <h2>Seleccione la Transacción</h2>
    <form action="retiro.php" method="post">
      <button type="submit" name="opcion" value="retiro">Retiro</button>
    </form>
    <form action="consulta.php" method="post">
      <button type="submit" name="opcion" value="consulta">Consulta de saldo</button>
    </form>
    <form action="historial.php" method="post">
      <button type="submit" name="opcion" value="historial">Histórico de transacciones</button>
    </form>
    <form action="index.php" method="post">
      <button type="submit">Volver</button>
    </form>
  </div>
</body>
</html>
