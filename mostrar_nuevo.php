<?php
include 'conexion.php'; // Incluye la conexión a la base de datos

// Consulta para obtener los datos del modelo con id_Modelo
$id_Modelo = 16;
$sql = "SELECT m.nombreModelo, m.precio, i.imagenIzquierda, i.imagenInterior, i.imagenDerecha, 
        mo.motor, mo.combustible, mo.transmision, mo.traccion, 
        c.cilindrada, c.configuracion, c.potencia, c.torque,
        ch.suspDelantera, ch.suspTrasera, ch.frenosDelanteros, ch.frenosTraseros,
        in_.matAsientos, in_.asientos, in_.climatizacion, in_.radio,
        d.longitud, d.ancho, d.altura, d.peso
        FROM modelo m
        JOIN imagenAutos i ON m.id_Modelo = i.id_Modelo
        JOIN motor mo ON m.id_Modelo = mo.id_Modelo
        JOIN caracteristicas c ON m.id_Modelo = c.id_Modelo
        JOIN chasis ch ON m.id_Modelo = ch.id_Modelo
        JOIN interior in_ ON m.id_Modelo = in_.id_Modelo
        JOIN dimensiones d ON m.id_Modelo = d.id_Modelo
        WHERE m.id_Modelo = ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Verificar si la preparación fue exitosa
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}

// Vincular parámetros y ejecutar la consulta
$stmt->bind_param("i", $id_Modelo);
$stmt->execute();
$result = $stmt->get_result();

// Validar si hay resultados
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No se encontraron datos para el modelo especificado.");
}

