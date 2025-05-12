<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="x-ua-compatible" content="ie=edge" />
  <title>Ingreso</title>
  <link rel="stylesheet" href="./css/registro.css">
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <!-- Favicon -->
  <link rel="shortcut icon" href="./img/bot2.ico" type="image/x-icon">


  <style>
    *{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      text-decoration:none;
      list-style: none
    }    
  </style>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- MDB icon -->
  <link rel="icon" href="img/mdb-favicon.ico" type="image/x-icon" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css" />
  <!-- Google Fonts Roboto -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" />
  <!-- MDB -->
  <link rel="stylesheet" href="./css/bootstrap-login-form.min.css" />
</head>

<body>
  <!-- Start your project here-->
  
  <a href="./index.html" class="back-home d-flex position-absolute align-items-center" style="margin: 30px;" >
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" width="44" height="44" stroke-width="2">
    <path d="M5 12l14 0"></path>
    <path d="M5 12l4 4"></path>
    <path d="M5 12l4 -4"></path>
  </svg>
  <h1 style="font-size: 1.5rem; color: #fff;" >Regresar</h1>
  </a>

  <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-8">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="imagen col-md-6 col-lg-5 d-none d-md-block">
                <!-- <img
                  src="/img/WhatsApp Image 2024-09-18 at 10.29.12 AM.jpeg"
                  alt="login form"
                  class="imagen img-fluid" style="border-radius: 1rem 0 0 1rem;"
                /> -->
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-5 p-lg-6 text-black">
  
                  <form action="./validar.php" method="post">
  
                    <div class="d-flex align-items-center mb-3 pb-1">
                      <!-- <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i> -->
                      <span class="h4 fw-bold mb-0">INICIAR SESION</span>
                    </div>
  
                    <!-- <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5> -->
  
                    <div class="form-outline mb-3">
                      <input type="email" name="correo" id="form2Example17" class="form-control form-control-lg" placeholder="Correo"/>
                      <!-- <label class="form-label" for="form2Example17">Email address</label> -->
                    </div>
  
                    <div class="form-outline mb-4">
                      <input type="password" name="contraseña" id="form2Example27" class="form-control form-control-lg" placeholder="Contraseña"/>
                      <!-- <label class="form-label" for="form2Example27">Password</label> -->
                    </div>

                    <div class="pt-1 mb-4">
                      <button class="btn form-control btn-dark btn-lg btn-block"  type="submit">Login</button>
                    </div>
  
                    <center><a class="small text-muted" href="./forgot_password.php">Olvidaste tu contraseña?</a>
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">No tienes cuenta? <a href="registro.php" style="color: #393f81;">Registrate aquí</a></p>
                    <a href="#!" class="small text-muted">Terminos de uso.</a>
                    <a href="#!" class="small text-muted">Politica privada</a>
                    <a href="#!" class="small text-muted">Bill Bot Technology</a></center>
                  </form>
  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <!-- End your project here-->

  <!-- MDB -->
  <script type="text/javascript" src="js/mdb.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <!-- Custom scripts -->
  <script type="text/javascript"></script>

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

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
  body{
    height: 100vh;         /* Usa toda la altura visible */
    width: 100vw; 
    background-image: url('./img/fondos/fondo.avif');
    background-repeat: no-repeat;
    background-size: cover;  /* Evita distorsión, pero puede dejar espacios vacíos */
    background-position: center;
    image-rendering: crisp-edges;      /* Mejora bordes en algunas imágenes */
    image-rendering: -webkit-optimize-contrast; /* Mejora en WebKit (Chrome, Safari) */
  }

  @media (max-width: 768px) {
    body {
      background-image: url('mobile.webp'); /* Versión optimizada para móviles */
    }
  }
  .imagen{
    background-image: url("./img/bot.jpg");
    border-radius: 1rem 0 0 1rem;
    background-color: #0042f7;
    background-position: center center;
    background-repeat: no-repeat;
    
}

  .btn{
    background-color: #0042f7;
  }

  .btn:hover{
    background-color: #0035f7;
  }
</style>