<?php
require_once './Elkin/db.php'; 
require_once './phpmailer/src/Exception.php';
require_once './phpmailer/src/PHPMailer.php';
require_once './phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    error_log("Email recibido del formulario: {$email}");

    $user = null;
    $tabla_tokens = '';
    $reset_link = '';

    // Buscar en tabla administrador
    $stmt = $pdo->prepare("SELECT id, nombre FROM administrador WHERE correo = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $tabla_tokens = 'password_reset_tokens';
        $reset_link = "https://localhost/billboot/Elkin/reset_password.php?token=";
    } else {
        // Buscar en tabla facturadores
        $stmt = $pdo->prepare("SELECT id, nombre FROM facturadores WHERE correo = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $tabla_tokens = 'password_reset_tokens_usuarios2';
            $reset_link = "https://localhost/billboot/Elkin/reset_password.php?token=";
        }
    }

    if ($user) {
        $user_id = $user['id'];
        $user_name = $user['nombre'];

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Guardar en la tabla correspondiente
        $stmt = $pdo->prepare("INSERT INTO {$tabla_tokens} (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $token, $expires]);

        $reset_link .= $token;

        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'elkinmb3@gmail.com';
            $mail->Password = 'kvfu plfo zprd rqfm';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('elkinmb3@gmail.com', 'Bill Bot Technology');
            $mail->addAddress($email, $user_name);

            $mail->isHTML(true);
            $mail->Subject = 'Recuperación de Contraseña - Bill Bot Technology';
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->AltBody = 'Este es el cuerpo alternativo en texto plano.';
            $mail->Body = "
                <html><body>
                <h1>Recuperación de Contraseña</h1>
                <p>Hola <strong>{$user_name}</strong>,</p>
                <p>Haz clic en el siguiente enlace para restablecer tu contraseña:</p>
                <p><a href='{$reset_link}' target='_blank'>Restablecer mi contraseña</a></p>
                <p><em>Este enlace expirará en 1 hora.</em></p>
                </body></html>";

            $mail->send();
            error_log("Correo enviado a: {$email}");
        } catch (Exception $e) {
            error_log("Error PHPMailer: " . $mail->ErrorInfo);
        }

        header("Location: ./forgot_password.php?status=success");
        exit;
    } else {
        error_log("Correo no encontrado en ninguna tabla: {$email}");
        header("Location: ./forgot_password.php?status=not_found");
        exit;
    }
}

header("Location: ./Elkin/forgot_password.php");
exit;
?>