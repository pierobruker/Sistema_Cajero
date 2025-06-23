<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Retiro de Efectivo</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>

<body>
  <div class="contenedor">
    <h2>Seleccione Monto</h2>
    <form action="confirmar.php" method="post">
  <button type="submit" name="monto" value="20">S/ 20</button>
  <button type="submit" name="monto" value="50">S/ 50</button>
  <button type="submit" name="monto" value="100">S/ 100</button>
</form>

<form action="confirmar.php" method="post">
  <label for="otro">Otro monto:</label>
  <input type="number" name="monto" id="otro" min="10" step="10" required>
  <button type="submit">Confirmar</button>
</form>
  </div>
</body>
</html>
