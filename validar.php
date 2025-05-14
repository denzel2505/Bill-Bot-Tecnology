<?php
include './conexion/init.php'; // Iniciar la sesión
include("./conexion/conexion-BillBot.php");

if (!isset($_SESSION['correo'])) {
    header('Location: ./ingreso.php');
    exit();
}
$correo = $_POST['correo'];
$contraseña = $_POST['contraseña'];
$_SESSION['correo'] = $correo;



// Verificar en tabla administrador
$consultaAdmin = "SELECT * FROM administrador WHERE correo = ? AND contraseña = ?";
$stmtAdmin = $con->prepare($consultaAdmin);
$stmtAdmin->bind_param("ss", $correo, $contraseña);
$stmtAdmin->execute();
$resultAdmin = $stmtAdmin->get_result();
$admin = $resultAdmin->fetch_assoc();

if ($admin) {
    $_SESSION['usuario_id'] = $admin['id']; // Guardar el ID del usuario en la sesión
    $_SESSION['rol'] = 'administrador';

    // Actualizar la sesión activa para el administrador
    $updateQuery = "UPDATE administrador SET sesion_activa = 1 , ultima_conexion = NOW() WHERE correo = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("s", $correo);
    $updateStmt->execute();
    
    // Redirigir al dashboard
    header("location: ./dashboard/home.php");
    $updateStmt->close();
    exit();
}

$stmtAdmin->close();

// Verificar en tabla facturadores
$consultaFact = "SELECT * FROM facturadores WHERE correo = ? AND contraseña = ?";
$stmtFact = $con->prepare($consultaFact);
$stmtFact->bind_param("ss", $correo, $contraseña);
$stmtFact->execute();
$resultFact = $stmtFact->get_result();
$facturador = $resultFact->fetch_assoc();

if ($facturador) {
    $_SESSION['usuario_id'] = $facturador['id']; // Guardar el ID del usuario en la sesión
    $_SESSION['rol'] = 'facturador';
  

    // Actualizar la sesión activa para el facturador
    $updateQuery = "UPDATE facturadores SET session_activa = 1, ultima_actividad = NOW() WHERE correo = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("s", $correo);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirigir al dashboard
    header("location: ./dashboard/home.php"); 
    exit();
}

$stmtFact->close();
$con->close();

// Si no se encontró en ninguna tabla
include("./ingreso.php");
echo '<script>alert("Datos Incorrectos")</script>';
?>
