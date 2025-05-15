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
    <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
    <h3>Tus ofertas publicadas</h3>
    
    <a href="crear_oferta.html">➕ Crear Nueva Oferta</a>

    <ul>
        <?php foreach ($ofertas as $oferta): ?>
            <li>
                <h4><?php echo $oferta["titulo"]; ?></h4>
                <p><?php echo $oferta["descripcion"]; ?></p>
                <p><strong>Requisitos:</strong> <?php echo $oferta["requisitos"]; ?></p>
                <p><strong>Duración:</strong> <?php echo $oferta["duracion"]; ?></p>
                <a href="editar_oferta.php?id=<?php echo $oferta['id']; ?>">✏️ Editar</a>
                <a href="eliminar_oferta.php?id=<?php echo $oferta['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta oferta?')">🗑️ Eliminar</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
