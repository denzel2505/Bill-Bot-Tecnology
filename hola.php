<?php
session_start(); // Necesario para acceder a $_SESSION

echo "<h2>Contenido de la sesión</h2>";

if (!empty($_SESSION)) {
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "La sesión está vacía.";
}
?>
