<?php
session_start();
require '../conexion/conexion-BillBot.php'; // Conexión a la base de datos

/*QUERY PARA FOTO DE PERFIL */
$sql = "SELECT * FROM usuarios";
$query2 = mysqli_query($con, $sql);


if (!isset($_SESSION['correo'])) {
    header("Location: ../ingreso.php");
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
  <title>Dashboard Funcionario Administrador</title>
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
  <?php
      include("../dashboard/sidebar/sidebar.php");
  ?>
  
  <div class="main-wrapper">
    <!-- ! Main nav -->
    <?php
      include("../dashboard/navbar/navbar.php");
    ?>
    
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">
        
        <div class="info-principal">
          <h2 class="main-title">Dashboard</h2>

          <div class="search-wrapper busqueda">
          <i data-feather="search" aria-hidden="true"></i>
          <input title="Filtrar Facturas" type="text" id="buscadorFacturas" placeholder="Buscar.." required>
          </div>
        </div>
        

        


    

    
          <?php
          $directorio = '../php/facturas';
          $mensaje = '';
          
          // Manejo de la eliminación de una sola factura
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['archivo_a_borrar'])) {
              $archivo_a_borrar = $_POST['archivo_a_borrar'];
              if (file_exists($archivo_a_borrar)) {
                  unlink($archivo_a_borrar);
                  $mensaje = "Factura borrada correctamente.";
              }
          }
          
          // Manejo de la eliminación de todas las facturas
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrar_todas'])) {
              $archivos = glob("$directorio/*.pdf");
              $borrados = 0;
          
              foreach ($archivos as $archivo) {
                  if (unlink($archivo)) {
                      $borrados++;
                  }
              }
          
              $mensaje = "{$borrados} factura(s) borrada(s) correctamente.";
          }
          
          // Mostrar historial de facturas
          
          echo "<h1 class='main-title' style='font-size: 2.2rem;'>Historial de Facturas</h1>";
          
          if (!empty($mensaje)) {
              echo "<p class='mensaje' id='mensajeBorrado'>{$mensaje}</p>";
              echo "<script>setTimeout(() => { document.getElementById('mensajeBorrado').style.display = 'none'; }, 3000);</script>";
          }
          
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
                  echo "<form action='' method='POST' style='margin-bottom: 1rem;' onsubmit='return confirmarBorradoTodas();'>
                          <button type='submit' name='borrar_todas' class='accion-boton' style='background-color: red; color: white; padding: 8px 16px; border-radius: 4px;'>Borrar Todas las Facturas</button>
                        </form>";
          
                  echo "<table id='tablaFacturas' class='factura-tabla'  style='overflow: auto;'>";
                  echo "<thead><tr><th>Número de Factura</th><th>Fecha de Generación</th><th>Acciones</th></tr></thead><tbody>";
          
                  foreach ($facturas as $factura) {
                      echo "<tr>";
                      echo "<td>{$factura['nombre']}</td>";
                      echo "<td>{$factura['fecha']}</td>";
                      echo "<td>
                              <a class='accion-boton' href='{$factura['ruta']}' target='_blank' style='background-color: green; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none;'>Descargar</a>
                              <form action='' method='POST' style='display:inline;' onsubmit='return confirmarBorradoIndividual();'>
                                  <input type='hidden' name='archivo_a_borrar' value='{$factura['ruta']}'>
                                  <button type='submit' class='accion-boton' style='background-color: red; color: white; padding: 6px 12px; border-radius: 4px; margin-left: 4px;'>Borrar</button>
                              </form>
                            </td>";
                      echo "</tr>";
                  }
          
                  echo "</tbody></table>";
              } else {
                  echo "<p class='mensaje main-title'>Aún no hay facturas.</p>";
              }
          } else {
              echo "<p class='mensaje'>No se encontró la carpeta de facturas.</p>";
          }
          ?>
        
        
        


      </div>
    </main>
  </div>
</div>
<!-- Chart library -->
<script src="../plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="../plugins/feather.min.js"></script>
<!-- Custom scripts -->
<script src="../js/script.js">
function confirmarBorradoTodas() {
    return confirm('¿Estás seguro de que deseas borrar todas las facturas? Esta acción no se puede deshacer.');
}

function confirmarBorradoIndividual() {
    return confirm('¿Deseas borrar esta factura?');
}

</script>

<script type="text/javascript">
  /* FILTRO DE FACTURAS */
  document.addEventListener("DOMContentLoaded", function () {
    const inputBusqueda = document.getElementById("buscadorFacturas");
    const tabla = document.getElementById("tablaFacturas").getElementsByTagName("tbody")[0];

    inputBusqueda.addEventListener("keyup", function () {
      const filtro = inputBusqueda.value.toLowerCase();

      for (let fila of tabla.rows) {
        let textoFila = fila.innerText.toLowerCase();
        fila.style.display = textoFila.includes(filtro) ? "" : "none";
      }
    });
  });










  /*********************CHAT BOT *********************/
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



        @media screen and (max-width: 991px) {
            .factura-tabla .accion-boton {
              display: block;
                margin-top: 10px;
                margin-bottom: 10px;
            }
          
        }
</style>