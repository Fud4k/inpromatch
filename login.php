<?php

session_start();
require 'db.php'; // Incluye el archivo de conexión a la base de datos.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // Consulta para verificar el correo en la base de datos
    $sql = "SELECT id, tipo, nombre, password FROM usuarios WHERE correo = :correo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":correo" => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["password"])) {
        // Si las credenciales son correctas, guarda la sesión
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["tipo"] = $usuario["tipo"];
        $_SESSION["nombre"] = $usuario["nombre"];
        // Redirige según el tipo de usuario
        if ($usuario["tipo"] == "estudiante") {
            header("Location: dashboard_estudiante.php");
        } else {
            header("Location: dashboard_empresa.php");
        }
        exit();
    } else {
        echo '<br><div class="alert alert-danger">Error al ingresar, vuelve a intentarlo</div>';
        //echo "Credenciales incorrectas. <a href='login.php'>Intentar de nuevo</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - InProMatch</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="views/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="views/bower_components/font-awesome/css/font-awesome.min.css">
</head>
<body>

    <header>
        <div class="logo">
            <h1>InProMatch</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="registro.php">Registro</a></li>
            </ul>
        </nav>
    </header>

    <section id="login">
        <h2>Iniciar sesión</h2>
        <form action="login.php" method="POST" id="loginForm">
            <label for="correoLogin">Correo Electrónico:</label>
            <input type="email" id="correoLogin" name="correo" required>

            <label for="passwordLogin">Contraseña:</label>
            <input type="password" id="passwordLogin" name="password" required>

            <button type="submit">Iniciar sesión</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </section>

    <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
