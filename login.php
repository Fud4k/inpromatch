<?php

session_start();
require 'db.php'; // Incluye el archivo de conexión a la base de datos.

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $password = $_POST["password"];

    // Consulta para verificar el correo en la base de datos
    $sql = "SELECT id, tipo, nombre, password FROM usuarios WHERE correo = :correo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":correo" => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($password, $usuario["password"])) {
        $_SESSION["usuario_id"] = $usuario["id"];
        $_SESSION["tipo"] = $usuario["tipo"];
        $_SESSION["nombre"] = $usuario["nombre"];

        if ($usuario["tipo"] == "estudiante") {
            header("Location: dashboard_estudiante.php");
        } else {
            header("Location: dashboard_empresa.php");
        }
        exit();
    } else {
        $_SESSION["login_error"] = "Correo o contraseña incorrectos. Inténtalo de nuevo.";
        header("Location: index.php"); // Redirige al index
        exit();
    }
}
