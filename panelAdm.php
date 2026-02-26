<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
    <link rel="stylesheet" href="./css/panelAdm.css">
</head>
<body>
    <!-- Barra de Menú Superior -->
    <div class="barra-superior">
        <div class="barra-superior-logo">
            <img src="./images/logo4.jpg" alt="Logo de la Empresa">
        </div>
        <div class="barra-superior-menu">
            <a href="cierreinicio.php">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Contenedor del Panel -->
    <div class="panel-contenedor">
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/presolicitudes.jpg" alt="Ver Pre-Solicitudes">
            </div>
            <a href="./presolicitudes.php" class="panel-boton">Ver Pre-Solicitudes</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/carrusell.jpg" alt="Editar Carrusel">
            </div>
            <a href="./editcarrusell.php" class="panel-boton">Editar Carrusel</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/presentacion.jpg" alt="Editar Presentación">
            </div>
            <a href="./editpresentacion.php" class="panel-boton">Editar Presentación</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/adestacados.jpg" alt="Editar Autos Destacados">
            </div>
            <a href="./editdestacados.php" class="panel-boton">Editar Autos Destacados</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/testimonios.jpg" alt="Editar Testimonios">
            </div>
            <a href="./edittestimonios.php" class="panel-boton">Editar Testimonios</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/reconocimientos.jpg" alt="Editar Reconocimientos">
            </div>
            <a href="./editrecon.php" class="panel-boton">Editar Reconocimientos</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/mensajes.jpg" alt="Leer Mensajes">
            </div>
            <a href="./mensajes.php" class="panel-boton">Leer Mensajes</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/catalogo.jpg" alt="Editar Catálogo">
            </div>
            <a href="./editcatalogo.php" class="panel-boton">Editar Catálogo</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/prox.jpg" alt="Editar Proximamente">
            </div>
            <a href="./editprox.php" class="panel-boton">Editar Proximamente</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/historias.jpg" alt="Editar Historias">
            </div>
            <a href="./edithistoria.php" class="panel-boton">Editar Historias</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/modelos.jpg" alt="Editar Modelos">
            </div>
            <a href="./editmodelos.php" class="panel-boton">Editar Modelos</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/marca.jpg" alt="Editar Marcas">
            </div>
            <a href="./editmarcas.php" class="panel-boton">Editar Marcas</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/imga.jpg" alt="Editar Imagen de los Autos">
            </div>
            <a href="./editimg.php" class="panel-boton">Editar Imagenes Autos</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/engine.jpg" alt="Editar Motor">
            </div>
            <a href="./editmotor.php" class="panel-boton">Editar Motor</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/caract.jpg" alt="Editar Caracteristicas">
            </div>
            <a href="./editcaract.php" class="panel-boton">Editar Características</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/chasiss.jpg" alt="Editar Chasis">
            </div>
            <a href="./editchasis.php" class="panel-boton">Editar Chasis</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/interiors.jpg" alt="Editar Interior">
            </div>
            <a href="./editinterior.php" class="panel-boton">Editar Interior</a>
        </div>
        <div class="panel-item">
            <div class="panel-imagen">
                <img src="./images/dimen.jpg" alt="Editar Dimensiones">
            </div>
            <a href="./editdimensiones.php" class="panel-boton">Editar Dimensiones</a>
        </div>
    </div>

    <!-- Pie de Página -->
    <footer>
        <p>&copy; 2024 Concesionaria de Autos. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
