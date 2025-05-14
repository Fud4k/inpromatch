<?php
session_start();
require 'db.php';  // Conectar a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $oferta_id = $_POST["oferta_id"];
    $estudiante_id = $_SESSION["usuario_id"];
    $nota = $_POST["nota"];

    // Insertar los datos en la tabla 'postulaciones'
    try {
        $sql = "INSERT INTO postulaciones (oferta_id, estudiante_id, nota, estado) 
                VALUES (:oferta_id, :estudiante_id, :nota, 'pendiente')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":oferta_id" => $oferta_id,
            ":estudiante_id" => $estudiante_id,
            ":nota" => $nota
        ]);

        echo "Postulación enviada con éxito. <a href='index.php'>Volver al inicio</a>";
    } catch (PDOException $e) {
        die("Error al postularse: " . $e->getMessage());
    }
}
?>
