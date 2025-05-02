<?php
$con = mysqli_connect("localhost","root","","bill_bot");
$sql = "SELECT * FROM usuarios";
$query = mysqli_query($con, $sql);

session_start();
if (!isset($_SESSION['correo'])) {
  header("Location: ../ingreso.php");
  exit;
}

$correo = $_SESSION['correo'];

// Verificar si la sesión está activa en la base de datos
$qury = "SELECT sesion_activa FROM usuarios WHERE correo = ?";
$stmt = $con->prepare($qury);
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($sesion_activa);
$stmt->fetch();
$stmt->close();

if ($sesion_activa == 0) {
  session_unset();
  session_destroy();
  header("Location: ../ingreso.php");
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
  <title>Elegant Dashboard | Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Favicon -->
  <link rel="shortcut icon" href="../img/bot2.ico" type="image/x-icon">
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
        <a href="mailto:montesdenzel25@gmail.com?subject=Solicitud%20de%20Soporte%20Tecnico" target='_blank'  class="sidebar-user">
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
        <button href="##" class="nav-user-btn dropdown-btn" title="Mi Perfil" type="button">
          <span class="sr-only">Mi Perfil</span>
          <span class="nav-user-img">
            <picture><?php while ($row = mysqli_fetch_array($query)): ?>
            <img width="300px" src="<?=$row['url']?>" type="image/*">
          <?php  ?></picture>
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
        <div class="info-principal" style="margin: 0;">
          <h2 class="main-title">Mi perfil</h2>
        </div>
      
        

        <div class="info-admin">
          <div class="cambiar-foto">
              <div class="text-center mb-4">
                  <h2 style="font-size: 2.2rem;" class="main-title" >Hola, <?= $row['nombre'] ?></h2>
              </div>

              <div class="text-center mb-4">
                <div class="profile-picture-container">
                  <img src="<?=$row['url']?>" type="image/*" alt="Foto de perfil" class="rounded-circle" width="150" height="150" id="fotoPerfil">
                  
                  <svg class="bi bi-pencil edit-icon" id="editIcon" onclick="document.getElementById('profilePic').click();"  xmlns="http://www.w3.org/2000/svg"  width="44"  height="44"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                </div>

                <div class="mb-3 d-none" id="fileInputGroup">
                      <label>Cambiar foto de perfil</label>

                      <form action="../perfiles/subir-foto.php" method="post" enctype="multipart/form-data">
                        <input class="form-control mt-3 mb-3" id="profilePic"  type="file" name="image" accept="image/*"  placeholder="Cambiar foto">

                        <input class="cambiar-btn  d-flex" type="submit" value="Cambiar Foto">
                    </form>
                </div>
              </div>

            
              <!--<form action="../perfiles/subir-foto.php" method="post" enctype="multipart/form-data">
                <input   type="file" name="image" accept="image/*"  placeholder="Cambiar foto">

                <input class="cambiar-btn" type="submit" value="Cambiar Foto">
              </form>-->
          </div>



            <!-- <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
            <path d="M6 21v-2a4 4 0 0 1 4 -4h4"></path>
            <path d="M15 19l2 2l4 -4"></path>
            </svg><?= $row['usuario'] ?></p>
            
            <p><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24" height="24" stroke-width="2">
            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
            <path d="M3 7l9 6l9 -6"></path>
            </svg><?= $row['correo'] ?></p>
                          
            <p><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-crown"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 6l4 6l5 -4l-2 10h-14l-2 -10l5 4z" /></svg>Administrador</p>


           <form>
              <input type="text" placeholder="Nombre" value="Thomas D.">
              <input type="text" placeholder="Apellido" value="Hardison">
              <input type="email" placeholder="Correo Electrónico" value="thomashardison@dayrep.com">
              <input type="text" placeholder="Número de Contacto" value="661-724-7734">
              <input type="text" placeholder="Dirección" value="1368 Hayhurst Lane.">
              <input type="text" placeholder="Ciudad" value="McAllen">
              <input type="text" placeholder="Estado" value="New York">
              <input type="text" placeholder="Código Postal" value="11357">
              <input type="text" placeholder="País" value="United States">
              <input type="password" placeholder="Contraseña" value="********">
              <button type="submit">Guardar</button>
            </form>-->
            

            <div class="profile-box border rounded p-4 m-0">
                
                </form>

                <button class="btn mb-3 editar-perfil-btn" onclick="toggleEdit()">Editar Perfil</button>

                <form action="../perfiles/editar.php" method="post" id="profileForm" onsubmit="return confirmUpdate(event)" enctype="multipart/form-data">
                  <div class="row mb-3">
                    <div class="col">
                      <label>Nombre</label>
                      <input type="text" class="form-control" name="nombre" value="<?= $row['nombre'] ?>" disabled>
                    </div>
                    <div class="col">
                      <label>Apellido</label>
                      <input type="text" class="form-control" name="apellido" value="<?= $row['apellido'] ?>" disabled>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <div class="col">
                      <label>Usuario</label>
                      <input type="text" class="form-control" name="usuario" value="<?= $row['usuario'] ?>" disabled>
                    </div>
                    
                    <div class="col">
                      <label>Correo</label>
                      <input type="email" class="form-control" name="correo" id="email" value="<?= $row['correo'] ?>" disabled>
                    </div>
                  </div>
                  

                  <div class="row mb-3">
                    <div class="col">
                      <label>Ciudad</label>
                      <input type="text" class="form-control" id="ciudad" value="Cartagena" readonly>
                    </div>
                    <div class="col">
                      <label>País</label>
                      <input type="text" class="form-control" id="pais" value="Colombia" readonly>
                    </div>
                  </div>

                  <div class="text-end">              
                    <button type="submit" id="guardarBtn" class="btn d-none guardar-btn">Guardar Cambios</button>
                  </div>
                </form>
            </div>
        </div>          
            <?php endwhile; ?>
      </div>
    </main>
</div>
<!-- Chart library -->
<script src="../plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="../plugins/feather.min.js"></script>
<!-- Custom scripts -->
<script src="../js/script.js"></script>

<script type="text/javascript">
  //MENU DESPLEGABLE EDITAR PERFIL
  let isEditing = false;

  let editMode = false;
    
    
    function toggleEdit() {
      isEditing = !isEditing;
      editMode = !editMode;
      const editIcon = document.getElementById("editIcon");
      const formControls = document.querySelectorAll("#profileForm .form-control");
      const saveBtn = document.getElementById("guardarBtn");
      const fileInputGroup = document.getElementById("fileInputGroup");
      const guardarBtn = document.getElementById("guardarBtn");

  formControls.forEach(input => input.disabled = !isEditing);
  saveBtn.classList.toggle("d-none", !isEditing);
  fileInputGroup.classList.toggle("d-none", !isEditing); // ← Mostrar u ocultar el input file


    // Mostrar ícono de editar y campo subir foto
    editIcon.style.display = editMode ? "block" : "none";
    fileInputGroup.classList.toggle("d-none", !editMode);
}

  






  //******************CHAT BOT****************  */
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
  .profile-picture-container {
      position: relative;
      display: inline-block;
    }

    .edit-icon {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background-color: rgba(0,0,0,0.7);
      border-radius: 50%;
      padding: 8px;
      color: white;
      cursor: pointer;
      display: none; /* Oculto por defecto */
    }

    .edit-icon:hover {
      background-color: rgba(0,0,0,0.9);
    }

    #fileInputGroup {
      margin-top: 10px;
    }



  .profile-box {
      max-width: 700px;
      margin: auto;
      transition: all .2s ease-in-out;
    }

    .form-control{
      background-color:rgb(179, 179, 179);
    }

    .darkmode .form-control{
      background-color: #1a1a2e;
      color: white;
    }

    .form-control:disabled{
      background-color:rgb(179, 179, 179);
    }

    .darkmode .form-control:disabled {
      background-color: #1a1a2e;
      color: white;
    }








  
  main .container .admin-user-photo{
    background-color: gray;
    border-radius: 50%;
    margin: 15px;
    padding: 10px;
  }

  main .container .info-principal{
    background-image: url('../img/fondos/fondo-perfil.webp');
    background-size:cover;
    background-repeat: no-repeat;
    background-position: center;
    padding: 20px;
    border-radius: 25px 25px 0 0;
  }

  .darkmode main .container .info-principal{
    background-image: url('../img/fondos/fondo-perfil2.avif');
    background-size:cover;
    background-repeat: no-repeat;
    background-position: center;
    padding: 20px;
    border-radius: 25px 25px 0 0;
  }

  main .container h2{
    margin: 0 15px;
    text-transform: capitalize;
  }

  main .container .info-admin{
    display: flex;
    margin: 10px auto;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-evenly;
  }

  main .container .info-admin p{
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 15px;
  }
  .darkmode main .container .info-admin label{
    color: white;
  }

  input[type=file]::file-selector-button {
  margin-right: 20px;
  border: none;
  background: #0042f7 ;
  padding: 10px 20px;
  border-radius: 5px 0px 0px 5px;
  color: #fff;
  cursor: pointer;
  transition: background .2s ease-in-out;
}

