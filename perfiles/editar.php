<?php
    include('../conexion/conexion-BillBot.php');

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];

    
    //$sql = "INSERT INTO usuarios (url) VALUES (?)";
    $query = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', usuario = '$usuario', correo = '$correo' WHERE id = 8";

    $consulta = mysqli_query($con, $query);
    if ($consulta) {
        header('Location: ../perfiles/myPerfil.php');
        exit();
    } else {
        echo "Error al subir los datos a la base de datos";
    }
    
?>