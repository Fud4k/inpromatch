<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "empresa") {
    header("Location: login.html");
    exit();
}
require 'db.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $requisitos = $_POST["requisitos"];
    $duracion = $_POST["duracion"];
    $empresa_id = $_SESSION["usuario_id"];

    try {
        $sql = "INSERT INTO ofertas (empresa_id, titulo, descripcion, requisitos, duracion) 
                VALUES (:empresa_id, :titulo, :descripcion, :requisitos, :duracion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":empresa_id" => $empresa_id,
            ":titulo" => $titulo,
            ":descripcion" => $descripcion,
            ":requisitos" => $requisitos,
            ":duracion" => $duracion
        ]);

        echo "Oferta publicada con éxito. <a href='dashboard_empresa.php'>Ver ofertas</a>";
    } catch (PDOException $e) {
        die("Error al publicar oferta: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Oferta</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h2>Publicar Nueva Oferta</h2>
    <form action="crear_oferta.php" method="POST">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required></textarea>

        <label for="requisitos">Requisitos:</label>
        <textarea id="requisitos" name="requisitos" required></textarea>

        <label for="duracion">Duración:</label>
        <input type="text" id="duracion" name="duracion" required>

        <button type="submit">Publicar Oferta</button>
    </form>
    <a href="dashboard_empresa.php">⬅️ Volver al Panel</a>
</body>
</html>
