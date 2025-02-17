<?php
session_start();
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

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Funcionario Administrador</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="./img/svg/logo.svg" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="./css/style.min.css">
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
  
  <div class="layer"></div>
<!-- ! Body -->
<a class="skip-link sr-only" href="#skip-target">Skip to content</a>
<div class="page-flex">
  <!-- ! Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-start">
        <div class="sidebar-head">
            <a href="./home.php" class="logo-wrapper" title="Home">
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
                    <a href="./home.php"><span class="icon home" aria-hidden="true"></span>Dashboard</a>
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
                            <a href="./armador1.php">Armado masivo</a>
                        </li>
                        <li>
                            <a href="./armador2.php">Armado de cuentas</a>
                        </li>
                    </ul>
                </li>
                
              
                <li>
                    <a href="#">
                        <span class="icon message" aria-hidden="true"></span>
                        Comments
                    </a>
                </li>
            </ul>
            <span class="system-menu__title">SISTEMA</span>
            <ul class="sidebar-body-menu">
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
                            <a href="./perfiles/funcionarios.php">Ver Perfiles</a>
                        </li>
            
                    </ul>
                </li>
                <li>
                    <a href="##"><span class="icon setting" aria-hidden="true"></span>Configuracion</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-footer">
        <a href="#" target='_blank' class="sidebar-user">
            <span class="sidebar-user-img">
                <picture class="bot"><source srcset="./img/bot.png" type="image/webp"><img src="./img/avatar/avatar-illustrated-01.png" alt="User name"></picture>
            </span>
            <div class="sidebar-user-info">
                <span class="sidebar-user__title">Bill Bot</span>
                <span class="sidebar-user__subtitle">Soporte tecnico</span>
            </div>
        </a>
    </div>
