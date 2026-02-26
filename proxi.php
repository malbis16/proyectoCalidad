<?php
// Incluir la conexión a la base de datos
include('conexion.php');

// Consultar todos los vehículos en la base de datos
$sql = "SELECT nombreProx, fechaProx, desProx, imagenProx FROM proximamente";
$result = $conn->query($sql);
$vehiculos = [];

if ($result->num_rows > 0) {
    // Almacenar los vehículos en un array
    while ($row = $result->fetch_assoc()) {
        $vehiculos[] = $row;
    }
} else {
    echo "No hay vehículos disponibles.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PRÓXIMAMENTE - MALBIS LUXXURY CAR DEALERSHIP</title>
    <link rel="icon" href="./images/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_proxi.css">
</head>
<body>
    <!-- Barra de Menú Superior -->
    <div class="barra-superior">
        <div class="barra-superior-logo">
            <img src="./images/logo4.jpg" alt="Logo de la Empresa">
        </div>
        <div class="barra-superior-menu">
            <a href="index.php">Inicio</a>
            <a href="historia.php">Historia</a>
            <a href="proxi.php">Próximamente</a>
            <a href="catalogo.php">Catálogo de Vehículos</a>
            <a href="contacto.html">Contacto</a>
        </div>
    </div>

    <!-- Contenido de la Página -->
    <div class="contenido">
        <h1>Vehículos Próximamente Disponibles</h1>
        <hr>
        <p>Nos complace anunciar que próximamente estarán disponibles en nuestro concesionario los siguientes vehículos.</p>

        <!-- Carrusel de Vehículos -->
        <div class="carrusel">
            <div class="carrusel-contenedor-pista">
                <div class="carrusel-pista">
                    <?php foreach ($vehiculos as $vehiculo): ?>
                    <!-- Tarjeta de vehículo -->
                    <div class="tarjeta-vehiculo">
                        <img src="<?php echo $vehiculo['imagenProx']; ?>" alt="Vehículo" class="imagen-vehiculo">
                        <div class="info-vehiculo">
                            <h2><?php echo $vehiculo['nombreProx']; ?></h2>
                            <p><strong>Fecha de Lanzamiento:</strong> <?php echo $vehiculo['fechaProx']; ?></p>
                            <p><?php echo $vehiculo['desProx']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Botones de navegación -->
            <div class="controles-carrusel">
                <button class="control-carrusel anterior" onclick="prevSlide()">&#10094;</button>
                <button class="control-carrusel siguiente" onclick="nextSlide()">&#10095;</button>
            </div>
        </div>
    </div>

    <!-- Menú de Pie de Página -->
    <footer>
        <div class="enlaces">
            <a href="./docs/privacidad.pdf" target="_blank">Política de Privacidad</a>
            <a href="./docs/terminos.pdf" target="_blank">Términos de Servicio</a>
            <a href="contacto.html">Contacto</a>
        </div>
    </footer>

    <script src="./javascript/script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pista = document.querySelector('.carrusel-pista');
            const diapositivas = Array.from(pista.children);
            const botonSiguiente = document.querySelector('.control-carrusel.siguiente');
            const botonAnterior = document.querySelector('.control-carrusel.anterior');
            const anchoDiapositiva = diapositivas[0].getBoundingClientRect().width;
            let indiceActual = 0;

            // Coloca las diapositivas una al lado de la otra
            diapositivas.forEach((diapositiva, indice) => {
                diapositiva.style.left = `${anchoDiapositiva * indice}px`;
            });

            // Función para mover la pista
            const moverADiapositiva = (indice) => {
                pista.style.transform = `translateX(-${anchoDiapositiva * indice}px)`;
                indiceActual = indice;
            };

            // Botones de navegación
            botonSiguiente.addEventListener('click', () => {
                moverADiapositiva((indiceActual + 1) % diapositivas.length);
            });
            botonAnterior.addEventListener('click', () => {
                moverADiapositiva((indiceActual - 1 + diapositivas.length) % diapositivas.length);
            });

            // Opcional: Hacer que el carrusel avance automáticamente
            const siguienteDiapositiva = () => {
                moverADiapositiva((indiceActual + 1) % diapositivas.length);
            };

            const avanceAutomatico = () => {
                siguienteDiapositiva();
                setTimeout(avanceAutomatico, 10000); // Cambiar cada 10 segundos
            };
            avanceAutomatico();
        });
    </script>

</body>
</html>
