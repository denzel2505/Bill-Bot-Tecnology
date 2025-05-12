<?php
// update_password.php - Procesa la actualización de contraseña
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = isset($_POST['token']) ? $_POST['token'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Validar datos
    if (empty($token) || empty($password) || empty($confirm_password)) {
        header("Location: reset_password.php?token=$token&error=empty_fields");
        exit;
    }
    
    if ($password !== $confirm_password) {
        header("Location: reset_password.php?token=$token&error=password_mismatch");
        exit;
    }
    
    if (strlen($password) < 8) {
        header("Location: reset_password.php?token=$token&error=password_length");
        exit;
    }
    
    // Verificar si el token es válido y obtener el usuario
    $stmt = $pdo->prepare("SELECT user_id FROM password_reset_tokens WHERE token = ? AND used = 0 AND expires_at > NOW()");
    $stmt->execute([$token]);
    $row = $stmt->fetch();
    
    if ($row) {
        $user_id = $row['user_id'];
        
        // Actualizar contraseña (usando hash seguro)
        $stmt = $pdo->prepare("UPDATE administrador SET contraseña = ? WHERE id = ?");
        $stmt->execute([$password, $user_id]);
        
        // Marcar token como usado
        $stmt = $pdo->prepare("UPDATE password_reset_tokens SET used = 1 WHERE token = ?");
        $stmt->execute([$token]);
        
        // Redirigir a página de éxito
        header("Location: ../ingreso.php?status=password_updated");
        exit;
    } else {
        header("Location: forgot_password.php?error=invalid_token");
        exit;
    }
} else {
    // Si no es POST, redirigir
    header("Location: forgot_password.php");
    exit;
}