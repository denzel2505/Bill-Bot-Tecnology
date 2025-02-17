<?php

include("../conexion-BillBot.php");

$id=$_POST["id"];
$rol = $_POST['rol'];


$sql="UPDATE usuarios2 SET rol='$rol' WHERE id='$id'";
$query = mysqli_query($con, $sql);

if($query){
    Header("Location: ../perfiles/funcionarios.php");
}else{

}

?>