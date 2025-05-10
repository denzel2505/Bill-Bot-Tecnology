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