<?php
// obtener_contadores.php - Archivo para actualizar los contadores de facturadores

// Iniciar sesión si no está iniciada
session_start();

// Verificar si el usuario tiene permisos (opcional)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Conexión a la base de datos
require_once '../conexion/conexion-BillBot.php';

// Obtener contador de facturadores en línea
$stmt = $con->prepare("SELECT COUNT(*) as online FROM facturadores WHERE rol = 'Facturador' AND session_activa = 1");
$stmt->execute();
$resultOnline = $stmt->get_result();
$online = $resultOnline->fetch_assoc()['online'];

// Obtener contador total de facturadores
$stmt = $con->prepare("SELECT COUNT(*) as total FROM facturadores WHERE rol = 'Facturador'");
$stmt->execute();
$resultTotal = $stmt->get_result();
$total = $resultTotal->fetch_assoc()['total'];

// Devolver los datos en formato JSON
echo json_encode([
    'online' => $online,
    'total' => $total
]);

// Cerrar conexión
$con->close();
?>