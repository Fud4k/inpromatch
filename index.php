<?php
session_start();
$loginError = '';
$mostrarModal = false;

if (isset($_SESSION["login_error"])) {
    $loginError = $_SESSION["login_error"];
    $mostrarModal = true;
    unset($_SESSION["login_error"]); // Elimina el mensaje para evitar mostrarlo otra vez
}

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
    <title>InProMatch - Plataforma de Pasantías</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <section id="BG">
        <aside class="sidebar2">
            <div class="sidebar-logo2">
                <img src="img/logo/logo.png" alt="Logo de InProMatch">
            </div>
            <h2>Bienvenido</h2>

            <!-- Menú lateral -->
            <a class="sidebar-btn2" href="#oferta">💼 Ofertas</a>
            <!-- Botón que abre el modal -->
            <a class="sidebar-btn2-log" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">🔐 Iniciar sesión</a>
            <a class="sidebar-btn2" href="registro.php">📝 Registrarse</a>
        </aside>

        <header>
            <div class="logo">
                <h1>InProMatch</h1>
                <p>Conecta empresas con estudiantes para pasantías y horas sociales</p>
            </div>
        </header>

        <section id="inicio">
            <h2>Bienvenido a InProMatch</h2>
            <p>Una plataforma para conectar empresas con estudiantes en busca de pasantías y horas sociales.</p>
        </section>

        <section id="oferta">
            <h2>Ofertas de Pasantías</h2>
            <p>Explora las ofertas de diferentes empresas o postúlate a las que más te interesen.</p>
            <button><a href="registro.php">Regístrate para Postularte</a></button>
        </section>
    </section>

    <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
    </footer>

    <!-- MODAL DE LOGIN -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="login.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Iniciar sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (!empty($loginError)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($loginError) ?>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="correoLogin" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correoLogin" name="correo" required>
                        </div>
                        <div class="mb-3">
                            <label for="passwordLogin" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="passwordLogin" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <p class="me-auto">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($mostrarModal): ?>
        <script>
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            window.onload = function() {
                loginModal.show();
            };
        </script>
    <?php endif; ?>

</body>

</html>