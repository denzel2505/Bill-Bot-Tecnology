<?php
    include("../conexion/conexion-BillBot.php");

    $id = null;
    $nombre = $_POST['nombre'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];
    $rol = 'Facturador';

    $sql = "INSERT INTO usuarios2 values('$id','$nombre','$usuario','$correo','$contraseña','$rol')";

    $consulta = mysqli_query($con,$sql);

    if ($consulta) {
        echo "<script>alert('Registro Exitoso')</script>";
        header('location: ../ingreso.php');
    }
    else{
        echo "<script>alert('No se pudo realizar el registro')</script>";
        header('location: registro.php');
    }

?>