<?php
include 'db.php';
session_start();

if (!isset($_SESSION["tecnico_id"])) {
    header("Location: login_tecnico.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $monto = floatval($_POST["monto"] ?? 0);
    $id_tecnico = $_SESSION["tecnico_id"];

    if ($monto > 0) {
        // Obtener saldo actual
        $stmt = $conn->prepare("SELECT efectivo_disponible FROM mae_cajero WHERE id_cajero = 1");
        $stmt->execute();
        $cajero = $stmt->get_result()->fetch_assoc();

        $nuevo_saldo = $cajero["efectivo_disponible"] + $monto;

        // Actualizar saldo
        $stmtU = $conn->prepare("UPDATE mae_cajero SET efectivo_disponible = ? WHERE id_cajero = 1");
        $stmtU->bind_param("d", $nuevo_saldo);
        $stmtU->execute();

        // Registrar en mantenimiento
        $tipo = "Reposición";
        $desc = "Reposición de S/ " . number_format($monto, 2);
        $stmtM = $conn->prepare("INSERT INTO trs_mantenimiento (id_tecnico, id_cajero, tipo, descripcion) VALUES (?, 1, ?, ?)");
        $stmtM->bind_param("iss", $id_tecnico, $tipo, $desc);
        $stmtM->execute();

        $success = "Reposición realizada exitosamente. Saldo actual: S/ " . number_format($nuevo_saldo, 2);
    } else {
        $error = "Monto inválido.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reposición Cajero</title>
  <link rel="stylesheet" href="css/crear_cliente.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Reposición de Efectivo</h2>

      <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

      <form method="post">
        <label for="monto">Monto a Reponer</label>
        <input type="number" id="monto" name="monto" required step="0.01" min="0.01">
        
        <button type="submit">Reponer</button>
      </form>

      <form action="logout_tecnico.php" method="post">
        <button type="submit" style="background-color: #dc3545;">Cerrar Sesión</button>
      </form>
    </div>
  </div>
</body>
</html>
