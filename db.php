<?php
$host = 'sql305.infinityfree.com';
$dbname = 'if0_38995162_inpromatch'; // Nombre de tu base de datos
$username = 'if0_38995162';         // Tu usuario de MySQL
$password = 'inpromatch';             // Tu contraseña de MySQL

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
