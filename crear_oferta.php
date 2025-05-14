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
