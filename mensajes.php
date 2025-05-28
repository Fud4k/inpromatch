<?php
session_start();
require 'db.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];

// Verificar tipo de usuario para redirigir a su dashboard
$sqlTipo = "SELECT tipo FROM usuarios WHERE id = :id";
$stmtTipo = $pdo->prepare($sqlTipo);
$stmtTipo->execute([':id' => $usuario_id]);
$usuarioTipo = $stmtTipo->fetchColumn();
$dashboard = ($usuarioTipo === 'empresa') ? 'dashboard_empresa.php' : 'dashboard_estudiante.php';

// Obtener usuarios con los que se ha chateado
$sql = "
    SELECT DISTINCT u.id, u.nombre
    FROM usuarios u
    JOIN mensajes m ON (u.id = m.emisor_id OR u.id = m.receptor_id)
    WHERE (m.emisor_id = :uid OR m.receptor_id = :uid) AND u.id != :uid
    ORDER BY m.fecha DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':uid' => $usuario_id]);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$receptor_id = $_GET['receptor_id'] ?? ($usuarios[0]['id'] ?? null);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mensajes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #163b5b;
            color: white;
            padding: 10px 20px;
        }

        .top-bar h2 {
            margin: 0;
        }

        .top-bar a {
            color: white;
            text-decoration: none;
            background: #007bff;
            padding: 8px 15px;
            border-radius: 5px;
        }

        .main {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .usuarios-lista {
            width: 25%;
            background: #f5f5f5;
            overflow-y: auto;
            border-right: 1px solid #ddd;
        }

        .usuarios-lista a {
            display: block;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }

        .usuarios-lista a:hover,
        .usuarios-lista a.activo {
            background: #e0e0e0;
        }

        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fff;
        }

        .chat-box {
            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
        }

        .mensaje-propio {
            text-align: right;
            background: #dcf8c6;
            margin: 5px;
            padding: 8px;
            border-radius: 10px;
            max-width: 60%;
            margin-left: auto;
        }

        .mensaje-ajeno {
            text-align: left;
            background: #f1f0f0;
            margin: 5px;
            padding: 8px;
            border-radius: 10px;
            max-width: 60%;
        }

        .escribiendo {
            font-style: italic;
            color: #999;
            margin-left: 10px;
        }

        #formulario-chat {
            display: flex;
            gap: 10px;
            padding: 10px;
            border-top: 1px solid #ccc;
            background: #fafafa;
        }

        #formulario-chat textarea {
            flex: 1;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            resize: none;
        }

        #formulario-chat button {
            background: #163b5b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .main {
                flex-direction: column;
            }

            .usuarios-lista {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #ddd;
            }

            .chat-container {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <h2>Mensajes</h2>
        <a href="<?php echo $dashboard; ?>">Home</a>
    </div>

    <div class="main">
        <div class="usuarios-lista">
            <?php foreach ($usuarios as $u): ?>
                <a href="mensajes.php?receptor_id=<?php echo $u['id']; ?>" class="<?php echo ($u['id'] == $receptor_id) ? 'activo' : ''; ?>">
                    <?php echo htmlspecialchars($u['nombre']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="chat-container">
            <?php if ($receptor_id): ?>
                <div class="chat-box" id="chat-box"></div>
                <div class="escribiendo" id="escribiendo"></div>

                <form id="formulario-chat" style="display: flex; flex-direction: column; gap: 10px; width: 100%; max-width: 600px; margin: 20px auto;">
                    <input type="hidden" name="receptor_id" value="<?php echo $receptor_id; ?>">

                    <textarea
                        name="mensaje"
                        id="mensaje"
                        rows="3"
                        placeholder="Escribe un mensaje..."
                        required
                        style="width: 100%; padding: 12px; font-size: 1rem; border: 1px solid #ccc; border-radius: 6px; resize: none;"></textarea>

                    <button
                        type="submit"
                        style="padding: 12px; font-size: 1rem; background-color: #163b5b; color: white; border: none; border-radius: 6px; cursor: pointer;">
                        Enviar
                    </button>
                </form>

                <script>
                    // Permitir enviar con Enter (y Shift+Enter para salto de línea)
                    document.getElementById("mensaje").addEventListener("keydown", function(event) {
                        if (event.key === "Enter" && !event.shiftKey) {
                            event.preventDefault(); // Evita salto de línea
                            document.getElementById("formulario-chat").dispatchEvent(new Event("submit")); // Dispara el envío
                        }
                    });
                </script>

            <?php else: ?>
                <div class="chat-box"><em>No hay usuarios con los que hayas conversado aún.</em></div>
            <?php endif; ?>
        </div>
    </div>

    <audio id="sonido-nuevo">
        <source src="audio/notify.mp3" type="audio/mpeg">
    </audio>

    <script>
        const usuarioId = <?php echo $usuario_id; ?>;
        const receptorId = <?php echo json_encode($receptor_id); ?>;
        let ultimoContenido = '';

        function obtenerMensajes() {
            if (!receptorId) return;
            fetch("chat_obtener.php?receptor_id=" + receptorId)
                .then(res => res.text())
                .then(html => {
                    if (html !== ultimoContenido) {
                        document.getElementById("chat-box").innerHTML = html;
                        document.getElementById("chat-box").scrollTop = document.getElementById("chat-box").scrollHeight;
                        if (ultimoContenido !== '') {
                            document.getElementById("sonido-nuevo").play();
                        }
                        ultimoContenido = html;
                    }
                });
        }

        function verificarEscribiendo() {
            if (!receptorId) return;
            fetch("escribiendo_estado.php?receptor_id=" + receptorId)
                .then(res => res.text())
                .then(texto => {
                    document.getElementById("escribiendo").innerText = texto;
                });
        }

        function enviarMensaje() {
            const mensajeInput = document.getElementById("mensaje");
            const mensaje = mensajeInput.value.trim();
            if (!mensaje) return;

            const form = new FormData(document.getElementById("formulario-chat"));
            fetch("chat_enviar.php", {
                method: "POST",
                body: form
            }).then(() => {
                mensajeInput.value = "";
                document.getElementById("escribiendo").innerText = "";
                obtenerMensajes();
            });
        }

        if (receptorId) {
            setInterval(obtenerMensajes, 1500);
            setInterval(verificarEscribiendo, 1500);
            obtenerMensajes();

            const formulario = document.getElementById("formulario-chat");
            const mensajeInput = document.getElementById("mensaje");

            formulario.addEventListener("submit", function(e) {
                e.preventDefault();
                enviarMensaje();
            });

            mensajeInput.addEventListener("keydown", function(event) {
                if (event.key === "Enter" && !event.shiftKey) {
                    event.preventDefault(); // evita salto de línea
                    enviarMensaje();
                }
            });

            mensajeInput.addEventListener("input", function() {
                fetch("escribiendo.php", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: "receptor_id=" + receptorId
                });
            });
        }
    </script>
</body>

</html>