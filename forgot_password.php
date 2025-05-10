<?php
// forgot_password.php - Formulario para solicitar restablecimiento de contraseña
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Recuperar Contraseña</div>
                    <div class="card-body">
                        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                            <div class="alert alert-success">
                                Si el correo existe en nuestra base de datos, recibirás un enlace para restablecer tu contraseña.
                            </div>
                        <?php endif; ?>
                        
                        <form action="./send_reset_link.php" method="post">
                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Enviar enlace de recuperación</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>