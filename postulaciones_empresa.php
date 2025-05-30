<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['usuario_id'];

$sql = "SELECT p.*, 
                u.nombre AS estudiante_nombre, 
                u.id AS estudiante_id,
                u.foto AS estudiante_foto,
                u.curriculum,
                o.titulo AS oferta_titulo
        FROM postulaciones p
        JOIN ofertas o ON p.oferta_id = o.id
        JOIN usuarios u ON p.estudiante_id = u.id
        WHERE o.empresa_id = :eid
        ORDER BY p.fecha_postulacion DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([':eid' => $empresa_id]);
$postulaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Postulaciones Recibidas - InProMatch</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .postulacion {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #f9f9f9;
        }

        .postulacion img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }

        .postulante-info {
            display: flex;
            align-items: center;
        }

        .acciones button {
            margin: 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .aceptar {
            background-color: #4CAF50;
            color: white;
        }

        .rechazar {
            background-color: #f44336;
            color: white;
        }

        .perfil,
        .chat {
            background-color: #007bff;
            color: white;
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            margin-right: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <h2>Postulaciones Recibidas</h2>
    <?php foreach ($postulaciones as $post): ?>
        <div class="postulacion">
            <div class="postulante-info">
                <img src="<?php echo $post['estudiante_foto'] ?? 'img/default.png'; ?>" alt="Foto estudiante">
                <div>
                    <strong><?php echo $post['estudiante_nombre']; ?></strong><br>
                    <small><b>Oferta:</b> <?php echo $post['oferta_titulo']; ?></small>
                </div>
            </div>

            <p><strong>Estado:</strong> <?php echo ucfirst($post['estado']); ?></p>

            <?php if ($post['curriculum']): ?>
                <p><a href="documentos/<?php echo $post['curriculum']; ?>" target="_blank">📄 Ver Curriculum</a></p>
            <?php else: ?>
                <p><em>Sin documento adjunto.</em></p>
            <?php endif; ?>

            <div class="acciones">
                <form method="POST" action="postulaciones_handler.php" style="display:inline;">
                    <input type="hidden" name="postulacion_id" value="<?php echo $post['id']; ?>">
                    <input type="hidden" name="accion" value="aceptar">
                    <button type="submit" class="aceptar">Aceptar</button>
                </form>

                <form method="POST" action="postulaciones_handler.php" style="display:inline;">
                    <input type="hidden" name="postulacion_id" value="<?php echo $post['id']; ?>">
                    <input type="hidden" name="accion" value="rechazar">
                    <button type="submit" class="rechazar">Rechazar</button>
                </form>

                <a href="perfil.php?id=<?php echo $post['estudiante_id']; ?>" class="perfil">Ver Perfil</a>
                <a href="mensajes.php?receptor_id=<?php echo $post['estudiante_id']; ?>" class="chat">Chat</a>
            </div>
        </div>
    <?php endforeach; ?>
</body>

</html>