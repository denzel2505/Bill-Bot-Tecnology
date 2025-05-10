<?php
// Obtener el rol del usuario actual
$queryRol = "SELECT rol FROM facturadores WHERE correo = ?";
$stmtRol = $con->prepare($queryRol);
$stmtRol->bind_param("s", $correo);
$stmtRol->execute();
$stmtRol->bind_result($rol_usuario);
$stmtRol->fetch();
$stmtRol->close();
?>
<aside class="sidebar">
    <div class="sidebar-start">
        <div class="sidebar-head">
            <a href="../dashboard/home.php" class="logo-wrapper">
                <span class="sr-only">Home</span>
                <span class="icon logo" aria-hidden="true"></span>
                <div class="logo-text">
                    <span class="logo-title">Bill Bot</span>
                    <span class="logo-subtitle">Administrador</span>
                </div>

            </a>
            <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
                <span class="sr-only">Toggle menu</span>
                <span class="icon menu-toggle" aria-hidden="true"></span>
            </button>
        </div>
        <div class="sidebar-body">
            <ul class="sidebar-body-menu">
                <li>
                    <a href="../dashboard/home.php"><span class="icon home" aria-hidden="true"></span>Dashboard</a>
                </li>
                <li>
                    <a class="show-cat-btn" href="##">
                        <span class="icon document" aria-hidden="true"></span>Armador
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a href="../dashboard/armador1.php">Armado masivo</a>
                        </li>
                        <li>
                            <a href="../dashboard/armador2.php">Armado de cuentas</a>
                        </li>
                    </ul>
                </li>
                
                <?php if($rol_usuario != 'Facturador'): ?>
                <li>
                    <a href="../historial_acceso/historial.php">
                        <span class="icon message" aria-hidden="true"></span>
                        Comments
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <span class="system-menu__title">SISTEMA</span>
            <ul class="sidebar-body-menu">
            <?php if($rol_usuario != 'Facturador'): ?>
                <li>
                    <a class="show-cat-btn" href="##">
                        <span class="icon user-3" aria-hidden="true"></span>Gestion de perfil
                        <span class="category__btn transparent-btn" title="Open list">
                            <span class="sr-only">Open list</span>
                            <span class="icon arrow-down" aria-hidden="true"></span>
                        </span>
                    </a>
                    <ul class="cat-sub-menu">
                        <li>
                            <a href="../perfiles/funcionarios.php">Ver Perfiles</a>
                        </li>
            
                    </ul>
                </li>
                <?php endif; ?>
                <li>
                    <a href="../video/info.php"><span class="icon setting" aria-hidden="true"></span>Configuracion</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer">
        <a href="mailto:montesdenzel25@gmail.com?subject=Solicitud%20de%20Soporte%20Tecnico" target='_blank' class="sidebar-user">
            <span class="sidebar-user-img">
                <picture class="bot"><source srcset="../img/bot.png" type="image/webp"><img src="./img/avatar/avatar-illustrated-01.png" alt="User name"></picture>
            </span>
            <div class="sidebar-user-info">
                <span class="sidebar-user__title">Bill Bot</span>
                <span class="sidebar-user__subtitle">Soporte tecnico</span>
            </div>
        </a>
    </div>
</aside>