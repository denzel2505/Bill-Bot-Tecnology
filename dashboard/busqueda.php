<?php
// Configuración de conexión a la base de datos
include('../conexion/conexion-BillBot.php');

$host = "localhost";
$dbname = "clinica";
$username = "root";
$password = "";

/*QUERY PARA FOTO DE PERFIL */
$sql = "SELECT * FROM usuarios";
$query2 = mysqli_query($con, $sql);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $factura = null;

    // Verificar si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $numero_factura = $_POST["numero_de_factura"];

        // Consulta preparada
        $stmt = $pdo->prepare("SELECT * FROM facturas WHERE numero_de_factura = :numero_de_factura");
        $stmt->bindParam(':numero_de_factura', $numero_factura, PDO::PARAM_STR);
        $stmt->execute();

        // Obtener resultado
        $factura = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Elegant Dashboard | Dashboard</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="../img/svg/logo.svg" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="../css/style.min.css">
  <link rel="stylesheet" href="../css/style.css">
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
            <a href="../dashboard/home.php" class="logo-wrapper" title="Home">
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
                            <a href="../perfiles/funcionarios.php">Ver Perfiles</a>
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
        <a href="mailto:montesdenzel25@gmail.com?subject=Solicitud%20de%20Soporte%20Tecnico" target='_blank' class="sidebar-user">
            <span class="sidebar-user-img">
                <picture class="bot"><source srcset="../img/bot.png" type="image/webp"><img src="../img/avatar/avatar-illustrated-01.png" alt="User name"></picture>
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
        <button href="##" class="nav-user-btn dropdown-btn" title="Mi Perfil" type="button">
          <span class="sr-only">Mi Perfil</span>
          <span class="nav-user-img">
            <picture><?php while ($row = mysqli_fetch_array($query2)): ?>
            <img width="300px" src="<?=$row['url']?>" type="image/*">
          <?php endwhile; ?></picture>
          </span>
        </button>
        <ul class="users-item-dropdown nav-user-dropdown dropdown">
          <li><a href="../perfiles/myPerfil.php">
              <i data-feather="user" aria-hidden="true"></i>
              <span>Perfil</span>
            </a></li>
          
          <li><a class="danger" href="../cerrar-sesion/logout.php">
              <i data-feather="log-out" aria-hidden="true"></i>
              <span>Cerrar Sesion</span>
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
          <h2 class="main-title">Busqueda de documentos</h2>
        </div>

        <h1 class="main-title">Buscar Factura</h1>
    <form action="busqueda.php" method="POST">
        <div class="search-wrapper busqueda">
        <i data-feather="search" aria-hidden="true"></i>
        <input type="text" id="numero_de_factura" name="numero_de_factura" required placeholder="Ingrese numero de factura" required>
        </div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($factura): ?>
        <div class="resultados">
          <h2 class="main-title">Resultados de busqueda:</h2>
          <p >Número de Factura: <?= htmlspecialchars($factura['numero_de_factura']) ?>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#14A44D" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
          <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95"></path>
          <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44"></path>
          <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92"></path>
          <path d="M8.56 20.31a9 9 0 0 0 3.44 .69"></path>
          <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95"></path>
          <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44"></path>
          <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92"></path>
          <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69"></path>
          <path d="M9 12l2 2l4 -4"></path>
        </svg></p>

          <p>Fecha: <?= htmlspecialchars($factura['fecha']) ?>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#14A44D" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
          <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95"></path>
          <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44"></path>
          <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92"></path>
          <path d="M8.56 20.31a9 9 0 0 0 3.44 .69"></path>
          <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95"></path>
          <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44"></path>
          <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92"></path>
          <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69"></path>
          <path d="M9 12l2 2l4 -4"></path>
        </svg></p>
          
        <p>Descripcion: <?= htmlspecialchars($factura['descripcion']) ?>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#14A44D" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
          <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95"></path>
          <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44"></path>
          <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92"></path>
          <path d="M8.56 20.31a9 9 0 0 0 3.44 .69"></path>
          <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95"></path>
          <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44"></path>
          <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92"></path>
          <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69"></path>
          <path d="M9 12l2 2l4 -4"></path>
        </svg></p>

          <p>EPS: <?= htmlspecialchars($factura['EPS']) ?>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#14A44D" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
          <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95"></path>
          <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44"></path>
          <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92"></path>
          <path d="M8.56 20.31a9 9 0 0 0 3.44 .69"></path>
          <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95"></path>
          <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44"></path>
          <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92"></path>
          <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69"></path>
          <path d="M9 12l2 2l4 -4"></path>
        </svg></p>
          
        <p>Servicio: <?= htmlspecialchars($factura['servicio']) ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#14A44D" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
          <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95"></path>
          <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44"></path>
          <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92"></path>
          <path d="M8.56 20.31a9 9 0 0 0 3.44 .69"></path>
          <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95"></path>
          <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44"></path>
          <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92"></path>
          <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69"></path>
          <path d="M9 12l2 2l4 -4"></path>
        </svg></p>
          
        <p>Valor: <?= htmlspecialchars($factura['valor']) ?>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#14A44D" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
          <path d="M8.56 3.69a9 9 0 0 0 -2.92 1.95"></path>
          <path d="M3.69 8.56a9 9 0 0 0 -.69 3.44"></path>
          <path d="M3.69 15.44a9 9 0 0 0 1.95 2.92"></path>
          <path d="M8.56 20.31a9 9 0 0 0 3.44 .69"></path>
          <path d="M15.44 20.31a9 9 0 0 0 2.92 -1.95"></path>
          <path d="M20.31 15.44a9 9 0 0 0 .69 -3.44"></path>
          <path d="M20.31 8.56a9 9 0 0 0 -1.95 -2.92"></path>
          <path d="M15.44 3.69a9 9 0 0 0 -3.44 -.69"></path>
          <path d="M9 12l2 2l4 -4"></path>
        </svg></p>
        </div>
        
        <!-- Formulario para enviar datos al armador -->
        <form action="armador2.php" method="POST">
            <input type="hidden" name="numero_de_factura" value="<?= htmlspecialchars($factura['numero_de_factura']) ?>">
            <input type="hidden" name="fecha" value="<?= htmlspecialchars($factura['fecha']) ?>">
            <input type="hidden" name="descripcion" value="<?= htmlspecialchars($factura['descripcion']) ?>">
            <input type="hidden" name="EPS" value="<?= htmlspecialchars($factura['EPS']) ?>">
            <input type="hidden" name="servicio" value="<?= htmlspecialchars($factura['servicio']) ?>">
            <input type="hidden" name="valor" value="<?= htmlspecialchars($factura['valor']) ?>">
            <button type="submit">Agregar al Armador</button>
        </form>
    <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <p class="error">No se encontró ninguna factura con ese número.</p>
    <?php endif; ?>
      </div>
    </main>
  </div>
