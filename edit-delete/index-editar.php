<?php
    include("../conexion-BillBot.php");

    $id=$_GET['id'];

    $sql = "SELECT * FROM usuarios2 where id='$id'";

    $consulta = mysqli_query($con,$sql);

    $row = mysqli_fetch_array($consulta)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/estilo.css">
</head>
<body>
<form action="editar.php"  method="post">

        <div class="mb-3">
            <input
                type="hidden"
                class="form-control"
                name="id"
                id="id"
                aria-describedby="helpId"
                placeholder="Edite su nombre"
                value="<?= $row['id'] ?>"
            />
        </div>

        <div class="mb-3">
            <label for="" class="form-label">Nombre</label>
            
            <select  name="rol" id="">
                
                <option value="Facturador">Facturador</option>
                <option value="Administrador">Administrador</option>
            </select>
            
            <!--<input
                type="text"
                class="form-control"
                name="rol"
                id="nombre"
                aria-describedby="helpId"
                placeholder="Edite su nombre"
                value="<?= $row['rol'] ?>"
            />
        </div>-->
        <button type="submit" class="btn btn-success">Actualizar datos</button>
    </form>
    
</body>
</html>