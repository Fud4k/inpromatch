<?php
session_start();
require 'db.php';

if (!isset($_SESSION["usuario_id"])) {
    exit("Sesión no iniciada");
}

$usuario_id = $_SESSION["usuario_id"];
$receptor_id = $_GET["receptor_id"] ?? null;

if (!$receptor_id || $receptor_id == $usuario_id) {
    exit();
}

$sql = "SELECT * FROM mensajes 
        WHERE (emisor_id = :uid AND receptor_id = :rid) 
           OR (emisor_id = :rid AND receptor_id = :uid)
        ORDER BY fecha ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':uid' => $usuario_id,
    ':rid' => $receptor_id
]);

$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($mensajes as $mensaje) {
    $clase = $mensaje['emisor_id'] == $usuario_id ? 'mensaje-propio' : 'mensaje-ajeno';
    echo "<div class='$clase'>" . htmlspecialchars($mensaje['mensaje']) . "</div>";
}
?>
