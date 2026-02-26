document.getElementById('formulario-contacto').addEventListener('submit', function(event) {
    event.preventDefault(); // Evitar envío tradicional

    const formData = new FormData(this);
    const resultado = document.getElementById('resultado');

    fetch('contacto_php.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultado.textContent = "¡Tu mensaje se envió correctamente! Nos pondremos en contacto contigo pronto.";
            this.reset(); // Limpiar formulario
        } else {
            resultado.textContent = "Hubo un error al enviar tu mensaje. Intenta nuevamente.";
            resultado.style.color = "red";
        }
    })
    .catch(error => {
        console.error('Error:', error);
        resultado.textContent = "Ocurrió un error inesperado. Intenta nuevamente más tarde.";
        resultado.style.color = "red";
    });
});
