<?php
session_start();
require 'db.php'; // Conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $tipo = $_POST['tipo'];

    // Encripta la contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Inserta el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, correo, password, tipo) VALUES (:nombre, :correo, :password, :tipo)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':correo' => $correo,
        ':password' => $hashedPassword,
        ':tipo' => $tipo
    ]);

    // Redirige al login o al dashboard
    header("Location: login.php");
    exit();
}
?>

<!-- Formulario de registro -->
<form action="registro.php" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="correo">Correo Electrónico:</label>
    <input type="email" id="correo" name="correo" required>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required>

    <label for="tipo">Tipo:</label>
    <select id="tipo" name="tipo">
        <option value="estudiante">Estudiante</option>
        <option value="empresa">Empresa</option>
    </select>

    <button type="submit">Registrarse</button>
</form>
