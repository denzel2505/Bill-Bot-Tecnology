<?php
session_start();
require '../conexion/conexion-BillBot.php';

if (isset($_SESSION['correo'])) {
    $correo = $_SESSION['correo'];

    // Actualizar la base de datos para marcar sesión como inactiva
    $query = "UPDATE usuarios SET sesion_activa = 0 WHERE correo = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->close();
}

// Destruir la sesión
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="message">
        <div class="imagen">
            <img src="../img/gif/loader-bot-3.gif" alt="" srcset="">
        </div>


        <div class="texto">
            <h1>Cerrando sesión...</h1>
            <p>Por favor espere un momento.</p>
        </div>

    </div>

    <script>
        // Mostrar mensaje y redirigir después de unos segundos
        setTimeout(() => {
            window.location.href = "../ingreso.php";
        }, 3000);
    </script>
</body>
</html>

<title>Cerrando Sesión...</title>
<style>
    body {
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f5f5f5;
        background-image: url('../img/fondos/fondo.avif');
        background-repeat: no-repeat;
        background-size: cover;
    }

    h1,p{
        color: #fff;
    }
    .message {
        display: flex;
        text-align: center;
        justify-content: space-around;
        padding: 25px 80px;
        background: linear-gradient(120deg,#1b2a5f,#373f5c) ;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .imagen,.texto{
        margin: auto;
        align-items: center;
    }

    img{
        width: 300px;
        padding: 0;
        margin: 0;
    }
</style>