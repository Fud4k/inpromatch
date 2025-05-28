<?php
session_start();
require 'db.php';

$usuario_id = $_SESSION['usuario_id'];
$receptor_id = $_GET['receptor_id'] ?? null;

if ($receptor_id) {
    $sql = "SELECT timestamp FROM escribiendo WHERE emisor_id = :rid AND receptor_id = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':rid' => $receptor_id, ':uid' => $usuario_id]);
    $row = $stmt->fetch();

    if ($row) {
        $diff = time() - strtotime($row['timestamp']);
        if ($diff < 5) {
            echo "Escribiendo...";
        }
    }
}
?>
