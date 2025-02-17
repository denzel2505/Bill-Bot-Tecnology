<?php
require('libs/fpdf.php');

// Verifica si se envían los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_factura = htmlspecialchars($_POST['numero_factura']);
    $fecha = htmlspecialchars($_POST['fecha']);
    $descripcion = htmlspecialchars($_POST['descripcion']);
    $eps = htmlspecialchars($_POST['eps']);
    $servicio = htmlspecialchars($_POST['servicio']);
    $valor = htmlspecialchars($_POST['valor']);
    $paciente = htmlspecialchars($_POST['paciente'] ?? 'Andres Felipe Rodriguez');
    $documento = htmlspecialchars($_POST['documento'] ?? 'CC 10023932');
    $telefono = htmlspecialchars($_POST['telefono'] ?? '320 2123221');
    $sexo = htmlspecialchars($_POST['sexo'] ?? 'M');

    // Crear una instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Logotipo según EPS
    if ($eps == 'CAJACOPI') {
        $pdf->Image('./img/logos/caja.png', 10, 10, 40);
    } elseif ($eps == 'MUTUALSER') {
        $pdf->Image('./img/logos/mutualser-logo.png', 10, 10, 40);
    } elseif ($eps == 'SALUD TOTAL') {
        $pdf->Image('./img/logos/salud-total.png', 10, 10, 40);
    } elseif ($eps == 'NUEVA EPS') {
        $pdf->Image('./img/logos/nueva-eps.png', 10, 10, 40);
    } elseif ($eps == 'COOSALUD') {
        $pdf->Image('./img/logos/coosalud.png', 10, 10, 40);
    }

    // Título principal
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 15, 'Factura Electronica', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, 'EPS: ' . $eps, 0, 1, 'C');

    // Línea divisoria
    $pdf->Ln(5);
    $pdf->SetDrawColor(50, 50, 50);
    $pdf->Line(10, 50, 200, 50);

    // Datos generales
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Datos Generales', 0, 1);

    $pdf->SetFont('Arial', '', 11);
    $pdf->Cell(0, 8, 'Numero de Factura: ' . $numero_factura, 0, 1);
    $pdf->Cell(0, 8, 'Fecha: ' . $fecha, 0, 1);
    $pdf->Cell(0, 8, 'Paciente: ' . $paciente, 0, 1);
    $pdf->Cell(0, 8, 'Documento: ' . $documento, 0, 1);
    $pdf->Cell(0, 8, 'Sexo: ' . $sexo, 0, 1);
    $pdf->Cell(0, 8, 'Telefono: ' . $telefono, 0, 1);

    // Descripción del servicio
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Descripcion del Servicio', 0, 1);

    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 8, $descripcion);

    $pdf->Ln(5);
    $pdf->Cell(0, 8, 'Servicio: ' . $servicio, 0, 1);
    $pdf->Cell(0, 8, 'Valor: $' . $valor, 0, 1);

    // Firma ficticia
    $pdf->Ln(20);
    $pdf->Cell(0, 8, '__________________________', 0, 1, 'C');
    $pdf->Cell(0, 8, 'Firma Responsable', 0, 1, 'C');

    // Guardar el archivo PDF en el servidor
    $nombre_archivo = 'factura_' . $numero_factura . '_' . $eps . '.pdf';
    $ruta_guardado = 'facturas/' . $nombre_archivo; // Carpeta 'facturas'
    if (!file_exists('facturas')) {
        mkdir('facturas', 0777, true); // Crea la carpeta si no existe
    }

    $pdf->Output('F', $ruta_guardado); // Guarda el archivo PDF en la ruta especificada

    // Redirigir al usuario o mostrar un mensaje de éxito
    echo "<p>Factura generada exitosamente. <a href='$ruta_guardado'>Descargar PDF</a></p>";
}
?>
