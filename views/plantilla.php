<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InProMatch - Plataforma de Pasantías y Horas Sociales</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Enlace al archivo CSS -->
    <script src="js/script.js"></script> <!-- Enlace al archivo JS -->
</head>

<!--/////////// CUERPO DOCUMENTO /////////////-->

<body>
    <?php
        /* SE CREO LA VARIABLE DIV COMO WRAPPER YA QUE LO POSEE LA MAYOR PARTE DE MODULOS */
        echo '<div class="wrapper">';
        include "modulos/cabecera.php";
        /* /////////////// MENU ////////////////// */
        include "modulos/menu.php";
        /* /////////////// CONTENIDO ////////////////// */
        if (isset($_GET["ruta"])) {
            if (
                $_GET["ruta"] == "index" ||
                $_GET["ruta"] == "usuarios" ||
                $_GET["ruta"] == "categorias" ||
                $_GET["ruta"] == "productos" ||
                $_GET["ruta"] == "clientes"  ||
                $_GET["ruta"] == "ventas" ||
                $_GET["ruta"] == "crear-venta" ||
                $_GET["ruta"] == "reportes" ||
                $_GET["ruta"] == "salir"
            ) {
                include "modulos/" . $_GET["ruta"] . ".php";
            } else {
                include "modulos/404.php";
            }
        } else {
            include "modulos/inicio.php";
        }

        /* /////////////// FOOTER ////////////////// */
        include "modulos/footer.php";
        echo '</div>';
    ?>
    <script src="views/js/plantilla.js"></script>
    <script src="views/js/usuarios.js"></script>
    <script src="views/js/categorias.js"></script>
    <script src="views/js/productos.js"></script>

</body>

</html>