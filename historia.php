<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HISTORIA - MALBIS - CONCESIONARIA DE AUTOS DE LUJO</title>
    <link rel="icon" href="./images/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_historia.css">
</head>
<body>
    <?php
    // Incluir el archivo de conexión
    include 'conexion.php'; 

    // Consulta para obtener los datos
    $sql = "SELECT nombreHist, desHist, imgHist FROM historia";
    $result = $conn->query($sql);

    // Verificar si hay resultados
    $textCards = [];
    $imageCards = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $textCards[] = [
                "title" => $row['nombreHist'],
                "description" => $row['desHist']
            ];
            $imageCards[] = $row['imgHist'];
        }
    } else {
        echo "<p>No se encontraron datos en la tabla 'historia'.</p>";
    }

    // Cerrar la conexión
    $conn->close();
    ?>
    
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

    <!-- Contenido Principal -->
    <div class="contenido">
        <div class="carrusel">
            <div class="carrusel-slide">
                <!-- Contenedor de la información -->
                <div class="carrusel-info">
                    <h3 id="carrusel-titulo"></h3>
                    <p id="carrusel-descripcion"></p>
                </div>
                <!-- Contenedor de la imagen -->
                <div class="carrusel-imagen">
                    <img id="carrusel-imagen" src="" alt="Imagen de la Nota">
                </div>
            </div>

            <!-- Botones de navegación del carrusel -->
            <button id="prev-slide" class="boton-carrusel">&#9664;</button>
            <button id="next-slide" class="boton-carrusel">&#9654;</button>
        </div>
        
        <!-- Indicador de páginas -->
        <div class="indicador-paginas">
            <span id="pagina-actual">1</span> / <span id="total-paginas"></span>
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
        // Variables de datos de PHP a JavaScript
        const textCards = <?php echo json_encode($textCards); ?>;
        const imageCards = <?php echo json_encode($imageCards); ?>;

        let currentIndex = 0;

        function updateSlide(index) {
            document.getElementById("carrusel-titulo").innerText = textCards[index].title;
            document.getElementById("carrusel-descripcion").innerText = textCards[index].description;
            document.getElementById("carrusel-imagen").src = imageCards[index];
            document.getElementById("pagina-actual").innerText = index + 1;
            document.getElementById("total-paginas").innerText = textCards.length;
        }

        document.getElementById("prev-slide").addEventListener("click", () => {
            currentIndex = (currentIndex - 1 + textCards.length) % textCards.length;
            updateSlide(currentIndex);
        });

        document.getElementById("next-slide").addEventListener("click", () => {
            currentIndex = (currentIndex + 1) % textCards.length;
            updateSlide(currentIndex);
        });

        // Inicializar el primer slide
        updateSlide(currentIndex);
    </script>
</body>
</html>
