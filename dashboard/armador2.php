<?php
session_start();
require '../conexion/conexion-BillBot.php'; // Conexión a la base de datos

/*QUERY PARA FOTO DE PERFIL */
$sql = "SELECT * FROM usuarios";
$query2 = mysqli_query($con, $sql);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $numero_factura = $_POST["numero_de_factura"] ?? null;
  $fecha = $_POST["fecha"] ?? null;
  $descripcion = $_POST["descripcion"] ?? null;
  $eps = $_POST["EPS"] ?? null;
  $servicio = $_POST["servicio"] ?? null;
  $valor = $_POST["valor"] ?? null;

  error_reporting(E_ALL & ~E_NOTICE);  
}

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
  <title>Armado de Cuentas | Bill Bot</title>
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
      include("./sidebar/sidebar.php");
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
          <h2 class="main-title">Armado de cuentas</h2>
          <a href="../dashboard/busqueda.php">+ Crear</a>
        </div>

        


        <div class="invoice bg-gradient">
          <?php if (isset($numero_factura) && $numero_factura): ?>
          <div class="contenido1">
              <p>Número de Factura: <?= htmlspecialchars($numero_factura) ?></p>
              <p>Fecha: <?= htmlspecialchars($fecha) ?></p>
              <p>Descripción: <?= htmlspecialchars($descripcion) ?></p>
              <p>EPS: <?= htmlspecialchars($eps) ?></p>
          </div>

        <div class="contenido2">
            <p>Servicio: <?= htmlspecialchars($servicio) ?></p>
            <p>Valor: <?= htmlspecialchars('$' . $valor) ?></p>

            <div class="edit-delete">
              <!-- FORMULARIO para generar factura -->
              <form id="formFactura" action="../php/generar_pdf.php" method="POST">
                <input type="hidden" name="numero_factura" value="<?= htmlspecialchars($numero_factura) ?>">
                <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
                <input type="hidden" name="descripcion" value="<?= htmlspecialchars($descripcion) ?>">
                <input type="hidden" name="eps" value="<?= htmlspecialchars($eps) ?>">
                <input type="hidden" name="servicio" value="<?= htmlspecialchars($servicio) ?>">
                <input type="hidden" name="valor" value="<?= htmlspecialchars($valor) ?>">

                <button type="submit" class="btn-edit" title="Armar Factura">
                  <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2"/>
                  </svg>
                </button>
              </form>

              <!-- MODAL -->
                <div id="modalFactura" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;">
                  <div class="modal-content" style="position:absolute; top: 20%; right: 50%; transform: translate(50%,-50%);  background:#fff; margin:15% auto; padding:20px; width:300px; border-radius:10px; text-align:center;">
                  <span onclick="cerrarModal()" style=" float: right; cursor:pointer; font-size:20px; color:black;">&times;</span>
                  <svg style="display: flex; margin: 25px auto; text-align:center" xmlns="http://www.w3.org/2000/svg"  width="64"  height="64"  viewBox="0 0 24 24"  fill="#28a745"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                  <h3>Factura generada exitosamente</h3>
                  <a id="descargarPDF" href="#" target="_blank" style="background:#28a745; padding:10px 20px; border-radius:5px; color:#fff; text-decoration:none; margin: 20px auto;">Descargar PDF</a>
                  </div>
                </div>
              </div>
            </div>
            <?php else: ?>
                <p>No se enviaron datos.</p>
            <?php endif; ?>
          </div>
          

          <div class="row stat-cards" style="margin-top: 20px;">
          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item m-10">
              <div class="stat-cards-icon primary">
                <i data-feather="bar-chart-2" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total visits</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit success">
                    <i data-feather="trending-up" aria-hidden="true"></i>4.07%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>

          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
              <div class="stat-cards-icon warning">
                <i data-feather="file" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total visits</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit success">
                    <i data-feather="trending-up" aria-hidden="true"></i>0.24%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>
          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
              <div class="stat-cards-icon purple">
                <i data-feather="file" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total visits</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit danger">
                    <i data-feather="trending-down" aria-hidden="true"></i>1.64%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>
          <div class="col-md-6 col-xl-3">
            <article class="stat-cards-item">
              <div class="stat-cards-icon success">
                <i data-feather="feather" aria-hidden="true"></i>
              </div>
              <div class="stat-cards-info">
                <p class="stat-cards-info__num">1478 286</p>
                <p class="stat-cards-info__title">Total visits</p>
                <p class="stat-cards-info__progress">
                  <span class="stat-cards-info__profit warning">
                    <i data-feather="trending-up" aria-hidden="true"></i>0.00%
                  </span>
                  Last month
                </p>
              </div>
            </article>
          </div>
        </div>  
          
      </div>
            

        
            
    </main>

<!-- Chart library -->
<script src="../plugins/chart.min.js"></script>
<!-- Icons library -->
<script src="../plugins/feather.min.js"></script>
<!-- Custom scripts -->
<script src="../js/script.js"></script>
<script type="text/javascript">
  //MODAL DE EXITO
    const form = document.getElementById('formFactura');

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const formData = new FormData(form);

      fetch(form.action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        // Obtener el enlace al PDF desde la respuesta PHP
        const match = data.match(/href=["'](.*?)["']/);
        if (match && match[1]) {
          document.getElementById('descargarPDF').href = match[1];
          document.getElementById('modalFactura').style.display = 'block';
        } else {
          alert("Error: No se pudo obtener el PDF.");
        }
      })
      .catch(error => {
        console.error("Error:", error);
      });
    });

    function cerrarModal() {
      document.getElementById('modalFactura').style.display = 'none';
    }

    // También cerrar si hacen clic fuera del modal
    window.onclick = function(event) {
      const modal = document.getElementById('modalFactura');
      if (event.target == modal) {
        cerrarModal();
      }
    }
  










  //******************CHAT BOT**********************
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

   /* Estilo del modal */
   #modalFactura {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content span:hover{
      transform: rotate(90deg);
    }

    .modal-content {
      background-color: white;
      margin: 15% auto;
      padding: 20px;
      border-radius: 10px;
      width: 90%;
      max-width: 400px;
      text-align: center;
    }

    .modal-content a {
      display: inline-block;
      margin-top: 15px;
      background-color: #28a745;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }

    .modal-content a:hover {
      background-color: #218838;
    }

    .close-modal {
      float: right;
      font-size: 20px;
      cursor: pointer;
      color: #aaa;
    }

    .close-modal:hover {
      color: black;
    }













  /*BOTON ARMADOR */
  .edit-delete{
    width: 0;
    padding: 0;
  }

  form{
    padding: 0;
    width: 0;
  }

  form .btn-edit{
    background-color: transparent
  }

  form .btn-edit svg{
    padding: 0;
    margin: 0;
  }

  /*CONTENIDO DE FACTURA */
  .contenido1 p{
    margin: 15px 0;
  }





</style>
