<?php
// Configuración mejorada de conexiones con opciones adicionales
$config = [
    'sios' => [
        'host' => 'localhost',
        'dbname' => 'sios',
        'user' => 'root',
        'pass' => '',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
        ]
    ],
    'software' => [
        'host' => 'localhost',
        'dbname' => 'bill_bot',
        'user' => 'root',
        'pass' => '',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    ]
];

// Función para conectar a la base de datos con manejo de errores mejorado
function connectDB($config) {
    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        return new PDO($dsn, $config['user'], $config['pass'], $config['options']);
    } catch (PDOException $e) {
        die("<div class='error'><h2>Error de conexión:</h2><p>{$e->getMessage()}</p></div>");
    }
}

// Estilos CSS para la salida
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: #2ecc71; margin: 20px 0; }
    .error { color: #e74c3c; margin: 20px 0; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background-color: #f2f2f2; }
    tr:hover { background-color: #f5f5f5; }
    pre { background: #f8f9fa; padding: 15px; border-radius: 5px; }
    .json-data { white-space: pre-wrap; font-family: monospace; }
</style>";

try {
    // 1. Conectar a ambas bases de datos
    $pdo_sios = connectDB($config['sios']);
    $pdo_software = connectDB($config['software']);

    // 2. Verificar existencia de tablas necesarias
    $required_tables = ['facturas', 'pacientes', 'eps', 'prestacion_servicio', 'servicios', 'detalle_factura'];
    foreach ($required_tables as $table) {
        $stmt = $pdo_sios->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() == 0) {
            throw new Exception("La tabla '$table' no existe en la base de datos SIOS");
        }
    }

    // 3. Consulta SQL mejorada para la vista con DISTINCT y GROUP_CONCAT
    $sql_create_view = "
    CREATE OR REPLACE VIEW vista_factura_completa AS
    SELECT
        f.id_factura,
        f.numero_factura,
        DATE_FORMAT(f.fecha_emision, '%d/%m/%Y') as fecha_emision_format,
        f.fecha_emision,
        CONCAT('$', FORMAT(f.total, 0)) as total_formato,
        f.total,
        f.estado,
        p.id_paciente,
        p.nombre_completo AS nombre_paciente,
        CONCAT(p.tipo_doc, ' ', p.num_doc) as documento_identidad,
        p.sexo,
        TIMESTAMPDIFF(YEAR, p.fecha_nac, CURDATE()) as edad,
        e.nombre_eps,
        GROUP_CONCAT(DISTINCT s.nombre_servicio SEPARATOR '; ') AS servicios,
        GROUP_CONCAT(DISTINCT CONCAT(df.descripcion, ' (', df.cantidad, ' x $', FORMAT(df.valor_unitario, 0), ')') SEPARATOR '<br>') AS items_detalle,
        GROUP_CONCAT(DISTINCT rm.medicamento SEPARATOR '; ') AS medicamentos,
        COUNT(DISTINCT ps.id_prestacion) AS total_servicios,
        COUNT(DISTINCT df.id_detalle) AS total_items
    FROM {$config['sios']['dbname']}.facturas f
    JOIN {$config['sios']['dbname']}.pacientes p ON f.id_paciente = p.id_paciente
    JOIN {$config['sios']['dbname']}.eps e ON p.id_eps = e.id_eps
    LEFT JOIN {$config['sios']['dbname']}.prestacion_servicio ps ON ps.id_factura = f.id_factura
    LEFT JOIN {$config['sios']['dbname']}.servicios s ON ps.id_servicio = s.id_servicio
    LEFT JOIN {$config['sios']['dbname']}.detalle_factura df ON f.id_factura = df.id_factura
    LEFT JOIN {$config['sios']['dbname']}.registro_medicamentos rm ON rm.id_prestacion = ps.id_prestacion
    GROUP BY f.id_factura, p.id_paciente
    ";

    // 4. Crear la vista con transacción para mayor seguridad
    $pdo_software->beginTransaction();
    try {
        // Eliminar vista si existe (para evitar errores)
        $pdo_software->exec("DROP VIEW IF EXISTS vista_factura_completa");
        
        // Crear la nueva vista
        $pdo_software->exec($sql_create_view);
        $pdo_software->commit();
        
        echo "<div class='success'><h2>✅ Vista creada exitosamente en la base de datos del software.</h2></div>";
    } catch (Exception $e) {
        $pdo_software->rollBack();
        throw $e;
    }

    // 5. Mostrar resumen de facturas (mejorado)
    $stmt = $pdo_software->query("
        SELECT 
            numero_factura,
            fecha_emision_format as fecha,
            nombre_paciente,
            documento_identidad,
            nombre_eps,
            total_formato as total,
            estado,
            total_servicios,
            total_items
        FROM vista_factura_completa 
        ORDER BY fecha_emision DESC
        LIMIT 5
    ");
    
    if ($stmt->rowCount() > 0) {
        echo "<h3>📋 Últimas 5 facturas registradas:</h3>";
        echo "<table>";
        echo "<tr>
                <th>Factura</th>
                <th>Fecha</th>
                <th>Paciente</th>
                <th>Documento</th>
                <th>EPS</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Servicios</th>
                <th>Items</th>
              </tr>";
        
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>{$row['numero_factura']}</td>";
            echo "<td>{$row['fecha']}</td>";
            echo "<td>{$row['nombre_paciente']}</td>";
            echo "<td>{$row['documento_identidad']}</td>";
            echo "<td>{$row['nombre_eps']}</td>";
            echo "<td style='text-align:right;'>{$row['total']}</td>";
            echo "<td>{$row['estado']}</td>";
            echo "<td style='text-align:center;'>{$row['total_servicios']}</td>";
            echo "<td style='text-align:center;'>{$row['total_items']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='error'>No se encontraron facturas en la vista.</div>";
    }

    // 6. Mostrar detalle de ejemplo expandible
    $stmt_detail = $pdo_software->query("
        SELECT 
            numero_factura,
            nombre_paciente,
            servicios,
            items_detalle,
            medicamentos
        FROM vista_factura_completa 
        ORDER BY fecha_emision DESC
        LIMIT 3
    ");
    
    if ($stmt_detail->rowCount() > 0) {
        echo "<h3>📝 Detalle consolidado de facturas:</h3>";
        
        while ($row = $stmt_detail->fetch()) {
            echo "<div style='margin-bottom:30px; border:1px solid #eee; padding:15px; border-radius:5px;'>";
            echo "<h4>Factura: {$row['numero_factura']} - Paciente: {$row['nombre_paciente']}</h4>";
            
            echo "<p><strong>Servicios:</strong><br>{$row['servicios']}</p>";
            echo "<p><strong>Items:</strong><br>{$row['items_detalle']}</p>";
            
            if (!empty($row['medicamentos'])) {
                echo "<p><strong>Medicamentos:</strong><br>{$row['medicamentos']}</p>";
            }
            
            echo "</div>";
        }
    }

} catch (PDOException $e) {
    echo "<div class='error'><h2>❌ Error de base de datos:</h2>
          <p><strong>Mensaje:</strong> {$e->getMessage()}</p>
          <p><strong>Código:</strong> {$e->getCode()}</p>
          <p><strong>Archivo:</strong> {$e->getFile()}</p>
          <p><strong>Línea:</strong> {$e->getLine()}</p></div>";
} catch (Exception $e) {
    echo "<div class='error'><h2>❌ Error general:</h2><p>{$e->getMessage()}</p></div>";
}
?>