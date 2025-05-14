<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "estudiante") {
    header("Location: login.html");
    exit();
}
require 'db.php';

// Obtener todas las ofertas de pasantías
$sql = "SELECT ofertas.id, ofertas.titulo, ofertas.descripcion, ofertas.requisitos, ofertas.duracion, usuarios.nombre AS empresa 
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
    <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
    <h3>Ofertas Disponibles</h3>

    <ul>
        <?php foreach ($ofertas as $oferta): ?>
            <li>
                <h4><?php echo $oferta["titulo"]; ?> - <small><?php echo $oferta["empresa"]; ?></small></h4>
                <p><?php echo $oferta["descripcion"]; ?></p>
                <p><strong>Requisitos:</strong> <?php echo $oferta["requisitos"]; ?></p>
                <p><strong>Duración:</strong> <?php echo $oferta["duracion"]; ?></p>

                <form action="postular.php" method="POST">
                    <input type="hidden" name="oferta_id" value="<?php echo $oferta['id']; ?>">
                    <label for="nota">Mensaje opcional:</label>
                    <textarea name="nota" id="nota"></textarea>
                    <button type="submit">Postularme</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
