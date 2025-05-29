<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "estudiante") {
    header("Location: login.php");
    exit();
}
require 'db.php';

// Obtener todas las ofertas de pasantías
$sql = "SELECT ofertas.id, ofertas.titulo, ofertas.descripcion, ofertas.requisitos, ofertas.duracion, ofertas.empresa_id, usuarios.nombre AS empresa 
        FROM ofertas 
        JOIN usuarios ON ofertas.empresa_id = usuarios.id";
$stmt = $pdo->query($sql);
$ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Estudiante</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="img/logo/logo.png" alt="Logo de InProMatch">
            </div>
            <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
            <a class="sidebar-btn" href="perfil.php">👤 Perfil</a>

            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">👤 Agregar Perfil</button>

            <a class="sidebar-btn" href="mensajes.php">💬 Mensajes</a>
            <a class="sidebar-btn cerrar-sesion" href="logout.php">🚪 Cerrar sesión</a>
        </aside>

        <main class="contenido">
            <header>
                <div class="logo">
                    <h1>InProMatch</h1>
                </div>
            </header>

            <h3>Ofertas Disponibles</h3>

            <section id="D_emp" class="contenedor-ofertas">
                <?php foreach ($ofertas as $oferta): ?>

                    <div class="oferta-card">
                        <h4><?php echo $oferta["titulo"]; ?> - <small><?php echo $oferta["empresa"]; ?></small></h4>
                        <p><?php echo $oferta["descripcion"]; ?></p>
                        <p><strong>Requisitos:</strong> <?php echo $oferta["requisitos"]; ?></p>
                        <p><strong>Duración:</strong> <?php echo $oferta["duracion"]; ?></p>

                        <div class="botones-oferta">
                            <form action="postular.php" method="POST">
                                <input type="hidden" name="oferta_id" value="<?php echo $oferta['id']; ?>">
                                <button type="submit" class="btn-postular">Postularme</button>
                            </form>

                            <a href="mensajes.php?receptor_id=<?php echo $oferta['empresa_id']; ?>" class="btn-chat" title="Chatear con la empresa">
                                <img src="img/icon/mensaje.png" alt="Chat"> Chat
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>


            <!-- Button trigger modal -->
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body"> ... </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>



            <footer>
                <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
            </footer>
            <!--//////// PLUGINGS DE JAVACRIPTS /////////-->
            <!-- jQuery 3 -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <script src="js/script.js"></script>



</body>

</html>