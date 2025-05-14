<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? 'PDF_reordenado.pdf';

    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo "Error al recibir el archivo.";
        exit;
    }

    $ruta = '../facturas/';
    if (!file_exists($ruta)) mkdir($ruta, 0777, true);

    $rutaFinal = $ruta . basename($nombre); // ← evitar inyecciones


    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaFinal)) {
        echo "Archivo guardado exitosamente como $nombre";
    } else {
        http_response_code(500);
        echo "Error al guardar el archivo.";
    }

    
}
