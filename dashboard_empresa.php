<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "empresa") {
    header("Location: login.php");
    exit();
}
require 'db.php'; // Conexión a la base de datos

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
    <header>
        <div class="logo">
            <h1>InProMatch</h1>
        </div>
        <nav>
            <ul>
                <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
                <li><a href="gestionar_postulaciones.php">Postulaciones</a></li>
            </ul>
        </nav>
    </header>
    
    <h3>Tus ofertas publicadas</h3>
    <a href="crear_oferta.php" class="btn-crear">➕ Crear Nueva Oferta</a>
    <section id="D_emp">
        <form action="" method="post" id="postForm">
        <ul>
        <?php foreach ($ofertas as $oferta): ?>
            <li>
                <h4><?php echo $oferta["titulo"]; ?></h4>
                <p><?php echo $oferta["descripcion"]; ?></p>
                <p><strong>Requisitos:</strong> <?php echo $oferta["requisitos"]; ?></p>
                <p><strong>Duración:</strong> <?php echo $oferta["duracion"]; ?></p>
                <a class="btn-eyd" href="editar_oferta.php?id=<?php echo $oferta['id']; ?>">✏️ Editar</a>
                <a class="btn-eyd" href="eliminar_oferta.php?id=<?php echo $oferta['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta oferta?')">🗑️ Eliminar</a>
            </li>
        <?php endforeach; ?>
        </ul></form>
        
    </section>
    <a href="logout.php" class="btn-crear">Cerrar sesión</a>
    <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
