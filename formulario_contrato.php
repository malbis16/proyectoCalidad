<?php
// Incluir la conexión a la base de datos
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $sucursal = $_POST['sucursal'];
    $modelo_auto = $_POST['modelo-auto'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $pais = $_POST['pais'];
    $idioma = $_POST['idioma'];

    // Consulta SQL para insertar los datos en la tabla 'presolicitud'
    $sql = "INSERT INTO presolicitud (nombre, apellidos, direccion, ciudad, pais, modelo, sucursal, idioma_preferido)
            VALUES ('$nombre', '$apellidos', '$direccion', '$ciudad', '$pais', '$modelo_auto', '$sucursal', '$idioma')";

    if ($conn->query($sql) === TRUE) {
        $mensaje = "Pre-solicitud enviada exitosamente.";
    } else {
        $mensaje = "Error al enviar la pre-solicitud: " . $conn->error;
    }

    // $conn->close(); // Solo cerrar al final si es necesario
}

// Obtener los modelos disponibles desde la tabla 'modelo'
$sql_modelos = "SELECT nombreModelo FROM modelo";
$resultado_modelos = $conn->query($sql_modelos);
$modelos = [];
if ($resultado_modelos->num_rows > 0) {
    while ($row = $resultado_modelos->fetch_assoc()) {
        $modelos[] = $row['nombreModelo'];
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTRATO - MALBIS - CONCESIONARIO DE AUTOS DE LUJO</title>
    <link rel="icon" href="./images/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="./css/styles_formulario.css">
</head>
<body style="background-image: url('./images/fondoForm.jpeg'); background-size: cover; background-position: center;">
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

    <!-- Formulario de Pre-Solicitud -->
    <div class="formulario-contacto">
        <h2>Formulario de Pre-Solicitud del Vehículo</h2>
        <hr>
        <?php if (isset($mensaje)) : ?>
            <p style="text-align: center; color: #b30000;"><?php echo $mensaje; ?></p>
        <?php endif; ?>
        <form id="formulario-solicitud" method="post">
            <div class="grupo-formulario">
                <label for="sucursal">Sucursal deseada:</label>
                <select id="sucursal" name="sucursal" required>
                    <option value="" disabled selected>Seleccione una sucursal</option>
                    <option value="Sucursal Japón">Sucursal Japón</option>
                    <option value="Sucursal EE.UU.">Sucursal EE.UU.</option>
                    <option value="Sucursal Brasil">Sucursal Brasil</option>
                    <option value="Sucursal España">Sucursal España</option>
                    <option value="Sucursal Alemania">Sucursal Alemania</option>
                </select>
            </div>

            <div class="grupo-formulario">
                <label for="modelo-auto">Modelo de auto deseado:</label>
                <select id="modelo-auto" name="modelo-auto" required>
                    <option value="" disabled selected>Seleccione un modelo</option>
                    <?php foreach ($modelos as $modelo) : ?>
                        <option value="<?php echo $modelo; ?>"><?php echo $modelo; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grupo-formulario">
                <div class="fila-formulario">
                    <div class="columna-formulario">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="columna-formulario">
                        <label for="apellidos">Apellidos:</label>
                        <input type="text" id="apellidos" name="apellidos" required>
                    </div>
                </div>
            </div>

            <div class="grupo-formulario">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>

            <div class="grupo-formulario">
                <div class="fila-formulario">
                    <div class="columna-formulario">
                        <label for="ciudad">Ciudad, País:</label>
                        <input type="text" id="ciudad" name="ciudad" required>
                    </div>
                    <div class="columna-formulario">
                        <label for="pais">País:</label>
                        <input type="text" id="pais" name="pais" required>
                    </div>
                </div>
            </div>

            <div class="grupo-formulario">
                <label for="idioma">Idioma preferido:</label>
                <select id="idioma" name="idioma" required>
                    <option value="" disabled selected>Seleccione un idioma</option>
                    <option value="Español">Español</option>
                    <option value="Inglés">Inglés</option>
                    <option value="Japonés">Japonés</option>
                    <option value="Portugués">Portugués</option>
                    <option value="Alemán">Alemán</option>
                </select>
            </div>

            <div class="politica">
                <label>
                    Acepto las <a href="./docs/politicas.pdf" target="_blank">políticas de tratamiento de datos personales</a>.
                    <input type="checkbox" name="politica" required>
                </label>
            </div>

            <button type="submit">Enviar Solicitud</button>
        </form>
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
</body>
</html>
