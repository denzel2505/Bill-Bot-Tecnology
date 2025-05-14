<?php
include '../conexion/init.php'; // Iniciar la sesión

echo "<h2>Contenido de la sesión</h2>";

if (!empty($_SESSION)) {
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "La sesión está vacía.";
}
?>
