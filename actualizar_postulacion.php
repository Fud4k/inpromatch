<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "empresa") {
    header("Location: login.html");
    exit();
}
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postulacion_id = $_POST["postulacion_id"];
    $accion = $_POST["accion"];

    try {
        $sql = "UPDATE postulaciones SET estado = :estado WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":estado" => $accion,
            ":id" => $postulacion_id
        ]);

        header("Location: gestionar_postulaciones.php");
        exit();
    } catch (PDOException $e) {
        die("Error al actualizar postulación: " . $e->getMessage());
    }
}
?>