// Cerrar el statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MALBIS - <?php echo htmlspecialchars($row['nombreModelo']); ?></title>
    <link rel="icon" href="./images/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_mostrarAuto.css">
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

    <!-- Contenedor para el título del vehículo y el precio -->
    <div class="contenedor-titulo-precio-vehiculo">
        <h1 class="titulo-vehiculo"><?php echo htmlspecialchars($row['nombreModelo']); ?></h1>
        <span class="precio-vehiculo">Precio: $<?php echo number_format($row['precio'], 2); ?></span>
    </div>
    <hr class="divisor-titulo-vehiculo">
    
    <!-- Contenedor para las imágenes del vehículo -->
    <div class="contenedor-imagenes-vehiculo">
        <img src="<?php echo htmlspecialchars($row['imagenIzquierda']); ?>" alt="Imagen izquierda del vehículo">
        <img src="<?php echo htmlspecialchars($row['imagenInterior']); ?>" alt="Imagen interior del vehículo">
        <img src="<?php echo htmlspecialchars($row['imagenDerecha']); ?>" alt="Imagen derecha del vehículo">
    </div>

    <!-- Características del Vehículo -->
    <section class="detalles-vehiculo">
        <div class="botones-detalles">
            <button class="boton-pestana" onclick="showTab('motor')">Motor</button>
            <button class="boton-pestana" onclick="showTab('caracteristicas')">Características Técnicas</button>
            <button class="boton-pestana" onclick="showTab('chasis')">Chasis y Suspensión</button>
            <button class="boton-pestana" onclick="showTab('interior')">Interior</button>
            <button class="boton-pestana" onclick="showTab('dimensiones')">Dimensiones y Pesos</button>
        </div>

        <!-- Pestaña Motor -->
        <div id="motor" class="contenido-pestana">
            <h2>Motor</h2>
            <div class="tarjeta">
                <div class="texto-tarjeta">
                    <p><strong>Motor:</strong> <?php echo htmlspecialchars($row['motor']); ?></p>
                    <p><strong>Combustible:</strong> <?php echo htmlspecialchars($row['combustible']); ?></p>
                    <p><strong>Transmisión:</strong> <?php echo htmlspecialchars($row['transmision']); ?></p>
                    <p><strong>Tracción:</strong> <?php echo htmlspecialchars($row['traccion']); ?></p>
                </div>
                <div class="imagen-tarjeta">
                    <img src="./images/motor.jpg" alt="Imagen del Motor">
                </div>
            </div>
        </div>

        <!-- Pestaña Características Técnicas -->
        <div id="caracteristicas" class="contenido-pestana">
            <h2>Características Técnicas</h2>
            <div class="tarjeta">
                <div class="texto-tarjeta">
                    <p><strong>Cilindrada:</strong> <?php echo htmlspecialchars($row['cilindrada']); ?></p>
                    <p><strong>Configuración:</strong> <?php echo htmlspecialchars($row['configuracion']); ?></p>
                    <p><strong>Potencia Máxima:</strong> <?php echo htmlspecialchars($row['potencia']); ?></p>
                    <p><strong>Torque Máximo:</strong> <?php echo htmlspecialchars($row['torque']); ?></p>
                </div>
                <div class="imagen-tarjeta">
                    <img src="./images/caracteristicas.jpg" alt="Imagen del Chasis y Suspensión">
                </div>
            </div>
        </div>

        <!-- Pestaña Chasis y Suspensión -->
        <div id="chasis" class="contenido-pestana">
            <h2>Chasis y Suspensión</h2>
            <div class="tarjeta">
                <div class="texto-tarjeta">
                    <p><strong>Suspensión Delantera:</strong> <?php echo htmlspecialchars($row['suspDelantera']); ?></p>
                    <p><strong>Suspensión Trasera:</strong> <?php echo htmlspecialchars($row['suspTrasera']); ?></p>
                    <p><strong>Frenos Delanteros:</strong> <?php echo htmlspecialchars($row['frenosDelanteros']); ?></p>
                    <p><strong>Frenos Traseros:</strong> <?php echo htmlspecialchars($row['frenosTraseros']); ?></p>
                </div>
                <div class="imagen-tarjeta">
                    <img src="./images/chasis.jpg" alt="Imagen del Chasis y Suspensión">
                </div>
            </div>
        </div>

        <!-- Pestaña Interior -->
        <div id="interior" class="contenido-pestana">
            <h2>Interior</h2>
            <div class="tarjeta">
                <div class="texto-tarjeta">
                    <p><strong>Material de Asientos:</strong> <?php echo htmlspecialchars($row['matAsientos']); ?></p>
                    <p><strong>Asientos:</strong> <?php echo htmlspecialchars($row['asientos']); ?></p>
                    <p><strong>Climatización:</strong> <?php echo htmlspecialchars($row['climatizacion']); ?></p>
                    <p><strong>Radio:</strong> <?php echo htmlspecialchars($row['radio']); ?></p>
                </div>
                <div class="imagen-tarjeta">
                    <img src="./images/interior.jpg" alt="Imagen del Interior">
                </div>
            </div>
        </div>

        <!-- Pestaña Dimensiones y Pesos -->
        <div id="dimensiones" class="contenido-pestana">
            <h2>Dimensiones y Pesos</h2>
            <div class="tarjeta">
                <div class="texto-tarjeta">
                    <p><strong>Longitud:</strong> <?php echo htmlspecialchars($row['longitud']); ?></p>
                    <p><strong>Ancho:</strong> <?php echo htmlspecialchars($row['ancho']); ?></p>
                    <p><strong>Altura:</strong> <?php echo htmlspecialchars($row['altura']); ?></p>
                    <p><strong>Peso:</strong> <?php echo htmlspecialchars($row['peso']); ?></p>
                </div>
                <div class="imagen-tarjeta">
                    <img src="./images/dimensiones.jpg" alt="Imagen de las Dimensiones y Pesos">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contenedor para los colores disponibles y el botón -->
    <section class="contenedor-colores-boton">
        <div class="opciones-color">
            <p><strong>Colores Disponibles:</strong></p>
            <div class="opciones-color-interno">
                <div class="muestra-color" style="background-color: red;" title="Rojo"></div>
                <div class="muestra-color" style="background-color: black;" title="Negro"></div>
                <div class="muestra-color" style="background-color: #2f2f2f;" title="Plomo Oscuro"></div>
                <div class="muestra-color" style="background-color: #d3d3d3;" title="Gris"></div>
                <div class="muestra-color" style="background-color: white;" title="Blanco"></div>
            </div>
        </div>
        <a href="formulario_contrato.php" class="boton-solicitar-info">Pre-Solicitar Vehículo</a>
    </section>

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
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.contenido-pestana');
            tabs.forEach(tab => tab.style.display = 'none');
            document.getElementById(tabId).style.display = 'block';
        }
        document.addEventListener('DOMContentLoaded', function() {
            showTab('motor');
        });
    </script>
</body>
</html>
