<?php


$con = mysqli_connect("localhost","root","","bill_bot");
$sql = "SELECT * FROM facturadores";
$query = mysqli_query($con, $sql);

$sql2 = "SELECT * FROM administrador";
$query2 = mysqli_query($con, $sql2);

session_start();
if (!isset($_SESSION['correo'])) {
  header("Location: ../ingreso.php");
  exit;
}

$correo = $_SESSION['correo'];
// Obtener el rol del usuario actual
$queryRol = "SELECT rol FROM facturadores WHERE correo = ?";
$stmtRol = $con->prepare($queryRol);
$stmtRol->bind_param("s", $correo);
$stmtRol->execute();
$stmtRol->bind_result($rol_usuario);
$stmtRol->fetch();
$stmtRol->close();
// Verificar si la sesión está activa en la base de datos
$qury = "SELECT sesion_activa FROM administrador WHERE correo = ?";
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
  <title>Gestion de perfil | Bill Bot</title>
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
  <?php include '../dashboard/sidebar/sidebar.php';?> <!-- Include the sidebar navigation -->
  <div class="main-wrapper">
    <!-- ! Main nav -->
    <?php include '../dashboard/navbar/navbar.php';?> <!-- Include the main navigation -->
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">
        <div class="info-principal">
          <h2 class="main-title">Funcionarios Facturadores</h2>
        </div>
          <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Asignar Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($query)): ?>
                    <tr>
                        <th><?= $row['id'] ?></th>
                        <th><?= $row['nombre'] ?></th>
                        <th><?= $row['usuario'] ?></th>
                        <th><?= $row['correo'] ?></th>
                        <th><?= $row['rol'] ?></th>
                        
                        <form action="../edit-delete/editar.php"  method="post">  
                        <div class="mb-3">
                          <input
                              type="hidden"
                              class="form-control"
                              name="id"
                              id="id"
                              aria-describedby="helpId"
                              placeholder="Edite su nombre"
                              value="<?= $row['id'] ?>"
                          />
                        </div>

                        <th><select name="rol" id="rol">
                        <option selected disabled >Selecciona una opción</option>
                              <option value="Facturador">Facturador</option>
                              <option value="Administrador">Administrador</option>
                          </select></th>

                        <th><button title="Asignar Rol" style="background-color: transparent;" onclick="alert('¿Deseas Asignar Este Rol?')" type="submit" class="btn btn-success"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#14A44D"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg></th>
                        </button>
                      </form>

                        <th><a onclick="alert('¿Deseas Eliminar Este Usuario?')" title="Eliminar Usuario" href="../edit-delete/borrar.php?id=<?= $row['id'] ?>" class="users-table--delete" ><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="#DC4C64"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-trash-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7h16" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /><path d="M10 12l4 4m0 -4l-4 4" /></svg></a></th>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
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
    .darkmode table{
        color: white;
    }
    .darkmode table tr{
        background-color: #363648;
    }

    table{
        margin: auto;
    }

    table tr {
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    }

    table th{
        padding: 16px;
        text-align: center;
        font-size: .85em;
    }

    table tr th{
        padding: 20px;
    }

    select{
      border-radius: 5px ;
      border: 0;
      outline: 0;
      padding: 5px;
      background-color: transparent;
    }

    .darkmode select{
      color: white;
    }

    .darkmode option{
      background-color: #363648;
    }

</style>