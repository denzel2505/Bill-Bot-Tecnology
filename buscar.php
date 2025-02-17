<?php
// Configuración de la conexión a la base de datos
$host = "localhost"; // Cambiar si no es localhost
$dbname = "clinica";
$username = "root"; // Cambiar si el usuario es diferente
$password = ""; // Cambiar si hay contraseña

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = $_POST["id"];

        // Consulta preparada
        $stmt = $pdo->prepare("SELECT * FROM facturas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Obtener resultados
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Mostrar resultados
            echo "<h1>Resultados:</h1>";
            echo "<p>ID: " . htmlspecialchars($usuario['numero_de_factura']) . "</p>";
            echo "<p>Nombre: " . htmlspecialchars($usuario['descripcion']) . "</p>";
            echo "<p>Email: " . htmlspecialchars($usuario['valor']) . "</p>";
        } else {
            echo "<p>No se encontró un usuario con ese ID.</p>";
        }
    }
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>
