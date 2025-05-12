<?php
require('../libs/fpdf.php');

// Verifica si se envían los datos necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_factura = htmlspecialchars($_POST['numero_factura']);
    $fecha_emision = htmlspecialchars($_POST['fecha_emision']);
    $nombre_paciente = htmlspecialchars($_POST['nombre_paciente']);
    $sexo = htmlspecialchars($_POST['sexo']);
    $edad = htmlspecialchars($_POST['edad']);
    $servicios = htmlspecialchars($_POST['servicios']);
    $nombre_eps = htmlspecialchars($_POST['nombre_eps']);
    $estado = htmlspecialchars($_POST['estado']);
    $total_formato = htmlspecialchars($_POST['total_formato']);

    

    // Crear una instancia de FPDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Logotipo según EPS
    if ($eps == 'CAJACOPI') {
        $pdf->Image('../img/logos/caja.png', 10, 10, 40);
    } elseif ($eps == 'MUTUALSER') {
        $pdf->Image('../img/logos/mutualser-logo.png', 10, 10, 40);
    } elseif ($eps == 'SALUD_TOTAL') {
        $pdf->Image('../img/logos/salud-total.png', 10, 10, 40);
    } elseif ($eps == 'NUEVA EPS') {
        $pdf->Image('../img/logos/nueva-eps.png', 10, 10, 40);
    } elseif ($eps == 'COOSALUD') {
        $pdf->Image('../img/logos/coosalud.png', 10, 10, 40);
    }

    // Título principal
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 15, 'Factura Electronica', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 8, 'EPS: ' . $nombre_eps, 0, 1, 'C');

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
    $pdf->Cell(0, 8, 'Fecha: ' . $fecha_emision, 0, 1);
    $pdf->Cell(0, 8, 'Paciente: ' . $nombre_paciente, 0, 1);

    $pdf->Cell(0, 8, 'Sexo: ' . $sexo, 0, 1);
    $pdf->Cell(0, 8, 'Edad: ' . $edad, 0, 1);

    // Descripción del servicio
    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Descripcion del Servicio', 0, 1);

    $pdf->SetFont('Arial', '', 11);
    $pdf->MultiCell(0, 8, $servicios);

    $pdf->Ln(5);
    $pdf->Cell(0, 8, 'Estado: ' . $estado, 0, 1);
    $pdf->Cell(0, 8, 'Valor: $' . $total_formato, 0, 1);

    // Firma ficticia
    $pdf->Ln(20);
    $pdf->Cell(0, 8, '__________________________', 0, 1, 'C');
    $pdf->Cell(0, 8, 'Firma Responsable', 0, 1, 'C');

        // --------------------- PÁGINA 2 - EPICRISIS ---------------------
        // Verificar si el servicio requiere Epicrisis
        if ($servicios == 'URGENCIAS' || $servicios == 'CIRUGIA') {
            // Añadir página
            $pdf->AddPage();

            // Fuente UTF-8 o codificación correcta
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, utf8_decode('EPICRISIS'), 0, 1, 'C');
            $pdf->Ln(5);

            // Subtítulo
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Datos del Paciente'), 0, 1);

            // Datos organizados en dos columnas
            $pdf->SetFont('Arial', '', 11);
            $pdf->Cell(50, 8, utf8_decode('Nombre completo:'));
            $pdf->Cell(0, 8, utf8_decode($nombre . ' ' . $apellido), 0, 1);

            $pdf->Cell(50, 8, utf8_decode('Tipo y Nº Documento:'));
            $pdf->Cell(0, 8, utf8_decode($documento), 0, 1);

            $pdf->Cell(50, 8, utf8_decode('EPS:'));
            $pdf->Cell(0, 8, utf8_decode($eps), 0, 1);

            $pdf->Cell(50, 8, utf8_decode('Servicio Médico:'));
            $pdf->Cell(0, 8, utf8_decode($servicio), 0, 1);
            $pdf->Ln(5);

            // Motivo de Ingreso
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Motivo de Ingreso:'), 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(0, 8, utf8_decode($motivo_ingreso));

            // Diagnóstico de Ingreso
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Diagnóstico de Ingreso:'), 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(0, 8, utf8_decode($diagnostico_ingreso));

            // Evolución Clínica
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Evolución Clínica:'), 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(0, 8, utf8_decode($evolucion_clinica));

            // Diagnóstico de Egreso
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Diagnóstico de Egreso:'), 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(0, 8, utf8_decode($diagnostico_egreso));

            // Tratamiento y Recomendaciones
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, utf8_decode('Tratamiento y Recomendaciones:'), 0, 1);
            $pdf->SetFont('Arial', '', 11);
            $pdf->MultiCell(0, 8, utf8_decode($tratamiento_recomendaciones));

            // Firma
            $pdf->Ln(15);
            $pdf->Cell(0, 8, utf8_decode('__________________________________'), 0, 1, 'C');
            $pdf->Cell(0, 8, utf8_decode('Médico Responsable'), 0, 1, 'C');
            $pdf->Cell(0, 8, utf8_decode('Firma y sello'), 0, 1, 'C');

        }

    


        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // ---------------- HOJA DE GASTOS ----------------
        $pdf->Cell(0, 10, utf8_decode("HOJA DE GASTOS"), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Concepto', 1, 0, 'C');
        $pdf->Cell(70, 10, 'Detalle', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Costo', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $gastos = [
            ['Consulta Especializada', 'Consulta prequirúrgica', 80000],
            ['Exámenes Diagnósticos', 'Hemograma, RX abdomen', 45000],
            ['Honorarios Médicos', 'Cirujano general', 150000],
            ['Material Quirúrgico', 'Suturas, guantes, gasas', 60000],
            ['Medicamentos', 'Antibióticos, analgésicos', 35000],
        ];

        foreach ($gastos as $g) {
            $pdf->Cell(50, 10, utf8_decode($g[0]), 1);
            $pdf->Cell(70, 10, utf8_decode($g[1]), 1);
            $pdf->Cell(40, 10, '$' . number_format($g[2], 0, ',', '.'), 1, 1);
        }

        $pdf->Cell(120, 10, 'Total:', 1);
        $pdf->Cell(40, 10, '$' . number_format(array_sum(array_column($gastos, 2)), 0, ',', '.'), 1, 1);

        $pdf->AddPage();

        // ---------------- PRESTACIÓN DE SERVICIO ----------------
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode("PRESTACIÓN DE SERVICIO"), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 10, utf8_decode("Nombre del paciente: $nombre $apellido"));
        $pdf->MultiCell(0, 10, utf8_decode("EPS: $eps"));
        $pdf->MultiCell(0, 10, utf8_decode("Servicio Médico: $servicio"));
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, utf8_decode("Detalle del Servicio"), 0, 1);

        $pdf->SetFont('Arial', '', 11);
        switch ($servicio) {
            case "CIRUGIA":
                $detalle = "Procedimiento: Apendicectomía laparoscópica\nDuración: 2 horas\nSala: Quirófano 2\nAnestesia: General\nComplicaciones: Ninguna";
                break;
            case "URGENCIAS":
                $detalle = "Ingreso por dolor torácico\nAtención médica inmediata\nExámenes diagnósticos\nEstabilización del paciente\nAlta médica";
                break;
            case "ODONTOLOGIA":
                $detalle = "Procedimiento: Extracción molar\nAnestesia local\nDuración: 45 minutos\nControl postoperatorio";
                break;
            case "GENERAL":
                $detalle = "Consulta general por síntomas gripales\nPrescripción de medicamentos\nRecomendaciones de reposo";
                break;
            default:
                $detalle = "Detalle del servicio no disponible.";
        }

        $pdf->MultiCell(0, 10, utf8_decode($detalle));

        $pdf->AddPage();

        // ---------------- REGISTRO DE MEDICAMENTOS ----------------
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, utf8_decode("REGISTRO DE MEDICAMENTOS"), 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Medicamento', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Dosis', 1, 0, 'C');
        $pdf->Cell(60, 10, 'Frecuencia', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 11);

        $meds = [];
        switch ($servicio) {
            case "CIRUGIA":
                $meds = [
                    ['Ceftriaxona', '1g IV', 'Cada 12 horas'],
                    ['Paracetamol', '500mg oral', 'Cada 8 horas'],
                    ['Omeprazol', '20mg oral', 'Cada 24 horas']
                ];
                break;
            case "URGENCIAS":
                $meds = [
                    ['Salbutamol', '100mcg inhalador', 'Cada 6 horas'],
                    ['Metamizol', '1g IM', 'Según dolor'],
                    ['Loratadina', '10mg oral', 'Cada 24 horas']
                ];
                break;
            case "ODONTOLOGIA":
                $meds = [
                    ['Ibuprofeno', '600mg oral', 'Cada 8 horas'],
                    ['Amoxicilina', '500mg oral', 'Cada 8 horas'],
                    ['Clorhexidina', 'Enjuague bucal', 'Cada 12 horas']
                ];
                break;
            case "GENERAL":
                $meds = [
                    ['Acetaminofén', '500mg oral', 'Cada 6 horas'],
                    ['Vitamina C', '1g oral', 'Cada 24 horas']
                ];
                break;
            default:
                $meds = [['No hay medicamentos registrados', '-', '-']];
        }

        foreach ($meds as $m) {
            $pdf->Cell(60, 10, utf8_decode($m[0]), 1);
            $pdf->Cell(60, 10, utf8_decode($m[1]), 1);
            $pdf->Cell(60, 10, utf8_decode($m[2]), 1, 1);
        }

        // Guardar o mostrar
       

    
        // --------------------- PÁGINA 6 - ADRES ---------------------
        // NUEVA PÁGINA: ADRES
        $pdf->AddPage();

        // Banner ADRES
        $pdf->Image('../img/logos/BannerAdres.jpg', 30, 10, 150); // Centrado

        $pdf->Ln(30);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('ADMINISTRADORA DE LOS RECURSOS DEL SISTEMA GENERAL DE SEGURIDAD SOCIAL EN SALUD - ADRES'), 0, 1, 'C');

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, utf8_decode('Información de afiliación en la Base de Datos Única de Afiliados – BDUA en el Sistema General de Seguridad Social en Salud'), 0, 1, 'C');

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 6, utf8_decode('Resultados de la consulta'), 0, 1, 'C');

        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 6, utf8_decode('Información Básica del Afiliado :'), 0, 1);

        // Tabla de información del afiliado
        $columnas = [
            'TIPO DE IDENTIFICACIÓN' => 'CC',
            'NÚMERO DE IDENTIFICACIÓN' => $documento,
            'NOMBRES' => $nombre,
            'APELLIDOS' => $apellido,
            'FECHA DE NACIMIENTO' => '**/**/****',
            'DEPARTAMENTO' => 'Bolívar',
            'MUNICIPIO' => 'Cartagena'
        ];

        // Cabecera de tabla
        $pdf->SetFillColor(44, 124, 181); // Azul tipo ADRES
        $pdf->SetTextColor(255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(95, 7, 'COLUMNAS', 1, 0, 'C', true);
        $pdf->Cell(95, 7, 'DATOS', 1, 1, 'C', true);

        // Filas
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(0);
        foreach ($columnas as $col => $dato) {
            $pdf->Cell(95, 7, utf8_decode($col), 1);
            $pdf->Cell(95, 7, utf8_decode($dato), 1, 1);
        }

        // Subtítulo
        $pdf->Ln(8);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(60, 6, utf8_decode('Datos de afiliación :'), 0, 1);

        // Tabla de afiliación - diseño corregido y proporcionado (suma total = 190 mm)
        $pdf->SetFillColor(44, 124, 181); // Azul tipo ADRES
        $pdf->SetTextColor(255);
        $pdf->SetFont('Arial', 'B', 9);

        // Ancho ajustado por columna
        $w_estado = 25;
        $w_entidad = 50;
        $w_regimen = 25;
        $w_afiliacion = 30;
        $w_finalizacion = 30;
        $w_tipo = 30;

        // Encabezados
        $pdf->Cell($w_estado, 7, 'ESTADO', 1, 0, 'C', true);
        $pdf->Cell($w_entidad, 7, 'ENTIDAD', 1, 0, 'C', true);
        $pdf->Cell($w_regimen, 7, 'REGIMEN', 1, 0, 'C', true);
        $pdf->Cell($w_afiliacion, 7, utf8_decode('FECHA DE AFILIACIÓN'), 1, 0, 'C', true);
        $pdf->Cell($w_finalizacion, 7, utf8_decode('FECHA DE FINALIZACIÓN'), 1, 0, 'C', true);
        $pdf->Cell($w_tipo, 7, utf8_decode('TIPO DE AFILIADO'), 1, 1, 'C', true);

        // Contenido
        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0);

        $pdf->Cell($w_estado, 10, 'ACTIVO', 1, 0, 'C');
        $pdf->Cell($w_entidad, 10, utf8_decode($eps), 1, 0, 'C');
        $pdf->Cell($w_regimen, 10, 'CONTRIBUTIVO', 1, 0, 'C');
        $pdf->Cell($w_afiliacion, 10, '01/12/2015', 1, 0, 'C');
        $pdf->Cell($w_finalizacion, 10, '31/03/2025', 1, 0, 'C');
        $pdf->Cell($w_tipo, 10, 'BENEFICIARIO', 1, 1, 'C');


       


            

    
    

    // Guardar el archivo PDF en el servidor
    $nombre_archivo = 'factura_' . $numero_factura . '_' . $nombre_eps . '.pdf';
    $ruta_guardado = 'facturas/' . $nombre_archivo; // Carpeta 'facturas'
    if (!file_exists('facturas')) {
        mkdir('facturas', 0777, true); // Crea la carpeta si no existe
    }

    $pdf->Output('F', $ruta_guardado); // Guarda el archivo PDF en la ruta especificada

    // Redirigir al usuario o mostrar un mensaje de éxito
    //echo "<p>Factura generada exitosamente. <a href='$ruta_guardado' target='_blank'>Descargar PDF</a></p>";


    $url_pdf = '../php/facturas/' . $nombre_archivo;

    echo "<p>Factura generada exitosamente. <a href='$url_pdf' target='_blank'>Descargar PDF</a></p>";

}
?>
