// Ocultar y mostrar la barra de menú de manera gradual
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.barra-superior');
    let lastScrollTop = 0;
    const delta = 5; // Valor para considerar si el scroll es significativo

    window.addEventListener('scroll', function() {
        let currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

        if (Math.abs(lastScrollTop - currentScrollTop) <= delta) {
            return; // Si el cambio de scroll es menor que el delta, no hacer nada
        }

        if (currentScrollTop > lastScrollTop) {
            // Desplazamiento hacia abajo
            navbar.style.top = '-60px'; // Ajusta este valor al alto de la barra de menú
        } else {
            // Desplazamiento hacia arriba
            navbar.style.top = '0';
        }

        lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; // Para Chrome, Safari y Opera
    });
});