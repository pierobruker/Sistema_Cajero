<?php
include 'db.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"] ?? '';
    $clave = $_POST["clave"] ?? '';

    $stmt = $conn->prepare("SELECT id_admin, clave FROM mae_administrador WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($clave, $admin["clave"])) {
            $_SESSION["admin_id"] = $admin["id_admin"];
            header("Location: admin_panel.php");
            exit();
        } else {
            $error = "Clave incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Administrador</title>
  <link rel="stylesheet" href="css/crear_cliente.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Login Administrador</h2>

      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

      <form method="post">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="clave">Clave</label>
        <input type="password" id="clave" name="clave" required>

        <button type="submit">Ingresar</button>
      </form>
    </div>
  </div>
</body>
</html>
