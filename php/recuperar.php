<?php
include '../conexion/conexion-BillBot.php'; // Archivo con la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['correo'];

    // Verificar si el correo existe
    $stmt = $con->prepare("SELECT * FROM administrador WHERE correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $token = bin2hex(random_bytes(50)); // Generar token seguro
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour")); // Expira en 1 hora

        // Guardar token en la base de datos
        $stmt = $con->prepare("INSERT INTO recuperacion (email, token, expira) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expira);
        $stmt->execute();

        // Enviar correo con el enlace de recuperación
        $enlace = "http://localhost/bill-bot/recuperar-contraseña/reset.php?token=" . $token;
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $enlace;

        mail($email, "Recuperar contraseña", $mensaje, "From: no-reply@tusitio.com");

        echo "Se ha enviado un correo con las instrucciones.";
    } else {
        echo "El correo no está registrado.";
    }
}
?>
