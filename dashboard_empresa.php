<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "empresa") {
    header("Location: login.php");
    exit();
}
require 'db.php';

// Obtener ofertas publicadas por la empresa
$sql = "SELECT * FROM ofertas WHERE empresa_id = :empresa_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":empresa_id" => $_SESSION["usuario_id"]]);
$ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empresa</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="img/logo/logo.png" alt="Logo de InProMatch">
            </div>
            <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
            <a class="sidebar-btn" href="perfil.php">👤 Perfil Empresarial</a>
            <a class="sidebar-btn" href="gestionar_postulaciones.php">📋 Postulaciones</a>
            <a class="sidebar-btn" href="mensajes.php">💬 Mensajes</a>
            <a class="sidebar-btn cerrar-sesion" href="logout.php">🚪 Cerrar sesión</a>
        </aside>

        <!-- Contenido principal -->
        <main class="contenido">
            <header>
                <div class="logo">
                    <h1>InProMatch</h1>
                </div>
            </header>

            <h3>Tus Ofertas Publicadas</h3>
            <a href="crear_oferta.php" class="btn-crear">➕ Crear Nueva Oferta</a>

            <section id="D_emp" class="contenedor-ofertas">
                <?php foreach ($ofertas as $oferta): ?>
                <div class="oferta-card">
                    <h4><?php echo $oferta["titulo"]; ?></h4>
                    <p><?php echo $oferta["descripcion"]; ?></p>
                    <p><strong>Requisitos:</strong> <?php echo $oferta["requisitos"]; ?></p>
                    <p><strong>Duración:</strong> <?php echo $oferta["duracion"]; ?></p>

                    <div class="botones-oferta">
                        <a class="btn-editar" href="editar_oferta.php?id=<?php echo $oferta['id']; ?>">✏️ Editar</a>
                        <a class="btn-eliminar" href="eliminar_oferta.php?id=<?php echo $oferta['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta oferta?')">🗑️ Eliminar</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </section>
        </main>
    </div>

    <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
