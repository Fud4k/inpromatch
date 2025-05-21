<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "empresa") {
    header("Location: login.php");
    exit();
}
require 'db.php';

// Obtener postulaciones a ofertas de la empresa
$sql = "SELECT postulaciones.id, usuarios.nombre AS estudiante, usuarios.correo, postulaciones.nota, postulaciones.estado, ofertas.titulo 
        FROM postulaciones
        JOIN usuarios ON postulaciones.estudiante_id = usuarios.id
        JOIN ofertas ON postulaciones.oferta_id = ofertas.id
        WHERE ofertas.empresa_id = :empresa_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":empresa_id" => $_SESSION["usuario_id"]]);
$postulaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Postulaciones</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Postulaciones Recibidas</h2>

    <ul>
        <?php foreach ($postulaciones as $postulacion): ?>
            <li>
                <h4><?php echo $postulacion["titulo"]; ?> - <small><?php echo $postulacion["estudiante"]; ?></small></h4>
                <p><strong>Correo:</strong> <?php echo $postulacion["correo"]; ?></p>
                <p><strong>Mensaje:</strong> <?php echo $postulacion["nota"]; ?></p>
                <p><strong>Estado:</strong> <?php echo ucfirst($postulacion["estado"]); ?></p>

                <form action="actualizar_postulacion.php" method="POST">
                    <input type="hidden" name="postulacion_id" value="<?php echo $postulacion['id']; ?>">
                    <button type="submit" name="accion" value="aceptado">✅ Aceptar</button>
                    <button type="submit" name="accion" value="rechazado">❌ Rechazar</button>
                    <button type="submit" name="accion" value="archivado">📂 Archivar</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <a href="dashboard_empresa.php">⬅️ Volver al Panel</a>
</body>
</html>
