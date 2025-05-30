<?php
session_start();
require 'db.php';

$perfil_id = isset($_GET["id"]) ? $_GET["id"] : $_SESSION["usuario_id"];

$sql = "SELECT nombre, foto, carrera, universidad, curriculum FROM usuarios WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$perfil_id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

$es_mi_perfil = $_SESSION["usuario_id"] == $perfil_id && $_SESSION["tipo"] === "estudiante";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $es_mi_perfil) {
    // Subida de archivos
    if (!empty($_FILES["foto"]["name"])) {
        $foto = 'uploads/fotos/' . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $foto);
        $stmt = $pdo->prepare("UPDATE usuarios SET foto = ? WHERE id = ?");
        $stmt->execute([$foto, $_SESSION["usuario_id"]]);
    }

    if (!empty($_FILES["curriculum"]["name"])) {
        $cv = 'uploads/curriculums/' . basename($_FILES["curriculum"]["name"]);
        move_uploaded_file($_FILES["curriculum"]["tmp_name"], $cv);
        $stmt = $pdo->prepare("UPDATE usuarios SET curriculum = ? WHERE id = ?");
        $stmt->execute([$cv, $_SESSION["usuario_id"]]);
    }

    // Actualización de carrera y universidad
    $carrera = $_POST['carrera'] ?? null;
    $universidad = $_POST['universidad'] ?? null;
    $stmt = $pdo->prepare("UPDATE usuarios SET carrera = ?, universidad = ? WHERE id = ?");
    $stmt->execute([$carrera, $universidad, $_SESSION["usuario_id"]]);

    header("Location: perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <h1>Perfil de <?php echo htmlspecialchars($usuario["nombre"]); ?></h1>
                <li><a href="dashboard_estudiante.php">Volver al panel</a></li>
            </ul>
        </nav>
    </header>

    <section id="D_emp">
        <img src="<?php echo $usuario["foto"] ?: 'img/default.jpg'; ?>" alt="Foto de perfil" width="150">
        <p><strong>Nombre de la Empresa:</strong> <?php echo $usuario["nombre_empresa"]; ?></p>
        <p><strong>Rubro:</strong> <?php echo $usuario["rubro"]; ?></p>
        <p><strong>Descripción:</strong> <?php echo $usuario["descripcion"]; ?></p>
        <p><strong>Sitio Web:</strong> <a href="<?php echo $usuario["sitio_web"]; ?>" target="_blank"><?php echo $usuario["sitio_web"]; ?></a></p>
        <p><strong>Contacto:</strong> <?php echo $usuario["representante_nombre"]; ?> (<?php echo $usuario["representante_cargo"]; ?>)</p>
        <p><strong>Teléfono:</strong> <?php echo $usuario["telefono_contacto"]; ?></p>
        <?php if ($usuario["curriculum"]): ?>
            <a href="<?php echo $usuario["curriculum"]; ?>" target="_blank">Ver documento</a>
        <?php else: ?>
            No subido aún.
        <?php endif; ?>
        </p>
    </section>

    <?php if ($es_mi_perfil): ?>
        <section>
            <h3>Editar Perfil</h3>
            <form method="POST" enctype="multipart/form-data">
                <label for="foto">Cambiar Foto:</label>
                <input type="file" name="foto"><br>

                <label for="carrera">Carrera:</label>
                <input type="text" name="carrera" value="<?php echo htmlspecialchars($usuario["carrera"]); ?>"><br>

                <label for="universidad">Universidad:</label>
                <input type="text" name="universidad" value="<?php echo htmlspecialchars($usuario["universidad"]); ?>"><br>

                <label for="curriculum">Subir CV (PDF):</label>
                <input type="file" name="curriculum"><br>

                <button type="submit">Guardar Cambios</button>
            </form>
        </section>
    <?php endif; ?>

    <footer>
        <p>&copy; 2025 InProMatch</p>
    </footer>
</body>

</html>