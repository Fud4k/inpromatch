<?php
session_start();  // Iniciar la sesión

// Verificar si el usuario está logueado como estudiante
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "estudiante") {
    // Redirigir al login si no está logueado
    header("Location: login.php");
    exit();
}
?>
