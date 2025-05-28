<?php
// chat.php - Interfaz completa estilo WhatsApp con AJAX, nombres, sonidos y "escribiendo..."
session_start();
require 'db.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION["usuario_id"];
$receptor_id = $_GET["receptor_id"] ?? null;

if (!$receptor_id || $receptor_id == $usuario_id) {
    echo "Receptor inválido.";
    exit();
}

// Obtener nombre del receptor
$stmt = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = :id");
$stmt->execute([':id' => $receptor_id]);
$receptor = $stmt->fetch();
$receptor_nombre = $receptor ? $receptor['nombre'] : 'Usuario';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat con <?php echo htmlspecialchars($receptor_nombre); ?></title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .chat-box {
            border: 1px solid #ccc;
            padding: 10px;
            height: 400px;
            overflow-y: auto;
            margin-bottom: 10px;
            background: #fff;
        }
        .mensaje-propio {
            text-align: right;
            background: #d1ffd1;
            padding: 5px;
            margin: 5px;
            border-radius: 5px;
        }
        .mensaje-ajeno {
            text-align: left;
            background: #f1f1f1;
            padding: 5px;
            margin: 5px;
            border-radius: 5px;
        }
        .escribiendo {
            font-style: italic;
            color: #888;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h2>Chat con <?php echo htmlspecialchars($receptor_nombre); ?></h2>

    <div class="chat-box" id="chat-box"></div>
    <div class="escribiendo" id="escribiendo"></div>

    <form id="formulario-chat">
        <input type="hidden" name="receptor_id" value="<?php echo $receptor_id; ?>">
        <textarea name="mensaje" id="mensaje" placeholder="Escribe un mensaje..." required></textarea>
        <button type="submit">Enviar</button>
    </form>

    <audio id="sonido-nuevo">
        <source src="audio/notify.mp3" type="audio/mpeg">
    </audio>

    <script>
        let ultimoContenido = '';
        let usuarioId = <?php echo $usuario_id; ?>;
        let receptorId = <?php echo $receptor_id; ?>;

        function obtenerMensajes() {
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

        setInterval(obtenerMensajes, 1500);
        obtenerMensajes();

        document.getElementById("formulario-chat").addEventListener("submit", function(e) {
            e.preventDefault();
            const form = new FormData(this);
            fetch("chat_enviar.php", {
                method: "POST",
                body: form
            }).then(() => {
                document.getElementById("mensaje").value = "";
                document.getElementById("escribiendo").innerText = "";
                obtenerMensajes();
            });
        });

        document.getElementById("mensaje").addEventListener("input", function() {
            fetch("escribiendo.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: "receptor_id=" + receptorId
            });
        });

        function verificarEscribiendo() {
            fetch("escribiendo_estado.php?receptor_id=" + receptorId)
                .then(res => res.text())
                .then(texto => {
                    document.getElementById("escribiendo").innerText = texto;
                });
        }

        setInterval(verificarEscribiendo, 1500);
    </script>
</body>
</html>
