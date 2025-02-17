<?php
include("conexion-BillBot.php");
$correo=$_POST['correo'];
$contraseña=$_POST['contraseña'];
session_start();
$_SESSION['correo']=$correo;

$con=mysqli_connect("localhost","root","","bill_bot");

$consulta="SELECT*FROM usuarios where correo='$correo'and contraseña='$contraseña'";

$consulta2="SELECT*FROM usuarios2 where correo='$correo'and contraseña='$contraseña'";



$resultado=mysqli_query($con,$consulta);
$resultado2=mysqli_query($con,$consulta2);

$filas=mysqli_num_rows($resultado);
$filas2=mysqli_num_rows($resultado2);

if($filas){
    $updateQuery = "UPDATE usuarios SET sesion_activa = 1 WHERE correo = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("s", $correo);
    $updateStmt->execute();
    $updateStmt->close();  
  
    header("location:home.php");

}elseif($filas2){
  header("location: ./dashboard-2/home-2.php");
}else{
    ?>
    <?php
    include("ingreso.php");

  ?>
  <h1 class="bad"><script>alert("Datos Incorrectos")</script></h1>
  <?php
}

$stmt->close();
mysqli_free_result($resultado);
mysqli_close($con);


?>