</aside>
  <div class="main-wrapper">
    <!-- ! Main nav -->
    <nav class="main-nav--bg">
  <div class="container main-nav">
    <div class="main-nav-start">
      <div class="search-wrapper">
        
      </div>
    </div>
    <div class="main-nav-end">
      <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
        <span class="sr-only">Toggle menu</span>
        <span class="icon menu-toggle--gray" aria-hidden="true"></span>
      </button>
      <div class="lang-switcher-wrapper">
        <button class="lang-switcher transparent-btn" type="button">
          ES
          <i data-feather="chevron-down" aria-hidden="true"></i>
        </button>
        <ul class="lang-menu dropdown">
          <li><a href="##">Ingles</a></li>
          <li><a href="##">Español</a></li>
          <li><a href="##">Portugues</a></li>
        </ul>
      </div>
      <button class="theme-switcher gray-circle-btn" type="button" title="Switch theme">
        <span class="sr-only">Switch theme</span>
        <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
        <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
      </button>
      <div class="notification-wrapper">
        <button class="gray-circle-btn dropdown-btn" title="To messages" type="button">
          <span class="sr-only">To messages</span>
          <span class="icon notification active" aria-hidden="true"></span>
        </button>
        <ul class="users-item-dropdown notification-dropdown dropdown">
          <li>
            <a href="##">
              <div class="notification-dropdown-icon info">
                <i data-feather="check"></i>
              </div>
              <div class="notification-dropdown-text">
                <span class="notification-dropdown__title">System just updated</span>
                <span class="notification-dropdown__subtitle">The system has been successfully upgraded. Read more
                  here.</span>
              </div>
            </a>
          </li>
          <li>
            <a href="##">
              <div class="notification-dropdown-icon danger">
                <i data-feather="info" aria-hidden="true"></i>
              </div>
              <div class="notification-dropdown-text">
                <span class="notification-dropdown__title">The cache is full!</span>
                <span class="notification-dropdown__subtitle">Unnecessary caches take up a lot of memory space and
                  interfere ...</span>
              </div>
            </a>
          </li>
          <li>
            <a href="##">
              <div class="notification-dropdown-icon info">
                <i data-feather="check" aria-hidden="true"></i>
              </div>
              <div class="notification-dropdown-text">
                <span class="notification-dropdown__title">New Subscriber here!</span>
                <span class="notification-dropdown__subtitle">A new subscriber has subscribed.</span>
              </div>
            </a>
          </li>
          <li>
            <a class="link-to-page" href="##">Go to Notifications page</a>
          </li>
        </ul>
      </div>
      <div class="nav-user-wrapper">
        <button href="##" class="nav-user-btn dropdown-btn" title="My profile" type="button">
          <span class="sr-only">My profile</span>
          <span class="nav-user-img">
            <picture><source srcset="./img/avatar/avatar-illustrated-02.webp" type="image/webp"><img src="./img/avatar/avatar-illustrated-02.png" alt="User name"></picture>
          </span>
        </button>
        <ul class="users-item-dropdown nav-user-dropdown dropdown">
          <li><a href="./perfiles/myPerfil.php">
              <i data-feather="user" aria-hidden="true"></i>
              <span>Profile</span>
            </a></li>
          <li><a href="##">
              <i data-feather="settings" aria-hidden="true"></i>
              <span>Account settings</span>
            </a></li>
          <li><a class="danger" href="./cerrar-sesion/logout.php">
              <i data-feather="log-out" aria-hidden="true"></i>
              <span>Log out</span>
            </a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">
        <div class="info-principal">
          <h2 class="main-title">Dashboard</h2>

          <div class="search-wrapper busqueda">
          <i data-feather="search" aria-hidden="true"></i>
          <input type="text" id="" name="" required placeholder="Buscar.." required>
          </div>
        </div>

    <h1 class="main-title" style="font-size: 2.2rem ;">Historial de Facturas</h1>
    <?php
    $directorio = 'facturas';

    if (is_dir($directorio)) {
        $archivos = array_diff(scandir($directorio), array('.', '..'));
        $facturas = [];

        foreach ($archivos as $archivo) {
            if (pathinfo($archivo, PATHINFO_EXTENSION) === 'pdf') {
                $ruta_archivo = "$directorio/$archivo";
                $fecha_creacion = date("d-m-Y H:i:s", filemtime($ruta_archivo));
                $facturas[] = [
                    'nombre' => $archivo,
                    'fecha' => $fecha_creacion,
                    'ruta' => $ruta_archivo
                ];
            }
        }

        if (count($facturas) > 0) {
            echo "<table class='factura-tabla'>";
            echo "<thead><tr><th>Número de Factura</th><th>Fecha de Generación</th><th>Acciones</th></tr></thead><tbody>";
            foreach ($facturas as $factura) {
                echo "<tr>";
                echo "<td>{$factura['nombre']}</td>";
                echo "<td>{$factura['fecha']}</td>";
                echo "<td><a class'accion-boton' href='{$factura['ruta']}' target='_blank'>Ver / Descargar</a>
                 <form action='' method='POST' style='display:inline;'>
                            <input type='hidden' name='archivo_a_borrar' value='{$factura['ruta']}'>
                            <button type='submit' class='accion-boton'>Borrar</button>
                        </form></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='mensaje main-title'>Aún no hay facturas.</p>";
        }
    } else {
        echo "<p class='mensaje'>No se encontró la carpeta de facturas.</p>";

    }

     // Manejar la eliminación de archivos
     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archivo_a_borrar'])) {
      $archivo_a_borrar = $_POST['archivo_a_borrar'];
      if (file_exists($archivo_a_borrar)) {
          unlink($archivo_a_borrar);
          echo "<p class='mensaje'>Factura borrada correctamente.</p>";
          echo "<script>setTimeout(() => { window.location.reload(); }, 1500);</script>";
      } 
  } 
    ?>
        
      </div>
    </main>
  </div>
</div>
<!-- Chart library -->
<script src="./plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="plugins/feather.min.js"></script>
<!-- Custom scripts -->
<script src="js/script.js"></script>
</body>
</html>

<style>
    h1 {
            text-align: center;
            color: #333;
            margin: 20px 0 50px 0;
        }

        .factura-tabla {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .factura-tabla th, .factura-tabla td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .factura-tabla th {
            background-color: #007BFF;
            color: white;
        }

        .darkmode .factura-tabla th {
          background-color:rgb(39, 39, 49);
        }
        
        .mensaje {
            text-align: center;
            font-size: 18px;
            color: #555;
        }

        .darkmode table tr{
        background-color: #363648;
    }
    .darkmode table{
      color: white;
    }

    td a{
      background-color: #22bb33;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      text-decoration: none;
      border-radius: 3px;
    }
    
    td a:hover{
      color: white;

    }

    .accion-boton {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 10px;
            cursor: pointer;
            text-decoration: none;
            border-radius: 3px;
        }
        .accion-boton:hover {
            background-color: #ff3333;
        }
</style>