.darkmode input[type=file]::file-selector-button {
  background:rgb(49, 62, 92);
}

.editar-perfil-btn{
  background-color: #198754;
  color: white;
  transition: background .2s ease-in-out;
}

.editar-perfil-btn:hover{
  background-color:rgb(15, 88, 54);
  color: white;
}


  .cambiar-foto{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  .cambiar-foto img{
    border-radius: 100%;
    width: 200px;
    height: 200px;
    object-fit:cover;
    margin: 5px 0;
  }

  .cambiar-btn, .guardar-btn{
    cursor:pointer; 
    background-color: #0042f7 ; 
    color: white; 
    padding: 10px;
  }

  .cambiar-btn:hover, .guardar-btn:hover{
    transition: background .2s ease-in-out;
    background-color:rgb(1, 50, 182) ;
    color: white;
  }

  .darkmode .cambiar-btn {
    background-color:rgb(49, 62, 92);
  }

  .darkmode .guardar-btn {
    background-color:rgb(49, 62, 92);
  }

  .darkmode .cambiar-btn:hover, .guardar-btn:hover{
    transition: background .2s ease-in-out;
    background-color:rgb(34, 43, 65);
    color: white;
  }


  .darkmode .editar-perfil-btn{
    background-color:rgb(31, 39, 58);
    color: white;
  }

  .darkmode .contenido-edit-2 p{
    color: white;
  }


  
  </style>