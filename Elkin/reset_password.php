<?php
require_once './db.php';

if (!isset($_GET['token'])) {
    die('Token no proporcionado.');
}

$token = $_GET['token'];
$tokenData = null;
$tabla_usuarios = '';
$tabla_tokens = '';

// Verificar en tabla `password_reset_tokens`
$stmt = $pdo->prepare("SELECT user_id, expires_at FROM password_reset_tokens WHERE token = ?");
$stmt->execute([$token]);
$tokenData = $stmt->fetch();

if ($tokenData) {
    $tabla_usuarios = 'administrador';
    $tabla_tokens = 'password_reset_tokens';
} else {
    // Verificar en tabla `password_reset_tokens_usuarios2`
    $stmt = $pdo->prepare("SELECT user_id, expires_at FROM password_reset_tokens_usuarios2 WHERE token = ?");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch();

    if ($tokenData) {
        $tabla_usuarios = 'facturadores';
        $tabla_tokens = 'password_reset_tokens_usuarios2';
    }
}

if (!$tokenData || strtotime($tokenData['expires_at']) < time()) {
    die('El enlace de restablecimiento no es válido o ha expirado.');
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($password) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres.';
    } else {

        // Actualizar la contraseña
        $stmt = $pdo->prepare("UPDATE {$tabla_usuarios} SET contraseña = ? WHERE id = ?");
        $stmt->execute([$password , $tokenData['user_id']]);

        // Eliminar el token
        $stmt = $pdo->prepare("DELETE FROM {$tabla_tokens} WHERE token = ?");
        $stmt->execute([$token]);

        $success = 'Tu contraseña ha sido restablecida exitosamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Restablecer Contraseña</div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php elseif ($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php else: ?>
                        <form method="POST">
                            <div class="form-group">
                                <label for="password">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <small class="form-text text-muted">Mínimo 8 caracteres.</small>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirmar Contraseña</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">Restablecer Contraseña</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
