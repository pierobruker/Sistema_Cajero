<?php
include 'db.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"] ?? '';
    $telefono = $_POST["telefono"] ?? '';

    $stmt = $conn->prepare("SELECT id_tecnico FROM mae_tecnico WHERE correo = ? AND telefono = ?");
    $stmt->bind_param("ss", $correo, $telefono);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $tecnico = $result->fetch_assoc();
        $_SESSION["tecnico_id"] = $tecnico["id_tecnico"];
        header("Location: reposicion.php");
        exit();
    } else {
        $error = "Credenciales inválidas.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login Técnico</title>
  <link rel="stylesheet" href="css/crear_cliente.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Login Técnico</h2>
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      <form method="post">
        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" required>
        
        <label for="telefono">Teléfono</label>
        <input type="text" id="telefono" name="telefono" required pattern="\d{9,15}">
        
        <button type="submit">Ingresar</button>
      </form>
    </div>
  </div>
</body>
</html>
