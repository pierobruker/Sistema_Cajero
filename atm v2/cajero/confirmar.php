<?php
include 'db.php';
session_start();

$cliente_id = $_SESSION["cliente_id"] ?? null;
$monto = floatval($_POST["monto"] ?? 0);

$error = '';

if ($cliente_id && $monto > 0) {
    $stmt = $conn->prepare("SELECT saldo_actual FROM mae_cliente WHERE id_cliente = ?");
    $stmt->bind_param("i", $cliente_id);
    $stmt->execute();
    $cliente = $stmt->get_result()->fetch_assoc();

    $stmtC = $conn->prepare("SELECT efectivo_disponible FROM mae_cajero WHERE id_cajero = 1");
    $stmtC->execute();
    $cajero = $stmtC->get_result()->fetch_assoc();

    if ($cliente["saldo_actual"] >= $monto) {
        if ($cajero["efectivo_disponible"] >= $monto) {
            $nuevo_saldo = $cliente["saldo_actual"] - $monto;
            $nuevo_efectivo = $cajero["efectivo_disponible"] - $monto;

            $stmtU = $conn->prepare("UPDATE mae_cliente SET saldo_actual = ? WHERE id_cliente = ?");
            $stmtU->bind_param("di", $nuevo_saldo, $cliente_id);
            $stmtU->execute();

            $stmtUC = $conn->prepare("UPDATE mae_cajero SET efectivo_disponible = ? WHERE id_cajero = 1");
            $stmtUC->bind_param("d", $nuevo_efectivo);
            $stmtUC->execute();

            $stmtAcc = $conn->prepare("SELECT id_cuenta FROM mae_cuenta WHERE id_cliente = ?");
            $stmtAcc->bind_param("i", $cliente_id);
            $stmtAcc->execute();
            $cuenta = $stmtAcc->get_result()->fetch_assoc();
            $id_cuenta = $cuenta["id_cuenta"] ?? null;

            if ($id_cuenta) {
                $id_tipo = 1;
                $stmtT = $conn->prepare("
                    INSERT INTO trs_transaccion 
                    (id_cuenta, id_cajero, id_tipo, monto, saldo_resultante, estado) 
                    VALUES (?, 1, ?, ?, ?, 'completada')
                ");
                $stmtT->bind_param("iidd", $id_cuenta, $id_tipo, $monto, $nuevo_saldo);
                $stmtT->execute();

                $_SESSION["ultimo_monto"] = $monto;
                $_SESSION["nuevo_saldo"] = $nuevo_saldo;

                // Notificar si el efectivo está bajo
                if ($nuevo_efectivo < 2500) {
                    echo "<script>alert('⚠ Atención: El cajero tiene menos de S/ 2500. Notificando al técnico...');</script>";
                    // Aquí podrías insertar en trs_mantenimiento un registro de necesidad
                }

                header("Location: comprobante.php");
                exit();
            } else {
                $error = "El cliente no tiene cuenta asociada.";
            }
        } else {
            $error = "El cajero no tiene suficiente efectivo disponible.";
        }
    } else {
        $error = "Saldo insuficiente en su cuenta.";
    }
} else {
    $error = "Monto inválido o sesión expirada.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Confirmación</title>
  <script>
    window.onload = function() {
      <?php if (!empty($error)): ?>
        alert("<?php echo $error; ?>");
        window.location.href = 'menu.php';
      <?php endif; ?>
    };
  </script>
</head>
<body>
</body>
</html>
