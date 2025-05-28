<?php
session_start();
require 'db.php';

$emisor_id = $_SESSION['usuario_id'];
$receptor_id = $_POST['receptor_id'] ?? null;

if ($receptor_id) {
    $sql = "REPLACE INTO escribiendo (emisor_id, receptor_id, timestamp) VALUES (:e, :r, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':e' => $emisor_id, ':r' => $receptor_id]);
}
