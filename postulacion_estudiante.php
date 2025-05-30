<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'estudiante') {
    header("Location: login.php");
    exit();
}

$oferta_id = $_GET['oferta_id'] ?? null;
$vista = $_GET['vista'] ?? 'postular';

if (!$oferta_id) {
    echo "Oferta no especificada.";
    exit();
}

// Obtener detalles de la oferta y datos de la empresa
$sql = "SELECT o.*, u.nombre AS empresa_nombre, u.foto AS empresa_foto, u.id AS empresa_id
        FROM ofertas o
        JOIN usuarios u ON o.empresa_id = u.id
        WHERE o.id = :oferta_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':oferta_id' => $oferta_id]);
$oferta = $stmt->fetch();

if (!$oferta) {
    echo "Oferta no encontrada.";
    exit();
}

// Verificar si ya se postuló
$ya_postulado = false;
$sqlCheck = "SELECT id FROM postulaciones WHERE estudiante_id = :eid AND oferta_id = :oid";
$checkStmt = $pdo->prepare($sqlCheck);
$checkStmt->execute([
    ':eid' => $_SESSION['usuario_id'],
    ':oid' => $oferta_id
]);
$ya_postulado = $checkStmt->fetch();

// Si el estudiante hizo clic en "Postularme"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$ya_postulado) {
    $sqlInsert = "INSERT INTO postulaciones (estudiante_id, oferta_id) VALUES (:eid, :oid)";
    $insertStmt = $pdo->prepare($sqlInsert);
    $insertStmt->execute([
        ':eid' => $_SESSION['usuario_id'],
        ':oid' => $oferta_id
    ]);
    header("Location: postulacion_estudiante.php?oferta_id=$oferta_id&vista=mis_postulaciones&postulacion=1");
    exit();
}

// Obtener mis postulaciones si se requiere
$postulaciones = [];
if ($vista === 'mis_postulaciones') {
    $sqlPost = "SELECT p.*, o.titulo, o.descripcion, u.nombre AS empresa_nombre FROM postulaciones p
                JOIN ofertas o ON p.oferta_id = o.id
                JOIN usuarios u ON o.empresa_id = u.id
                WHERE p.estudiante_id = :eid ORDER BY p.fecha_postulacion DESC";
    $postStmt = $pdo->prepare($sqlPost);
    $postStmt->execute([':eid' => $_SESSION['usuario_id']]);
    $postulaciones = $postStmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Postulación - InProMatch</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .layout {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 220px;
            background: #333;
            padding: 20px;
            color: white;
        }

        .sidebar a {
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            background: #555;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            color: white;
        }

        .contenido {
            flex: 1;
            padding: 30px;
            background: #fff;
        }

        .ficha {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            max-width: 700px;
        }

        .empresa-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .empresa-info img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 15px;
        }

        .btn-ir-perfil {
            padding: 5px 10px;
            font-size: 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 5px;
            display: inline-block;
        }

        .estado-msg {
            margin-top: 15px;
            color: green;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            background: #444;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }

        .postulacion-item {
            background: #e9e9e9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="layout">
    <div class="sidebar">
        <a href="?oferta_id=<?php echo $oferta_id; ?>&vista=postular">Postularme</a>
        <a href="?oferta_id=<?php echo $oferta_id; ?>&vista=mis_postulaciones">Mis Postulaciones</a>
        <a href="dashboard_estudiante.php" class="btn-volver">← Home</a>
    </div>

    <div class="contenido">
        <?php if ($vista === 'mis_postulaciones'): ?>
            <h2>Mis Postulaciones</h2>
            <?php foreach ($postulaciones as $post): ?>
                <div class="postulacion-item">
                    <strong><?php echo $post['titulo']; ?></strong>
                    <p><?php echo $post['descripcion']; ?></p>
                    <p><strong>Empresa:</strong> <?php echo $post['empresa_nombre']; ?></p>
                    <p><strong>Estado:</strong> <?php echo ucfirst($post['estado']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="ficha">
                <div class="empresa-info">
                    <img src="<?php echo $oferta['empresa_foto'] ?? 'img/default.png'; ?>" alt="Foto empresa">
                    <div>
                        <strong><?php echo $oferta['empresa_nombre']; ?></strong><br>
                        <a class="btn-ir-perfil" href="perfil.php?id=<?php echo $oferta['empresa_id']; ?>&vista=1">Ir a perfil</a>
                    </div>
                </div>

                <h2><?php echo $oferta['titulo']; ?></h2>
                <p><strong>Descripción:</strong> <?php echo $oferta['descripcion']; ?></p>
                <p><strong>Requisitos:</strong> <?php echo $oferta['requisitos']; ?></p>
                <p><strong>Duración:</strong> <?php echo $oferta['duracion']; ?></p>
                <p><strong>Ubicación:</strong> <?php echo $oferta['ubicacion'] ?? 'No especificada'; ?></p>
                <p><strong>Horario:</strong> <?php echo $oferta['horario'] ?? 'No especificado'; ?></p>

                <?php if ($ya_postulado): ?>
                    <p class="estado-msg">Ya te has postulado a esta oferta.</p>
                <?php else: ?>
                    <form method="POST">
                        <button type="submit" class="btn-ir-perfil">Postularme</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
