<?php
session_start();
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
                        <h2 class="main-title">Revisión de cuentas</h2>
                        <a href="../dashboard/busqueda.php" class="btn btn-primary">+ Crear</a>
                    </div>

                    <div class="invoice-container">
                        <?php
                        $archivo = './historial-cuentas/historial_facturas.json';
                        if (file_exists($archivo)) {
                            $historial = json_decode(file_get_contents($archivo), true);
                            
                            // Verificar si $historial es un array
                            if (is_array($historial)) {
                                foreach ($historial as $factura) {
                                    // Verificar si la estructura de $factura es correcta
                                    if (is_array($factura)) {
                                        echo '<div class="invoice-card">';
                                        echo '<p><strong>Número de Factura:</strong> ' . htmlspecialchars($factura['numero_factura'] ?? 'N/A') . '</p>';
                                        echo '<p><strong>Fecha:</strong> ' . htmlspecialchars($factura['fecha_emision'] ?? 'N/A') . '</p>';
                                        
                                        // Mostrar EPS desde el array anidado si existe
                                        $nombre_eps = $factura['eps']['nombre_eps'] ?? ($factura['nombre_eps'] ?? 'N/A');
                                        echo '<p><strong>EPS:</strong> ' . htmlspecialchars($nombre_eps) . '</p>';
                                        
                                        // Mostrar datos del paciente si existen
                                        if (isset($factura['paciente']) && is_array($factura['paciente'])) {
                                            echo '<p><strong>Nombre Paciente:</strong> ' . htmlspecialchars($factura['paciente']['nombre_completo'] ?? 'N/A') . '</p>';
                                            echo '<p><strong>Sexo:</strong> ' . htmlspecialchars($factura['paciente']['sexo'] ?? 'N/A') . '</p>';
                                            echo '<p><strong>Edad:</strong> ' . htmlspecialchars($factura['paciente']['edad'] ?? 'N/A') . '</p>';
                                        }
                                        
                                        // Mostrar servicios si existen
                                        if (isset($factura['servicios']) && is_array($factura['servicios'])) {
                                            echo '<p><strong>Descripción:</strong> ' . htmlspecialchars(implode(', ', $factura['servicios'])) . '</p>';
                                        } elseif (isset($factura['servicios'])) {
                                            echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($factura['servicios']) . '</p>';
                                        }
                                        
                                        echo '<p><strong>Estado:</strong> ' . htmlspecialchars($factura['estado'] ?? 'N/A') . '</p>';
                                        echo '<p><strong>Valor:</strong> ' . htmlspecialchars($factura['total_formato'] ?? 'N/A') . '</p>';
                                        echo '</div>';
                                    }
                                }
                            } else {
                                echo "<p>El formato del archivo JSON no es válido.</p>";
                            }
                        } else {
                            echo "<p>No hay facturas en el historial.</p>";
                        }
                        ?>
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





    .invoice-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .invoice-card p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }
        .invoice-card p strong {
            color: #2c3e50;
        }
        .info-principal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
