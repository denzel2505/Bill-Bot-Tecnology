<?php
include '../conexion/init.php'; // Iniciar la sesión
require '../conexion/conexion-BillBot.php'; // Conexión a la base de datos

/*QUERY PARA FOTO DE PERFIL */
$sql = "SELECT * FROM administrador";
$query2 = mysqli_query($con, $sql);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $numero_factura = $_POST["numero_factura"] ?? null;
  $fecha_emision = $_POST["fecha_emision"] ?? null;
  $servicios = $_POST["servicios"] ?? null;
  $nombre_eps = $_POST["nombre_eps"] ?? null;
  $estado = $_POST["estado"] ?? null;
  $total_formato = $_POST["total_formato"] ?? null;
  //otros datos
  $nombre_paciente = $_POST["nombre_paciente"] ?? null;
  $documento_identidad = $_POST["documento_identidad"] ?? null;
  $sexo = $_POST["sexo"] ?? null;
  $edad = $_POST["edad"] ?? null;

  error_reporting(E_ALL & ~E_NOTICE);  
}

if (!isset($_SESSION['correo'])) {
  header("Location: ../ingreso.php");
  exit;
}

$correo = $_SESSION['correo'];

// Verificar si la sesión está activa en la base de datos
$query = "SELECT sesion_activa FROM administrador WHERE correo = ?";
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
  <?php include './sidebar/sidebar.php';?> <!-- Include the sidebar navigation -->
  <div class="main-wrapper">
    <!-- ! Main nav -->
    <?php include './navbar/navbar.php';?> <!-- Include the top navigation bar -->
    <!-- ! Main -->
    <main class="main users chart-page" id="skip-target">
      <div class="container">
        <div class="info-principal">
          <h2 class="main-title">Armado de cuentas</h2>
          <a href="../dashboard/busqueda.php">+ Crear</a>
        </div>

        


        <div class="invoice bg-gradient">
          <?php if (isset($numero_factura) && $numero_factura): 
                    $query = "
        SELECT * FROM vista_factura_completa 
        WHERE numero_factura = '" . mysqli_real_escape_string($con, $numero_factura) . "'
    ";
    $result = mysqli_query($con, $query);
    $factura_data = mysqli_fetch_assoc($result);

    if ($factura_data) {
        // Estructurar todos los datos en un array
        $historial = [
            'id_factura' => $factura_data['id_factura'],
            'numero_factura' => $factura_data['numero_factura'],
            'fecha_emision' => $factura_data['fecha_emision'],
            'fecha_emision_format' => $factura_data['fecha_emision_format'],
            'total' => $factura_data['total'],
            'total_formato' => $factura_data['total_formato'],
            'estado' => $factura_data['estado'],
            'paciente' => [
                'id_paciente' => $factura_data['id_paciente'],
                'nombre_completo' => $factura_data['nombre_paciente'],
                'documento_identidad' => $factura_data['documento_identidad'],
                'sexo' => $factura_data['sexo'],
                'edad' => $factura_data['edad']
            ],
            'eps' => [
                'nombre_eps' => $factura_data['nombre_eps']
            ],
            'servicios' => explode('; ', $factura_data['servicios']), // Convertir a array
            'items_detalle' => $factura_data['items_detalle'],
            //'medicamentos' => explode('; ', $factura_data['medicamentos']), // Convertir a array
            'totales' => [
                'total_servicios' => $factura_data['total_servicios'],
                'total_items' => $factura_data['total_items']
            ],
            'fecha_registro' => date('Y-m-d H:i:s')
        ];

        // Guardar en JSON (historial)
        $archivo = './historial-cuentas/historial_facturas.json';
        $datos_existentes = [];

        if (file_exists($archivo)) {
            $datos_existentes = json_decode(file_get_contents($archivo), true);
        }

        // Evitar duplicados (actualizar si existe)
        $encontrado = false;
        foreach ($datos_existentes as &$item) {
            if ($item['numero_factura'] === $historial['numero_factura']) {
                $item = $historial; // Actualizar
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $datos_existentes[] = $historial;
        }

        file_put_contents(
            $archivo, 
            json_encode($datos_existentes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // Opcional: Devolver el JSON como respuesta (para AJAX)
     
    } else {
        echo json_encode(['error' => 'Factura no encontrada']);
    }

            ?>
            

          <div class="contenido1">
              <p>Número de Factura: <?= htmlspecialchars($numero_factura) ?></p>
              <p>Fecha: <?= htmlspecialchars($fecha_emision) ?></p>
              <p>Descripción: <?= htmlspecialchars($servicios) ?></p>
              <p>EPS: <?= htmlspecialchars($nombre_eps) ?></p>
              <p>Nombre Paciente: <?= htmlspecialchars($nombre_paciente) ?></p>
              <p>Sexo: <?= htmlspecialchars($sexo) ?></p>
              <p>Edad: <?= htmlspecialchars($edad) ?></p>
              


          </div>

        <div class="contenido2">
            <p>Estado: <?= htmlspecialchars($estado) ?></p>
            <p>Valor: <?= htmlspecialchars(  $total_formato) ?></p>

            <div class="edit-delete">
              <!-- FORMULARIO para generar factura -->
              <form id="formFactura" action="../php/generar_pdf.php" method="POST">
                <input type="hidden" name="numero_factura" value="<?= htmlspecialchars($numero_factura) ?>">
                <input type="hidden" name="fecha_emision" value="<?= htmlspecialchars($fecha_emision) ?>">
                <input type="hidden" name="servicios" value="<?= htmlspecialchars($servicios) ?>">
                <input type="hidden" name="nombre_eps" value="<?= htmlspecialchars($nombre_eps) ?>">
                <input type="hidden" name="estado" value="<?= htmlspecialchars($estado) ?>">
                <input type="hidden" name="total_formato" value="<?= htmlspecialchars($total_formato) ?>">
                <input type="hidden" name="nombre_paciente" value="<?= htmlspecialchars($nombre_paciente) ?>">
                <input type="hidden" name="documento_identidad" value="<?= htmlspecialchars($documento_identidad) ?>">
                <input type="hidden" name="sexo" value="<?= htmlspecialchars($sexo) ?>">
                <input type="hidden" name="edad" value="<?= htmlspecialchars($edad) ?>">
                
                
                <div class="acciones-btn" >

                  <button type="submit" class="btn-edit" title="Armar Factura">
                  <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2m4 -14h6m-6 4h6m-2 4h2"/>
                  </svg>
                </button>

                <!--<a href="../php/ordenar/reordenar.php?archivo=factura_FA50491_CAJACOPI.pdf">
                  
                </a>-->

                <a href="../php/ordenar/reordenar.php?archivo=factura_<?php echo $numero_factura . '_' . strtoupper(str_replace(' ', '', $nombre_eps)); ?>.pdf">
                  <svg  xmlns="http://www.w3.org/2000/svg"  width="34"  height="34"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-edit"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg> 
                </a>
                  
                </div>
              </form>

              
              <!-- MODAL -->
                <div id="modalFactura" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;">
                  <div class="modal-content" style="position:absolute; top: 20%; right: 50%; transform: translate(50%,-50%);  background:#fff; margin:15% auto; padding:20px; width:300px; border-radius:10px; text-align:center;">
                  <span onclick="cerrarModal()" style=" float: right; cursor:pointer; font-size:20px; color:black;">&times;</span>
                  <svg style="display: flex; margin: 25px auto; text-align:center" xmlns="http://www.w3.org/2000/svg"  width="64"  height="64"  viewBox="0 0 24 24"  fill="#28a745"  class="icon icon-tabler icons-tabler-filled icon-tabler-circle-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336zm-1.293 5.953a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z" /></svg>
                  <h3>Factura generada exitosamente</h3>
                  <a id="descargarPDF" href="#" target="_blank" style="background:#28a745; padding:10px 20px; border-radius:5px; color:#fff; text-decoration:none; margin: 20px auto;">Descargar PDF</a>

                   <a href="../php/ordenar/reordenar.php?archivo=factura_<?php echo $numero_factura . '_' . strtoupper(str_replace(' ', '', $nombre_eps)); ?>.pdf">
                  Editar
                </a>
                </div>

                
                </div>
              </div>
                  <!-- SUGERENCIA -->
                  <div class="tooltip-container">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="#ebb944"  class="icon icon-tabler icons-tabler-filled icon-tabler-alert-triangle"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 1.67c.955 0 1.845 .467 2.39 1.247l.105 .16l8.114 13.548a2.914 2.914 0 0 1 -2.307 4.363l-.195 .008h-16.225a2.914 2.914 0 0 1 -2.582 -4.2l.099 -.185l8.11 -13.538a2.914 2.914 0 0 1 2.491 -1.403zm.01 13.33l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007zm-.01 -7a1 1 0 0 0 -.993 .883l-.007 .117v4l.007 .117a1 1 0 0 0 1.986 0l.007 -.117v-4l-.007 -.117a1 1 0 0 0 -.993 -.883z" /></svg>
                    <div class="tooltip-left">
                      Para editar una factura, primero debes armarla en el icono de factura.
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
      </div>
    </div>
  </body>
</html>

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




/*********************************SUGERENCIA ARMADO************************* */
.tooltip-container {
  position: relative;
  display: inline-block;
  display: flex;
  margin-left: auto;
}

.tooltip-left {
  position: absolute;
  left: auto;
  right: calc(100% + 10px); /* Espacio entre ícono y tooltip */
  top: 50%;
  transform: translateY(-50%);
  background-color:rgb(42, 97, 248);
  color: #fff;
  padding: 10px 12px;
  border-radius: 5px;
  font-size: 14px;
  width: max-content;
  max-width: 220px;
  white-space: normal;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease-in-out;
  z-index: 1000;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.tooltip-container:hover .tooltip-left {
  opacity: 1;
  visibility: visible;
}

.tooltip-left::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 100%;
  transform: translateY(-50%);
  border-width: 6px;
  border-style: solid;
  border-color: transparent transparent transparent rgb(42, 97, 248);
}

.darkmode .tooltip-left {
    background-color:rgb(39, 39, 49);
  }


.darkmode .tooltip-left::after {
  content: "";
  position: absolute;
  top: 50%;
  left: 100%;
  transform: translateY(-50%);
  border-width: 6px;
  border-style: solid;
  border-color: transparent transparent transparent rgb(39, 39, 49);
}







  /*BOTON ARMADOR */
  .edit-delete{
    width: 0;
    padding: 0;
    justify-content: end;
    flex-direction: row;
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
  
  .contenido2 p{
    text-align: end;
  }

  .edit-delete{
    display: flex;
    width: 30%;
  }
  .acciones-btn{
    justify-content: flex-start;
    display: flex;
    background-color: red;
  }
  .formFactura{
    width: 100%;
    justify-content: flex-start;
    background-color: yellow;
    padding: 30px;
  }
  



</style>
