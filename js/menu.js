// Menú hamburguesa para pantallas chicas
document.addEventListener('DOMContentLoaded', function () {
    var boton = document.querySelector('.menu-toggle');
    var nav = document.querySelector('.navbar');
    if (!boton || !nav) return;

    boton.addEventListener('click', function () {
        nav.classList.toggle('open');
    });

    nav.querySelectorAll('a').forEach(function (enlace) {
        enlace.addEventListener('click', function () {
            nav.classList.remove('open');
        });
    });
});
