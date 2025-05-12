<?php
// Versión corregida y mejorada de procesar_facturador.php

// Habilitar la visualización de errores para diagnóstico (quitar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está iniciada
session_start();

// Establecer encabezados para JSON (importante)
header('Content-Type: application/json');

// Verificar si el usuario tiene permisos (ajustar según tu sistema de autenticación)
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos para realizar esta acción']);
    exit;
}

// Conexión a la base de datos (ajustar según tu configuración)
require_once '../conexion/conexion-BillBot.php'; // Asegúrate de que este archivo exista y contenga tu conexión a la BD

// Registrar la solicitud para diagnóstico
$request_data = [
    'get' => $_GET,
    'post' => $_POST,
    'file' => __FILE__,
];
error_log('Solicitud recibida: ' . print_r($request_data, true));

// Obtener la acción a realizar
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

// Función para validar datos
function validarDatos($datos) {
    $errores = [];
    
    // Validar nombre
    if (empty($datos['nombre'])) {
        $errores[] = 'El nombre es obligatorio';
    }
    
    // Validar usuario
    if (empty($datos['usuario'])) {
        $errores[] = 'El nombre de usuario es obligatorio';
    } elseif (strlen($datos['usuario']) < 4) {
        $errores[] = 'El nombre de usuario debe tener al menos 4 caracteres';
    }
    
    // Validar correo
    if (empty($datos['correo'])) {
        $errores[] = 'El correo electrónico es obligatorio';
    } elseif (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electrónico no tiene un formato válido';
    }
    
    return $errores;
}

// Switch para las diferentes acciones
try {
    switch ($accion) {
        case 'agregar':
            // Validar datos obligatorios
            if (empty($_POST['nombre']) || empty($_POST['usuario']) || empty($_POST['correo']) || empty($_POST['password'])) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                exit;
            }
            
            // Validar datos
            $errores = validarDatos($_POST);
            if (count($errores) > 0) {
                echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
                exit;
            }
            
            // Verificar si el usuario ya existe
            $stmt = $con->prepare("SELECT id FROM facturadores WHERE usuario = ? OR correo = ?");
            $stmt->bind_param("ss", $_POST['usuario'], $_POST['correo']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'El usuario o correo ya existe en el sistema']);
                exit;
            }
            
            // Encriptar contraseña
            $password_hash = $_POST['password'];
            
            // Insertar nuevo facturador
            $stmt = $con->prepare("INSERT INTO facturadores (nombre, usuario, correo, contraseña, rol, ultima_actividad) VALUES (?, ?, ?, ?, 'Facturador', NOW())");
            $stmt->bind_param("ssss", $_POST['nombre'], $_POST['usuario'], $_POST['correo'], $password_hash);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Facturador agregado correctamente']);
            } else {
                throw new Exception('Error al agregar el facturador: ' . $stmt->error);
            }
            break;
            
        case 'actualizar':
            // Verificar ID
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID de facturador inválido']);
                exit;
            }
            
            $id = intval($_GET['id']);
            error_log('ID a actualizar: ' . $id);
            
            // Verificar primero que el facturador exista
            $check = $con->prepare("SELECT id FROM facturadores WHERE id = ? AND rol = 'Facturador'");
            $check->bind_param("i", $id);
            $check->execute();
            $check_result = $check->get_result();
            
            if ($check_result->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Facturador no encontrado']);
                exit;
            }
            
            // Validar datos
            $errores = validarDatos($_POST);
            if (count($errores) > 0) {
                echo json_encode(['success' => false, 'message' => implode(', ', $errores)]);
                exit;
            }
            
            // Verificar si el usuario/correo ya existe (excepto para el mismo ID)
            $stmt = $con->prepare("SELECT id FROM facturadores WHERE (usuario = ? OR correo = ?) AND id != ?");
            $stmt->bind_param("ssi", $_POST['usuario'], $_POST['correo'], $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                echo json_encode(['success' => false, 'message' => 'El usuario o correo ya existe en otro facturador']);
                exit;
            }
            
            // Preparar actualización
            if (!empty($_POST['password'])) {
                // Con nueva contraseña
                $password_hash = $_POST['password'];
                $stmt = $con->prepare("UPDATE facturadores SET nombre = ?, usuario = ?, correo = ?, contraseña = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $_POST['nombre'], $_POST['usuario'], $_POST['correo'], $password_hash, $id);
            } else {
                // Sin cambiar contraseña
                $stmt = $con->prepare("UPDATE facturadores SET nombre = ?, usuario = ?, correo = ? WHERE id = ?");
                $stmt->bind_param("sssi", $_POST['nombre'], $_POST['usuario'], $_POST['correo'], $id);
            }
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Facturador actualizado correctamente']);
            } else {
                throw new Exception('Error al actualizar el facturador: ' . $stmt->error);
            }
            break;
            
        case 'eliminar':
            // Verificar ID
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'ID de facturador inválido']);
                exit;
            }
            
            $id = intval($_GET['id']);
            
            // Verificar si tiene registros asociados (ajustar según tu estructura de base de datos)
            // Por ejemplo, si los facturadores tienen facturas asociadas:
            $stmt = $con->prepare("SELECT COUNT(*) as total FROM facturas WHERE id_facturador = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if ($row['total'] > 0) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'No se puede eliminar este facturador porque tiene facturas asociadas'
                ]);
                exit;
            }
            
            // Eliminar facturador
            $stmt = $con->prepare("DELETE FROM facturadores WHERE id = ? AND rol = 'Facturador'");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Facturador eliminado correctamente']);
            } else {
                throw new Exception('Error al eliminar el facturador: ' . $stmt->error);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
            break;
    }
} catch (Exception $e) {
    error_log('Error en procesar_facturador.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}

// Cerrar conexión
$con->close();
?>