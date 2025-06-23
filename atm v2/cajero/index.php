<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cajero Automático - Bienvenido</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <div class="contenedor">
    <h1>Bienvenido al Cajero</h1>
    <img src="img/logo.png" alt="Logo del Banco" class="logo">
    <p>Por favor, inserte su tarjeta para comenzar.</p>
    <form action="login.php" method="post">
      <button type="submit">Insertar tarjeta</button>
    </form>

    <form action="ayuda.php" method="get">
    <button type="submit">Ayuda</button>
    </form>

  </div>
</body>
</html>
