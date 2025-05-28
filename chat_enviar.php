<?php
session_start();
require 'db.php';

$emisor_id = $_SESSION["usuario_id"];
$receptor_id = $_POST["receptor_id"] ?? null;
$mensaje = trim($_POST["mensaje"] ?? "");

if ($receptor_id && $mensaje !== "") {
    $sql = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje, fecha) 
            VALUES (:eid, :rid, :msg, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':eid' => $emisor_id,
        ':rid' => $receptor_id,
        ':msg' => $mensaje
    ]);
}
?>
