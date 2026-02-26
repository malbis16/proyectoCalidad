<?php
// Incluir archivo de conexión
include 'conexion.php';

// Consulta para obtener autos destacados
$sql_autos_destacados = "SELECT autoDest, desDest, imageDest, paginaAuto FROM autos_destacados";
$result_autos_destacados = $conn->query($sql_autos_destacados);

// Verificar si la consulta de autos destacados ha fallado
if (!$result_autos_destacados) {
    die("Error en la consulta de autos destacados: " . $conn->error);
}

// Consulta para obtener testimonios
$sql_testimonios = "SELECT testimonio, persona FROM testimonios";
$result_testimonios = $conn->query($sql_testimonios);

// Verificar si la consulta de testimonios ha fallado
if (!$result_testimonios) {
    die("Error en la consulta de testimonios: " . $conn->error);
}

// Consulta para obtener los reconocimientos
$sql_reconocimientos = "SELECT premio, revista, imgRecon FROM reconocimientos";
$result_reconocimientos = $conn->query($sql_reconocimientos);

// Verificar si la consulta de reconocimientos ha fallado
if (!$result_reconocimientos) {
    die("Error en la consulta de reconocimientos: " . $conn->error);
}

// Consulta para obtener las imágenes del carrusel
$sql_carrusel = "SELECT imgCarrusel FROM indexCarrusel";
$result_carrusel = $conn->query($sql_carrusel);

// Verificar si la consulta ha fallado
if (!$result_carrusel) {
    die("Error en la consulta de carrusel: " . $conn->error);
}

// Consulta para obtener la información de la empresa
$sql_empresa = "SELECT nombreInfor, imgInfor, desInfor FROM indexInfor WHERE id_Infor = 1";
$result_empresa = $conn->query($sql_empresa);

