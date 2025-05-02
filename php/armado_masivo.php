<?php
require('../libs/fpdf.php'); // Librería PDF

// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'clinica');
if ($conexion->connect_error) {
    die("❌ Error de conexión: " . $conexion->connect_error);
}

// Consultar facturas
$sql = "SELECT numero_de_factura, fecha, descripcion, EPS, servicio, valor FROM facturas";
$resultado = $conexion->query($sql);

if ($resultado->num_rows === 0) {
    die("⚠️ No hay facturas disponibles.");
}

// Crear carpeta temporal si no existe
$carpetaTemporal = "pdf_temp/";
if (!is_dir($carpetaTemporal)) {
    mkdir($carpetaTemporal);
}

// 1️⃣ Generar los PDFs
while ($factura = $resultado->fetch_assoc()) {
    $pdf = new FPDF();

    
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, "Factura: " . $factura['numero_de_factura'], 0, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    foreach ($factura as $campo => $valor) {
        $pdf->Cell(0, 8, ucfirst(str_replace('_', ' ', $campo)) . ": " . $valor, 0, 1);
    }

    $nombrePDF = $carpetaTemporal . "factura_" . $factura['numero_de_factura'] . ".pdf";
    $pdf->Output('F', $nombrePDF); // Guardar PDF
}

// 2️⃣ Crear el ZIP
$nombreZIP = "facturas_" . date("Ymd_His") . ".zip";
$zip = new ZipArchive();

if ($zip->open($nombreZIP, ZipArchive::CREATE) === TRUE) {
    foreach (glob($carpetaTemporal . "*.pdf") as $archivoPDF) {
        $zip->addFile($archivoPDF, basename($archivoPDF)); // Añadir PDFs
    }
    $zip->close();

    // 3️⃣ Eliminar PDFs individuales
    foreach (glob($carpetaTemporal . "*.pdf") as $archivoPDF) {
        unlink($archivoPDF);
    }
    rmdir($carpetaTemporal);

    // 4️⃣ Descargar ZIP
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $nombreZIP . '"');
    readfile($nombreZIP);
    unlink($nombreZIP); // Borrar ZIP después de descarga
} else {
    echo "❌ No se pudo crear el archivo ZIP.";
}
?>
