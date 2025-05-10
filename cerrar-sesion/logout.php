<?php
// Primero iniciamos la sesión para acceder a las variables
session_start();

// Incluimos la conexión a la base de datos
require_once '../conexion/conexion-BillBot.php';

// Verificamos si las variables de sesión existen antes de usarlas
if (isset($_SESSION['correo']) && isset($_SESSION['rol'])) {
    $correo = $_SESSION['correo'];
    $rol = $_SESSION['rol'];
    
    // Según el rol, actualizamos la tabla correspondiente
    if ($rol === 'administrador') {
        $query = "UPDATE administrador SET sesion_activa = 0, ultima_conexion = NOW() WHERE correo = ?";
    } else {
        $query = "UPDATE facturadores SET session_activa = 0, ultima_actividad = NOW() WHERE correo = ?";
    }
    
    // Preparamos y ejecutamos la consulta
    $stmt = $con->prepare($query);
    if ($stmt) {
        $stmt->bind_param("s", $correo);
        $result = $stmt->execute();
        
        // Verificamos si la actualización fue exitosa
        if ($result) {
            // Registramos en el log que el cierre de sesión fue exitoso
            error_log("Cierre de sesión exitoso para el usuario: $correo con rol: $rol");
        } else {
            // Registramos el error en el log
            error_log("Error al actualizar sesion_activa para el usuario: $correo. Error: " . $stmt->error);
        }
        
        $stmt->close();
    } else {
        // Si hay un error en la preparación de la consulta
        error_log("Error al preparar la consulta para cerrar sesión: " . $con->error);
    }
} else {
    // Si no hay variables de sesión, lo registramos en el log
    error_log("Intento de cierre de sesión sin variables de sesión establecidas");
}

// Finalmente, destruimos la sesión
$_SESSION = array(); // Limpiamos todas las variables de sesión
if (ini_get("session.use_cookies")) {
    // Invalidamos la cookie de sesión
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy(); // Destruimos la sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            margin: 0;
            padding: 0;
        }

        h1, p {
            color: #fff;
        }
        
        .message {
            display: flex;
            text-align: center;
            justify-content: space-around;
            padding: 25px 80px;
            background: linear-gradient(120deg, #1b2a5f, #373f5c);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .imagen, .texto {
            margin: auto;
            align-items: center;
        }

        img {
            width: 300px;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="message">
        <div class="imagen">
            <img src="../img/gif/loader-bot-3.gif" alt="Cerrando sesión" srcset="">
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