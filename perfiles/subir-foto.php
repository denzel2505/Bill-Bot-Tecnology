<?php
    include('../conexion/conexion-BillBot.php');

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('Error al subir archivo')</script>";
        include('myPerfil.php');
        exit();
    }

   /* $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];*/
    $imagen = $_FILES['image'];

    $target_dir = '../img/perfil/';
    $file_name = basename($imagen['name']);
    $target_file = $target_dir . $file_name;

    if (!move_uploaded_file($imagen['tmp_name'], $target_file)) {
        echo "Error al mover el archivo.";
        exit();
    }
    $imagen_path = $target_file;

    //$sql = "INSERT INTO usuarios (url) VALUES (?)";
    $query = "UPDATE usuarios SET url = (?)";
    
    /*$sql = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', usuario = '$usuario', correo = '$correo'";

    $consulta = mysqli_query($con, $sql);*/

    $stmt = $con->prepare($query);
    if ($stmt->execute([$imagen_path])) {
        header('Location: ./myPerfil.php');
        exit();
    } else {
        echo "Error al subir los datos a la base de datos";
    }
?>