// Verificar si la consulta ha devuelto resultados
if ($result_empresa) {
    $empresa = $result_empresa->fetch_assoc();
} else {
    die("Error en la consulta de información de la empresa: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MALBIS - LUXXURY CAR DEALERSHIP</title>
    <link rel="icon" href="./images/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_index.css">
</head>
<body>
    <!-- Barra de Menú Superior -->
    <div class="barra-superior">
        <div class="barra-superior-logo">
            <img src="./images/logo4.jpg" alt="Logo de la Empresa">
        </div>
        <div class="barra-superior-menu">
            <a href="inicio.html">Admin</a>
            <a href="index.php">Inicio</a>
            <a href="historia.php">Historia</a>
            <a href="proxi.php">Próximamente</a>
            <a href="catalogo.php">Catálogo de Vehículos</a>
            <a href="contacto.html">Contacto</a>
        </div>
    </div>

    <!-- Carrusel de Imágenes -->
    <div class="carrusel">
        <div class="carrusel-pista">
            <?php
            if ($result_carrusel->num_rows > 0) {
                // Salida de cada fila de resultados para generar las imágenes
                while($row = $result_carrusel->fetch_assoc()) {
                    echo '<div class="carrusel-slide">';
                    echo '<img src="' . $row['imgCarrusel'] . '" alt="Auto">';
                    echo '</div>';
                }
            } else {
                echo "<p>No hay imágenes disponibles para el carrusel.</p>";
            }
            ?>
        </div>
        <div class="carrusel-nav">
            <button id="prevBtn">&#10094;</button>
            <button id="nextBtn">&#10095;</button>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="contenido">
        <!-- Sección de Presentación de la Empresa -->
        <section class="empresa" id="inicio">
            <div class="empresa-contenido">
                <div class="empresa-imagen">
                    <!-- Usamos la variable imgInfor para la imagen -->
                    <img src="<?php echo $empresa['imgInfor']; ?>" alt="Logo de la Empresa" class="empresa-logo">
                </div>
                <div class="empresa-texto">
                    <!-- Usamos la variable nombreInfor para el nombre de la empresa -->
                    <h2>¡Bienvenidos a <?php echo $empresa['nombreInfor']; ?>!</h2>
                    <!-- Usamos la variable desInfor para la descripción -->
                    <p><?php echo $empresa['desInfor']; ?> <a href="catalogo.php" class="explora-catalogo">Explora nuestro catálogo</a> y encuentra el coche de tus sueños.</p>
                </div>
            </div>
        </section>

        <!-- Sección de Autos Destacados -->
        <section class="autos-destacados">
            <h3>Autos Destacados</h3>
            <div class="tarjetas-autos">
                <?php
                if ($result_autos_destacados->num_rows > 0) {
                    // Salida de cada fila de resultados
                    while($row = $result_autos_destacados->fetch_assoc()) {
                        echo '<div class="tarjeta-auto">';
                        // Cambiar el enlace para que apunte a la página dinámica del auto
                        echo '<a href="' . $row['paginaAuto'] . '">';
                        echo '<img src="' . $row['imageDest'] . '" alt="' . $row['autoDest'] . '">';
                        echo '</a>';
                        echo '<h4>' . $row['autoDest'] . '</h4>';
                        echo '<p>' . $row['desDest'] . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No hay autos destacados disponibles.</p>";
                }
                ?>
            </div>
        </section>

        <!-- Sección de Testimonios -->
        <section class="testimonios">
            <h3>Testimonios de Clientes</h3>
            <?php
            if ($result_testimonios->num_rows > 0) {
                // Salida de cada fila de resultados
                while($row = $result_testimonios->fetch_assoc()) {
                    echo '<div class="testimonio">';
                    echo '<p>"' . $row['testimonio'] . '" - <span>' . $row['persona'] . '</span></p>';
                    echo '</div>';
                }
            } else {
                echo "<p>No hay testimonios disponibles.</p>";
            }
            ?>
        </section>

        <!-- Reconocimientos -->
        <section class="reconocimientos">
            <h3>Reconocimientos</h3>
            <div class="tarjetas-reconocimiento">
                <?php
                if ($result_reconocimientos->num_rows > 0) {
                    // Salida de cada fila de resultados
                    while($row = $result_reconocimientos->fetch_assoc()) {
                        echo '<div class="tarjeta-reconocimiento">';
                        echo '<img src="' . $row['imgRecon'] . '" alt="' . $row['premio'] . '">';
                        echo '<p>"' . $row['premio'] . '" - <span>' . $row['revista'] . '</span></p>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No hay reconocimientos disponibles.</p>";
                }
                ?>
            </div>
        </section>

    </div>

    <!-- Menú de Pie de Página -->
    <footer>
        <div class="enlaces">
            <a href="./docs/privacidad.pdf" target="_blank">Política de Privacidad</a>
            <a href="./docs/terminos.pdf" target="_blank">Términos de Servicio</a>
            <a href="contacto.html">Contacto</a>
        </div>
    </footer>
    
    <?php
    // Cerrar la conexión
    $conn->close();
    ?>

    <script src="./javascript/script.js"></script>
    <script>
        // Manejo del carrusel
        const pista = document.querySelector('.carrusel-pista');
        const slides = Array.from(pista.children);
        const botonSiguiente = document.querySelector('#nextBtn');
        const botonAnterior = document.querySelector('#prevBtn');
        let indiceSlideActual = 0;

        function actualizarPosicionSlide() {
            pista.style.transform = `translateX(-${indiceSlideActual * 100}%)`;
        }

        function siguienteSlide() {
            indiceSlideActual = (indiceSlideActual + 1) % slides.length;
            actualizarPosicionSlide();
        }

        function anteriorSlide() {
            indiceSlideActual = (indiceSlideActual - 1 + slides.length) % slides.length;
            actualizarPosicionSlide();
        }

        // Añadir eventos de clic a los botones
        botonSiguiente.addEventListener('click', siguienteSlide);
        botonAnterior.addEventListener('click', anteriorSlide);

        // Cambiar imagen automáticamente cada 5 segundos sin sonido
        setInterval(siguienteSlide, 5000);
    </script>
</body>
</html>
