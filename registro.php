<?php
session_start();
require 'db.php'; // Conexión a la base de datos.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['contrasena'];
    $tipo = $_POST['tipo'];

    // Encripta la contraseña
    echo "Password al registrar: " . $password;
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - InProMatch</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <h1>InProMatch</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="login.php">Iniciar sesión</a></li>
            </ul>
        </nav>
    </header>
    <h2 id="Registro">Registro de Usuario</h2>
    <section id="Registro">
    <form action="registro.php" method="POST" enctype="multipart/form-data">

        <div class="form-group">
        <label for="tipo">Tipo de Usuario:</label>
        <select id="tipo" name="tipo" required>
            <option value="estudiante">Estudiante</option>
            <option value="empresa">Empresa</option>
        </select>
        </div>
        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <label for="foto">Fotografía (opcional):</label>
        <input type="file" id="foto" name="foto" accept="image/*">

        <label for="carrera">Carrera (solo estudiantes):</label>
        <input type="text" id="carrera" name="carrera">

        <label for="universidad">Universidad (solo estudiantes):</label>
        <input type="text" id="universidad" name="universidad">

        <button type="submit">Registrarse</button>
        
    </form>
    </section>
        <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
        </footer>
        <script src="js/script.js"></script>
</body>
</html>
