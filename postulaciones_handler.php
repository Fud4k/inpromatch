<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postulacion_id = $_POST['postulacion_id'];
    $accion = $_POST['accion'];

    $estado = $accion === 'aceptar' ? 'aceptada' : 'rechazada';

    $sql = "UPDATE postulaciones SET estado = :estado WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':estado' => $estado,
        ':id' => $postulacion_id
    ]);

    header("Location: postulaciones_empresa.php");
    exit();
}
?>
