<?php
session_start();
require 'db.php';

$perfil_id = isset($_GET["id"]) ? $_GET["id"] : $_SESSION["usuario_id"];

$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$perfil_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

$es_mi_perfil = $_SESSION["usuario_id"] == $perfil_id;

if ($_SERVER["REQUEST_METHOD"] === "POST" && $es_mi_perfil) {
    $foto = $usuario["foto"];
    $cv = $usuario["curriculum"];

    if (!empty($_FILES["foto"]["name"])) {
        $foto = 'uploads/fotos/' . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
    }

    if (!empty($_FILES["curriculum"]["name"])) {
        $cv = 'uploads/curriculums/' . basename($_FILES["curriculum"]["name"]);
        move_uploaded_file($_FILES["curriculum"]["tmp_name"], $cv);
    }

    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, correo=?, foto=?, carrera=?, universidad=?, curriculum=? WHERE id=?");
    $stmt->execute([
        $_POST["nombre"],
        $_POST["correo"],
        $foto,
        $_POST["carrera"] ?? null,
        $_POST["universidad"] ?? null,
        $cv,
        $_SESSION["usuario_id"]
    ]);

    header("Location: perfil.php");
    exit();
}

$dashboard = $usuario["tipo"] === "estudiante" ? "dashboard_estudiante.php" : "dashboard_empresa.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .perfil-container {
            max-width: 800px;
            margin: auto;
            display: flex;
            gap: 20px;
            padding: 2em;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .foto-perfil {
            flex: 0 0 200px;
        }

        .foto-perfil img {
            width: 100%;
            max-width: 200px;
            border-radius: 10px;
            object-fit: cover;
        }

        .datos-perfil {
            flex: 1;
        }

        h2 {
            text-align: center;
            margin-bottom: 1em;
        }

        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 1em;
            border: 1px solid #aaa;
            border-radius: 4px;
        }

        input:disabled {
            background-color: #eee;
        }

        .btn {
            padding: 10px;
            margin-bottom: 10px;
            width: 100%;
            background-color: #3498db;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #555;
        }
    </style>
    <script>
        function habilitarEdicion() {
            const campos = document.querySelectorAll('.editable');
            campos.forEach(campo => campo.disabled = false);
            document.getElementById('guardarBtn').style.display = 'block';
            document.getElementById('editarBtn').style.display = 'none';
        }
    </script>
</head>
<body>
    <h2>Perfil de <?php echo htmlspecialchars($usuario["nombre"]); ?></h2>
    <div class="perfil-container">
        <div class="foto-perfil">
            <img src="<?php echo $usuario["foto"] ?: 'img/default.jpg'; ?>" alt="Foto de perfil">
        </div>

        <div class="datos-perfil">
            <form method="POST" enctype="multipart/form-data">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="editable" value="<?= htmlspecialchars($usuario["nombre"]); ?>" disabled>

                <label>Correo:</label>
                <input type="email" name="correo" class="editable" value="<?= htmlspecialchars($usuario["correo"]); ?>" disabled>

                <label>Tipo de Usuario:</label>
                <input type="text" value="<?= htmlspecialchars($usuario["tipo"]); ?>" disabled>

                <?php if ($usuario["tipo"] === "estudiante"): ?>
                    <label>Carrera:</label>
                    <input type="text" name="carrera" class="editable" value="<?= htmlspecialchars($usuario["carrera"]); ?>" disabled>

                    <label>Universidad:</label>
                    <input type="text" name="universidad" class="editable" value="<?= htmlspecialchars($usuario["universidad"]); ?>" disabled>

                    <label>Currículum:</label>
                    <?php if ($usuario["curriculum"]): ?>
                        <a href="<?= $usuario["curriculum"]; ?>" target="_blank">Ver CV</a><br>
                    <?php endif; ?>
                    <input type="file" name="curriculum" class="editable" disabled>
                <?php endif; ?>

                <label>Foto:</label>
                <input type="file" name="foto" class="editable" disabled>

                <?php if ($es_mi_perfil): ?>
                    <button type="button" id="editarBtn" class="btn" onclick="habilitarEdicion()">Editar</button>
                    <button type="submit" id="guardarBtn" class="btn" style="display: none;">Guardar Cambios</button>
                <?php endif; ?>
            </form>

            <a href="<?= $dashboard; ?>"><button class="btn btn-secondary">Volver al Dashboard</button></a>
        </div>
    </div>
</body>
</html>