</div>
<!-- Chart library -->
<script src="../plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="../plugins/feather.min.js"></script>
<!-- Custom scripts -->
<script src="../js/script.js"></script>

<script type="text/javascript">
  (function(d, t) {
      var v = d.createElement(t), s = d.getElementsByTagName(t)[0];
      v.onload = function() {
        window.voiceflow.chat.load({
          verify: { projectID: '67bbb0c7c97c50a0c1d20a51' },
          url: 'https://general-runtime.voiceflow.com',
          versionID: 'production', 
          voice: { 
            url: "https://runtime-api.voiceflow.com" 
          }
        });
      }
      v.src = "https://cdn.voiceflow.com/widget-next/bundle.mjs"; v.type = "text/javascript"; s.parentNode.insertBefore(v, s);
  })(document, 'script');
</script>
</body>
</html>


<style>
  .busqueda{
    display: inline;
    margin-right: 10px;
  }

  form button{
    padding: 10px;
    background-color: #0042f7;
    color: white;
    border-radius: 5px
  }

  .darkmode form button{
  background-color: #363648;
  }

  .darkmode form button:hover{
  background-color: #222235;
}


.resultados{
  margin: 20px 0;
  display: flex;
  flex-direction: column;
}

.resultados p{
  align-items: center;
  margin: 10px 0;
  font-weight: 500;
}

.darkmode .resultados p{
  color: white;
}

/*ERROR DE BUSQUEDA */
.container .error{
  text-align: center;
  margin: 50px 0;
  background-color:rgb(238, 102, 95);
  padding: 15px;
  border-radius: 10px;
  color: white;
}

.darkmode .container .error{
  background-color:rgb(207, 126, 122);
}

</style>