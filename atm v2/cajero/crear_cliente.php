<?php
include 'db.php';

$error = '';
$success = '';
$stmtA = $conn->prepare("INSERT INTO mae_auditoria (detalle_del_cambio, usuario_creacion, estado, id_admin) VALUES (?, ?, 'activo', ?)");
$detalle = "Creación de cliente: $nombre $apellido";
$usuario_creacion = $_SESSION["admin_id"];
$stmtA->bind_param("ssi", $detalle, $usuario_creacion, $_SESSION["admin_id"]);
$stmtA->execute();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $tarjeta = $_POST["tarjeta"];
    $pin = $_POST["pin"];
    $saldo = $_POST["saldo"];

    $pin_hash = password_hash($pin, PASSWORD_DEFAULT);

    try {
        // Insertar cliente
        $stmt = $conn->prepare("INSERT INTO mae_cliente (nombre, apellido, dni, tarjeta, pin_hash, saldo_actual) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssd", $nombre, $apellido, $dni, $tarjeta, $pin_hash, $saldo);
        $stmt->execute();

        // Obtener el ID del cliente recién creado
        $cliente_id = $conn->insert_id;

        // Crear número de cuenta (por ejemplo 000 + ID con relleno)
        $numero_cuenta = '000' . str_pad($cliente_id, 9, '0', STR_PAD_LEFT);

        // Insertar cuenta asociada
        $stmtCta = $conn->prepare("INSERT INTO mae_cuenta (id_cliente, numero_cuenta, tipo_moneda, saldo, estado) VALUES (?, ?, 'S/', ?, 'activo')");
        $stmtCta->bind_param("isd", $cliente_id, $numero_cuenta, $saldo);
        $stmtCta->execute();

        $success = "Cliente y cuenta creados exitosamente.";

    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            $error = "Error: Ya existe un cliente con ese DNI o Tarjeta.";
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
  <title>Registro de Cliente</title>
  <link rel="stylesheet" href="css/crear_cliente.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <h2>Registro de Cliente</h2>

      <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

      <form method="post">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellido">Apellido</label>
        <input type="text" id="apellido" name="apellido" required>

        <label for="dni">DNI</label>
        <input type="text" id="dni" name="dni" required pattern="\d{8}">

        <label for="tarjeta">Número de Tarjeta</label>
        <input type="text" id="tarjeta" name="tarjeta" required pattern="\d{16}">

        <label for="pin">PIN</label>
        <input type="password" id="pin" name="pin" required pattern="\d{4}">

        <label for="saldo">Saldo Inicial</label>
        <input type="number" id="saldo" name="saldo" required step="0.01">

        <button type="submit">Crear Cliente</button>
      </form>
    </div>
  </div>
</body>
</html>

