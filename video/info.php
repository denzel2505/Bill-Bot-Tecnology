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
  <script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
      <div class="container container-2">
        <div class="info-principal" style="margin: 0; display: block;" >

          <!--Info Que es bill bot-->
          <div class="info-1">
            <h2 class="main-title" style="font-size: 1.8rem; margin: 0;">¿Qué es Bill Bot?</h2>
            <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea eveniet inventore necessitatibus aut ullam minima ipsum voluptatem optio in expedita, voluptates quibusdam iure repellat nobis vero saepe. Ullam, reiciendis itaque. Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium retur molestiae facilis officia excepturi aliquid eum unde modi cumque praesentium! Itaque, quasi. </p>
            <center>    
              <video width="700px" autoplay controls src="../video/video1.mp4"></video>
              <track default kind="captions" src="captions.vtt" />
          </center>
          </div>

          <!--Info Como funciona-->
          <div class="info-2">
            <h2 class="main-title " style="font-size: 1.8rem; margin: 0; ">¿Cómo funciona?</h2>
            <p> Lorem ipsum dolor sit amet consectetur adipisicing elit. Ea eveniet inventore necessitatibus aut ullam minima ipsum voluptatem optio in expedita, voluptates quibusdam iure repellat nobis vero saepe. Ullam, reiciendis itaque. Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium retur molestiae facilis officia excepturi aliquid eum unde modi cumque praesentium! Itaque, quasi. </p>
            <center>    
              <video width="700px" autoplay controls src="../video/video2.mp4"></video>
              <track default kind="captions" src="captions.vtt" />
          </center>
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

  .info-principal{
    justify-content: center;
    align-items: center;
  }

  .info-principal video{
    margin: 50px 0;
    border-radius: 10px;
  }

  .info-1,.info-2{
    margin: 30px 0;
  }

  main .container .admin-user-photo{
    background-color: gray;
    border-radius: 50%;
    margin: 15px;
    padding: 10px;
  }

  main .container .info-principal{
    background-size:cover;
    background-repeat: no-repeat;
    background-position: center;
    padding: 20px;
    border-radius: 25px 25px 0 0;
  }

  .darkmode main .container .info-principal{
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
    margin: 10px 0;
  }

  main .container .info-admin p{
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 15px;
  }
  .darkmode .info-principal ,main .container .info-admin p{
    color: white;
    text-align: justify;
  }

  .darkmode .editar-btn{
    background-color:rgb(49, 62, 92);
    color: white;
  }

  .darkmode .contenido-edit-2 p{
    color: white;
  }


  
  </style>