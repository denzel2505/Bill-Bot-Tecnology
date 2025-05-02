<?php
include("./conexion/conexion-BillBot.php");
$correo=$_POST['correo'];
$contraseña=$_POST['contraseña'];
session_start();
$_SESSION['correo']=$correo;

$con=mysqli_connect("localhost","root","","bill_bot");

$consulta="SELECT*FROM usuarios where correo='$correo'and contraseña='$contraseña'";


$resultado=mysqli_query($con,$consulta);

$filas=mysqli_num_rows($resultado);

  
if($filas){
    $updateQuery = "UPDATE usuarios SET sesion_activa = 1 WHERE correo = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("s", $correo);
    $updateStmt->execute();
    $updateStmt->close();  
  
    header("location: ./dashboard/home.php");

}else{
    ?>
    <?php
    include("./ingreso.php");

  ?>
  <h1 class="bad"><script>alert("Datos Incorrectos")</script></h1>
  <?php
}

mysqli_free_result($resultado);
mysqli_close($con);

?>
