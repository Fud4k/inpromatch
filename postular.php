<?php
session_start();
require 'db.php';

// Asegura que el usuario esté autenticado y sea estudiante
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'estudiante') {
    header("Location: login.php");
    exit();
}

// Verifica que llegue el ID de la oferta
if (!isset($_GET['oferta_id'])) {
    echo "Oferta no especificada.";
    exit();
}

$estudiante_id = $_SESSION['usuario_id'];
$oferta_id = intval($_GET['oferta_id']);

// Verifica si ya existe una postulación previa
$sql = "SELECT COUNT(*) FROM postulaciones WHERE estudiante_id = :estudiante_id AND oferta_id = :oferta_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':estudiante_id' => $estudiante_id,
    ':oferta_id' => $oferta_id
]);
$ya_postulado = $stmt->fetchColumn();

if ($ya_postulado > 0) {
    // Ya se postuló a esta oferta
    header("Location: postulacion_estudiante.php?oferta_id=$oferta_id&ya_postulado=1");
    exit();
}

// Si no hay postulación previa, se inserta
$insert = "INSERT INTO postulaciones (estudiante_id, oferta_id, estado) VALUES (:estudiante_id, :oferta_id, 'pendiente')";
$stmt = $pdo->prepare($insert);
$stmt->execute([
    ':estudiante_id' => $estudiante_id,
    ':oferta_id' => $oferta_id
]);

// Redirige al detalle de la postulación
header("Location: postulacion_estudiante.php?oferta_id=$oferta_id&postulacion=ok");
exit();
?>
