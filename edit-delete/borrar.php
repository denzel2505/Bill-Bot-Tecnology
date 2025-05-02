<?php
    include("../conexion/conexion-BillBot.php");

    $id = $_GET['id'];

    $sql = "DELETE FROM usuarios2 WHERE id='$id'";

    $consulta = mysqli_query($con,$sql);

    header("location: ../perfiles/funcionarios.php");



?>