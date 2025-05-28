<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "estudiante") {
    header("Location: login.php");
    exit();
}
require 'db.php';

// Obtener todas las ofertas de pasantías
$sql = "SELECT ofertas.id, ofertas.titulo, ofertas.descripcion, ofertas.requisitos, ofertas.duracion, ofertas.empresa_id, usuarios.nombre AS empresa 
        FROM ofertas 
        JOIN usuarios ON ofertas.empresa_id = usuarios.id";
$stmt = $pdo->query($sql);
$ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Estudiante</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    
    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="img/logo/logo.png" alt="Logo de InProMatch">
            </div>
            <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
            <a class="sidebar-btn" href="perfil.php">👤 Perfil</a>
            <a class="sidebar-btn" href="mensajes.php">💬 Mensajes</a>
            <a class="sidebar-btn cerrar-sesion" href="logout.php">🚪 Cerrar sesión</a>
        </aside>

        <main class="contenido">
            <header>
                <div class="logo">
                    <h1>InProMatch</h1>
                </div>
            </header>

            <h3>Ofertas Disponibles</h3>

    <section id="D_emp" class="contenedor-ofertas">
    <?php foreach ($ofertas as $oferta): ?>
        
        <div class="oferta-card">
            <h4><?php echo $oferta["titulo"]; ?> - <small><?php echo $oferta["empresa"]; ?></small></h4>
            <p><?php echo $oferta["descripcion"]; ?></p>
            <p><strong>Requisitos:</strong> <?php echo $oferta["requisitos"]; ?></p>
            <p><strong>Duración:</strong> <?php echo $oferta["duracion"]; ?></p>

            <div class="botones-oferta">
            <form action="postular.php" method="POST">
                <input type="hidden" name="oferta_id" value="<?php echo $oferta['id']; ?>">
                <button type="submit" class="btn-postular">Postularme</button>
            </form>

            <a href="mensajes.php?receptor_id=<?php echo $oferta['empresa_id']; ?>" class="btn-chat" title="Chatear con la empresa">
                <img src="img/icon/mensaje.png" alt="Chat"> Chat
            </a>
        </div>
        </div>
    <?php endforeach; ?>
</section>


    <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
    </footer>

    <script src="js/script.js"></script>
</body>

</html>