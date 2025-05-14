<?php
$host = 'localhost';
$dbname = 'inpromatch'; // Nombre de tu base de datos
$username = 'root';         // Tu usuario de MySQL
$password = '';             // Tu contraseña de MySQL

try {
    // Crear conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configuración para manejar errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // En caso de error, mostrar mensaje
    die("Error de conexión: " . $e->getMessage());
}
?>
