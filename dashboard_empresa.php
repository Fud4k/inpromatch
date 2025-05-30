<?php
session_start();
if (!isset($_SESSION["usuario_id"]) || $_SESSION["tipo"] !== "empresa") {
    header("Location: login.php");
    exit();
}
require 'db.php';

// Obtener ofertas publicadas por la empresa
$sql = "SELECT * FROM ofertas WHERE empresa_id = :empresa_id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":empresa_id" => $_SESSION["usuario_id"]]);
$ofertas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empresa</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="img/logo/logo.png" alt="Logo de InProMatch">
            </div>
            <h2>Bienvenido, <?php echo $_SESSION["nombre"]; ?>!</h2>
            <a class="sidebar-btn" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">➕ Crear Nueva Oferta</a>
            <a class="sidebar-btn" href="perfil.php">👤 Perfil Empresarial</a>
            <a class="sidebar-btn" href="postulaciones_empresa.php" >📥 Ver Postulaciones</a>
            <a class="sidebar-btn" href="mensajes.php">💬 Mensajes</a>
            <a class="sidebar-btn cerrar-sesion" href="logout.php">🚪 Cerrar sesión</a>
        </aside>

        <!-- Contenido principal -->
            <!-- Sección con fondo minimalista exclusiva para el panel de empresa -->


            <main class="contenido">
                <section id="BG_empresa">
                    <header>
                        <div class="logo">
                            <h1>InProMatch</h1>
                        </div>
                    </header>

                    

                    <h3>Tus Ofertas Publicadas</h3>
                        <section id="D_emp" class="contenedor-ofertas">
                        
                        <?php foreach ($ofertas as $oferta): ?>
                            <div class="oferta-card">
                                <h4><?php echo $oferta["titulo"]; ?></h4>
                                <p><?php echo $oferta["descripcion"]; ?></p>
                                <p><strong>Requisitos:</strong> <?php echo $oferta["requisitos"]; ?></p>
                                <p><strong>Duración:</strong> <?php echo $oferta["duracion"]; ?></p>

                                <div class="botones-oferta">
                                    <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#modalEditar<?php echo $oferta['id']; ?>">✏️ Editar</button>
                                    <form action="ofertas_handler.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta oferta?');" style="display:inline;">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?php echo $oferta['id']; ?>">
                                        <button type="submit" class="btn-eliminar">🗑️ Eliminar</button>
                                    </form>
                                </div>
                            </div>
                            <!-- Modal de edición para esta oferta -->
                            <div class="modal fade" id="modalEditar<?php echo $oferta['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="ofertas_handler.php" method="POST" class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Editar Oferta</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="accion" value="editar">
                                            <input type="hidden" name="id" value="<?php echo $oferta['id']; ?>">

                                            <label for="titulo">Título:</label>
                                            <input type="text" name="titulo" value="<?php echo htmlspecialchars($oferta['titulo']); ?>" required>

                                            <label for="descripcion">Descripción:</label>
                                            <textarea name="descripcion" required><?php echo htmlspecialchars($oferta['descripcion']); ?></textarea>

                                            <label for="requisitos">Requisitos:</label>
                                            <textarea name="requisitos" required><?php echo htmlspecialchars($oferta['requisitos']); ?></textarea>

                                            <label for="duracion">Duración:</label>
                                            <input type="text" name="duracion" value="<?php echo htmlspecialchars($oferta['duracion']); ?>" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </section>
                </section>
            </main>

        </main>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Publicar Nueva Oferta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="crear_oferta.php" method="POST">
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>

                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" required></textarea>

                        <label for="requisitos">Requisitos:</label>
                        <textarea id="requisitos" name="requisitos" required></textarea>

                        <label for="duracion">Duración:</label>
                        <input type="text" id="duracion" name="duracion" required>

                        <button type="submit">Publicar Oferta</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 InProMatch. Todos los derechos reservados.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>