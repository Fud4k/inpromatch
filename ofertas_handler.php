<?php
session_start();
require 'db.php';

if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "empresa") {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION["usuario_id"];
$accion = $_POST["accion"] ?? '';

try {
    if ($accion === "crear") {
        $sql = "INSERT INTO ofertas (empresa_id, titulo, descripcion, requisitos, duracion) 
                VALUES (:empresa_id, :titulo, :descripcion, :requisitos, :duracion)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":empresa_id" => $empresa_id,
            ":titulo" => $_POST["titulo"],
            ":descripcion" => $_POST["descripcion"],
            ":requisitos" => $_POST["requisitos"],
            ":duracion" => $_POST["duracion"]
        ]);
    } elseif ($accion === "editar") {
        $sql = "UPDATE ofertas 
                SET titulo = :titulo, descripcion = :descripcion, requisitos = :requisitos, duracion = :duracion 
                WHERE id = :id AND empresa_id = :empresa_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id" => $_POST["id"],
            ":empresa_id" => $empresa_id,
            ":titulo" => $_POST["titulo"],
            ":descripcion" => $_POST["descripcion"],
            ":requisitos" => $_POST["requisitos"],
            ":duracion" => $_POST["duracion"]
        ]);
    } elseif ($accion === "eliminar") {
        $sql = "DELETE FROM ofertas WHERE id = :id AND empresa_id = :empresa_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":id" => $_POST["id"],
            ":empresa_id" => $empresa_id
        ]);
    }

    header("Location: dashboard_empresa.php");
    exit();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
