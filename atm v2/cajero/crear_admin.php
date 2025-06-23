<?php
include 'db.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $clave = $_POST["clave"];
    $nombre = $_POST["nombre"];

    // Generar hash de la clave
    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("INSERT INTO mae_administrador (usuario, clave, nombre_completo) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, $clave_hash, $nombre);
        $stmt->execute();

        $success = "Administrador creado exitosamente.";
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $error = "Error: El usuario ya existe.";
        } else {
            $error = "Error inesperado: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Administrador</title>
  <link rel="stylesheet" href="css/crear_cliente.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Crear Administrador</h2>

      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

      <form method="post">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="clave">Clave</label>
        <input type="password" id="clave" name="clave" required>

        <label for="nombre">Nombre Completo</label>
        <input type="text" id="nombre" name="nombre" required>

        <button type="submit">Crear Administrador</button>
      </form>
    </div>
  </div>
</body>
</html>
