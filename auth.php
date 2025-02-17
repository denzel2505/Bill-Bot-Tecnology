<?php
require 'conexion-BillBot.php'; // Conexión a la base de datos


if (!isset($_SESSION['correo'])) {
    header("Location: ./ingreso.php");
    exit;
}

$correo = $_SESSION['correo'];

// Verificar si la sesión está activa en la base de datos
$query = "SELECT sesion_activa FROM usuarios WHERE correo = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($sesion_activa);
$stmt->fetch();
$stmt->close();

if ($sesion_activa == 0) {
    session_unset();
    session_destroy();
    header("Location: ./ingreso.php");
    exit;
}
?>
