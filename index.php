<?php
session_start();

// Redirigir a los paneles correspondientes si el usuario ya está logueado
if (isset($_SESSION["usuario_id"])) {
    if ($_SESSION["tipo"] === "empresa") {
        header("Location: dashboard_empresa.php");
        exit();
    } elseif ($_SESSION["tipo"] === "estudiante") {
        header("Location: dashboard_estudiante.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InProMatch - Plataforma de Pasantías y Horas Sociales</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Enlace al archivo CSS -->
    <script src="js/script.js"></script> <!-- Enlace al archivo JS -->
</head>
<body>

    <header>
        <div class="logo">
            <h1>InProMatch</h1>
            <p>Conecta empresas con estudiantes para pasantías y horas sociales</p>
        </div>
        <nav>
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#ofertas">Ofertas</a></li>
                <li><a href="login.php">Iniciar sesión</a></li>
                <li><a href="registro.html">Registrarse</a></li>
            </ul>
        </nav>
    </header>

    <section id="inicio">
        <h2>Bienvenido a InProMatch</h2>
        <p>Una plataforma para conectar empresas con estudiantes en busca de pasantías y horas sociales.</p>
    </section>

    <section id="ofertas">
        <h2>Ofertas de Pasantías</h2>
        <p>Explora las ofertas de diferentes empresas o postúlate a las que más te interesen.</p>
        <button><a href="registro.html">Regístrate para Postularte</a></button>
    </section>

    <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
