<?php
session_start();
require 'validar_sesion.php';  // Verificar si el estudiante está logueado

// Conectar a la base de datos
require 'db.php';

// Obtener las ofertas disponibles (suponiendo que hay una tabla 'ofertas' en la base de datos)
$sql = "SELECT * FROM ofertas";
$stmt = $pdo->query($sql);
$ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postúlate a una Oferta</title>
</head>
<body>
    <h2>Postúlate a una Oferta de Pasantía</h2>
    <form action="procesar_postulacion.php" method="POST">
        <label for="oferta_id">Oferta:</label>
        <select id="oferta_id" name="oferta_id" required>
            <?php foreach ($ofertas as $oferta): ?>
                <option value="<?= $oferta['id'] ?>"><?= $oferta['nombre'] ?> - <?= $oferta['empresa'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="nota">Mensaje opcional para la empresa:</label>
        <textarea id="nota" name="nota" rows="4" placeholder="Escribe un mensaje opcional..."></textarea>

        <button type="submit">Postularse</button>
    </form>
</body>
</html>
