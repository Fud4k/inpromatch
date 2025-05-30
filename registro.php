<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $tipo = $_POST['tipo'];

    // Encriptar contraseña
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Inicializar valores comunes
    $foto = null;
    if (!empty($_FILES["foto"]["name"])) {
        $foto = 'uploads/fotos/' . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
    }

    // Campos específicos
    $carrera = $_POST['carrera'] ?? null;
    $universidad = $_POST['universidad'] ?? null;
    $telefono_estudiante = $_POST['telefono_estudiante'] ?? null;
    $direccion_estudiante = $_POST['direccion_estudiante'] ?? null;
    $habilidades = $_POST['habilidades'] ?? null;

    $nombre_empresa = $_POST['nombre_empresa'] ?? null;
    $descripcion_empresa = $_POST['descripcion_empresa'] ?? null;
    $telefono_empresa = $_POST['telefono_empresa'] ?? null;
    $direccion_empresa = $_POST['direccion_empresa'] ?? null;
    $sitio_web_empresa = $_POST['sitio_web_empresa'] ?? null;

    $sql = "INSERT INTO usuarios (
        nombre, correo, password, tipo, foto,
        carrera, universidad, telefono_estudiante, direccion_estudiante, habilidades,
        nombre_empresa, descripcion_empresa, telefono_empresa, direccion_empresa, sitio_web_empresa
    ) VALUES (
        :nombre, :correo, :password, :tipo, :foto,
        :carrera, :universidad, :telefono_estudiante, :direccion_estudiante, :habilidades,
        :nombre_empresa, :descripcion_empresa, :telefono_empresa, :direccion_empresa, :sitio_web_empresa
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':correo' => $correo,
        ':password' => $hashedPassword,
        ':tipo' => $tipo,
        ':foto' => $foto,
        ':carrera' => $tipo === 'estudiante' ? $carrera : null,
        ':universidad' => $tipo === 'estudiante' ? $universidad : null,
        ':telefono_estudiante' => $tipo === 'estudiante' ? $telefono_estudiante : null,
        ':direccion_estudiante' => $tipo === 'estudiante' ? $direccion_estudiante : null,
        ':habilidades' => $tipo === 'estudiante' ? $habilidades : null,
        ':nombre_empresa' => $tipo === 'empresa' ? $nombre_empresa : null,
        ':descripcion_empresa' => $tipo === 'empresa' ? $descripcion_empresa : null,
        ':telefono_empresa' => $tipo === 'empresa' ? $telefono_empresa : null,
        ':direccion_empresa' => $tipo === 'empresa' ? $direccion_empresa : null,
        ':sitio_web_empresa' => $tipo === 'empresa' ? $sitio_web_empresa : null
    ]);

    header("Location: registro.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - InProMatch</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }
        form {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-top: 1rem;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.25rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button, .home-btn {
            margin-top: 1.5rem;
            width: 20%;
            padding: 0.75rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            align-items: normal;
        }
        .home-btn {
            margin-top: 0.5rem;
            background-color: #2ecc71;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
    </style>
    <script>
        function mostrarCampos() {
            const tipo = document.getElementById("tipo").value;
            document.getElementById("camposEstudiante").style.display = tipo === "estudiante" ? "block" : "none";
            document.getElementById("camposEmpresa").style.display = tipo === "empresa" ? "block" : "none";
        }

        document.addEventListener("DOMContentLoaded", mostrarCampos);
    </script>
</head>
<body>

    <a href="index.php" class="home-btn">🏠 Home</a>

    <form action="registro.php" method="POST" enctype="multipart/form-data">
        <h2>Registro de Usuario</h2>

        <label for="tipo">Tipo de Usuario:</label>
        <select id="tipo" name="tipo" onchange="mostrarCampos()" required>
            <option value="estudiante">Estudiante</option>
            <option value="empresa">Empresa</option>
        </select>

        <label for="nombre">Nombre Completo:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <label for="foto">Fotografía (opcional):</label>
        <input type="file" id="foto" name="foto" accept="image/*">

        <!-- Estudiante -->
        <div id="camposEstudiante" style="display:none;">
            <label for="carrera">Carrera:</label>
            <input type="text" id="carrera" name="carrera">

            <label for="universidad">Universidad:</label>
            <input type="text" id="universidad" name="universidad">

            <label for="telefono_estudiante">Teléfono:</label>
            <input type="text" id="telefono_estudiante" name="telefono_estudiante">

            <label for="direccion_estudiante">Dirección:</label>
            <input type="text" id="direccion_estudiante" name="direccion_estudiante">

            <label for="habilidades">Habilidades:</label>
            <textarea id="habilidades" name="habilidades"></textarea>
        </div>

        <!-- Empresa -->
        <div id="camposEmpresa" style="display:none;">
            <label for="nombre_empresa">Nombre de la Empresa:</label>
            <input type="text" id="nombre_empresa" name="nombre_empresa">

            <label for="descripcion_empresa">Descripción de la Empresa:</label>
            <textarea id="descripcion_empresa" name="descripcion_empresa"></textarea>

            <label for="telefono_empresa">Teléfono de Contacto:</label>
            <input type="text" id="telefono_empresa" name="telefono_empresa">

            <label for="direccion_empresa">Dirección:</label>
            <input type="text" id="direccion_empresa" name="direccion_empresa">

            <label for="sitio_web_empresa">Sitio Web:</label>
            <input type="url" id="sitio_web_empresa" name="sitio_web_empresa">
        </div>

        <button type="submit">Registrarse</button>
    </form>

</body>
</html>
