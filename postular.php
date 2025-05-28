<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id']) || !isset($_POST['oferta_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$oferta_id = $_POST['oferta_id'];

// Evitar duplicación (opcional)
$sqlCheck = "SELECT COUNT(*) FROM postulaciones WHERE usuario_id = :usuario_id AND oferta_id = :oferta_id";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute(['usuario_id' => $usuario_id, 'oferta_id' => $oferta_id]);
if ($stmtCheck->fetchColumn() > 0) {
    header("Location: ofertas.php?ya_postulado=1");
    exit();
}

// Insertar postulación
$sql = "INSERT INTO postulaciones (usuario_id, oferta_id, fecha) VALUES (:usuario_id, :oferta_id, NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'usuario_id' => $usuario_id,
    'oferta_id' => $oferta_id
]);

header("Location: ofertas.php?postulacion=ok");
exit();
