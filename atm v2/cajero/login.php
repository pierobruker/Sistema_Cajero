<?php
include 'db.php';
session_start();

$tarjeta = '';
$pin = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarjeta = $_POST["tarjeta"] ?? '';
    $pin = $_POST["pin"] ?? '';

    if (!empty($tarjeta) && !empty($pin)) {
        $stmt = $conn->prepare("SELECT id_cliente, pin_hash, saldo_actual FROM mae_cliente WHERE tarjeta = ?");
        $stmt->bind_param("s", $tarjeta);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $cliente = $result->fetch_assoc();
            if (password_verify($pin, $cliente["pin_hash"])) {
                $_SESSION["cliente_id"] = $cliente["id_cliente"];
                $_SESSION["saldo"] = $cliente["saldo_actual"];
                header("Location: menu.php");
                exit();
            } else {
                $error = "PIN incorrecto.";
            }
        } else {
            $error = "Tarjeta no encontrada.";
        }
    } else {
        $error = "Ingrese todos los campos.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Autenticación</title>
  <link rel="stylesheet" href="css/estilologin.css">
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="icon-container">
        <span class="lock-icon">🔒</span>
      </div>
      <h2>Autenticación</h2>
      <a href="#" class="secure-link">🔄 Sistema seguro</a>

      <form method="post" action="login.php">
        <label for="tarjeta">Número de Tarjeta</label>
        <input type="text" id="tarjeta" name="tarjeta" maxlength="16" pattern="\d{16}" required placeholder="Ingrese los 16 dígitos">

        <label for="pin">Ingrese su PIN</label>
        <input type="password" id="pin" name="pin" maxlength="4" pattern="\d{4}" required placeholder="****">

        <button type="submit">Ingresar PIN</button>
      </form>

      <a href="ayuda.php" class="help-link">❓ Olvidé mi PIN</a>

      <p class="security-note">Por seguridad, no compartas tu PIN con nadie</p>
    </div>
  </div>
</body>
</html>

