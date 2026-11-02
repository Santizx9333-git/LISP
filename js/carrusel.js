/* =========================================
   CARRUSELES LISP (proyectos e integrantes)
   - Avance automático suave
   - Arrastre con mouse / dedo
   - Flechas y puntos
   - Modal de detalle para proyectos
========================================= */

(function () {
    'use strict';

    var reducirMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Modal de proyecto ---------- */

    var modal = document.getElementById('projectModal');
    var ultimoFoco = null;

    var modalEls = modal ? {
        imagen: modal.querySelector('#pmImage'),
        estado: modal.querySelector('#pmStatus'),
        titulo: modal.querySelector('#pmTitle'),
        desc: modal.querySelector('#pmDesc'),
        tech: modal.querySelector('#pmTech'),
        cerrar: modal.querySelector('.project-modal-close')
    } : null;

    function abrirModal(tarjeta) {
        if (!modal) return;

        var img = tarjeta.querySelector('.project-image img');
        var placeholder = tarjeta.querySelector('.project-placeholder');
        var estado = tarjeta.querySelector('.project-status');
        var titulo = tarjeta.querySelector('h3');
        var desc = tarjeta.querySelector('.project-content p');
        var tech = tarjeta.querySelector('.technologies');

        modalEls.imagen.innerHTML = '';
        if (img) {
            var grande = document.createElement('img');
            grande.src = img.getAttribute('src');
            grande.alt = img.getAttribute('alt') || '';
            modalEls.imagen.appendChild(grande);
        } else if (placeholder) {
            modalEls.imagen.appendChild(placeholder.cloneNode(true));
        }

        modalEls.estado.textContent = estado ? estado.textContent.trim() : '';
        modalEls.estado.className = 'project-status' +
            (estado && estado.classList.contains('finished') ? ' finished' : '');

        modalEls.titulo.textContent = titulo ? titulo.textContent.trim() : '';
        modalEls.desc.textContent = desc ? desc.textContent.trim() : '';

        modalEls.tech.innerHTML = '';
        if (tech) {
            Array.prototype.forEach.call(tech.children, function (chip) {
                modalEls.tech.appendChild(chip.cloneNode(true));
            });
        }

        ultimoFoco = document.activeElement;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('modal-open');
        modalEls.cerrar.focus();
    }

    function cerrarModal() {
        if (!modal || !modal.classList.contains('is-open')) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('modal-open');
        if (ultimoFoco && ultimoFoco.focus) ultimoFoco.focus();
    }

    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target.closest('[data-close]')) cerrarModal();
        });

        document.addEventListener('keydown', function (e) {
            if (!modal.classList.contains('is-open')) return;
            if (e.key === 'Escape') cerrarModal();
            // el único elemento enfocable es el botón de cerrar
            if (e.key === 'Tab') {
                e.preventDefault();
                modalEls.cerrar.focus();
            }
        });
    }

    /* ---------- Carrusel ---------- */

    function Carrusel(root) {
        var viewport = root.querySelector('.carousel-viewport');
        var track = root.querySelector('.carousel-track');
        var slides = Array.prototype.slice.call(track.children);
        var btnPrev = root.querySelector('.carousel-btn.prev');
        var btnNext = root.querySelector('.carousel-btn.next');
        var dotsBox = root.querySelector('.carousel-dots');

        var retardo = reducirMovimiento ? 0 : (parseInt(root.getAttribute('data-autoplay'), 10) || 0);
        var abreModal = root.getAttribute('data-modal') === 'projectModal';

        var indice = 0;
        var porVista = 1;
        var maxIndice = 0;
        var paso = 0;
        var timer = null;
        var hover = false;

        var arrastrando = false;
        var seMovio = false;
        var bloquearClick = false;
        var inicioX = 0;
        var deltaX = 0;
        var idPuntero = null;

        /* --- medidas y render --- */

        function medir() {
            porVista = parseInt(getComputedStyle(root).getPropertyValue('--per-view'), 10) || 1;
            maxIndice = Math.max(0, slides.length - porVista);
            paso = slides.length > 1 ? slides[1].offsetLeft - slides[0].offsetLeft : 0;
            if (indice > maxIndice) indice = maxIndice;
            root.classList.toggle('is-static', maxIndice === 0);
            crearPuntos();
            render(false);
        }

        function render(animar) {
            track.style.transition = animar ? '' : 'none';
            track.style.transform = 'translate3d(' + (-indice * paso) + 'px, 0, 0)';
            if (!animar) { void track.offsetWidth; track.style.transition = ''; }
            actualizarPuntos();
        }

        function irA(i) {
            if (maxIndice === 0) return;
            if (i > maxIndice) i = 0;
            if (i < 0) i = maxIndice;
            indice = i;
            render(true);
        }

        /* --- puntos --- */

        function crearPuntos() {
            if (!dotsBox) return;
            dotsBox.innerHTML = '';
            var total = maxIndice + 1;
            if (maxIndice === 0 || total > 10) return;
            for (var i = 0; i < total; i++) {
                (function (n) {
                    var b = document.createElement('button');
                    b.type = 'button';
                    b.setAttribute('aria-label', 'Ir a la posición ' + (n + 1));
                    b.addEventListener('click', function () { irA(n); iniciar(); });
                    dotsBox.appendChild(b);
                })(i);
            }
        }

        function actualizarPuntos() {
            if (!dotsBox) return;
            Array.prototype.forEach.call(dotsBox.children, function (b, i) {
                b.classList.toggle('active', i === indice);
            });
        }

        /* --- autoplay --- */

        function detener() {
            if (timer) { clearInterval(timer); timer = null; }
        }

        function iniciar() {
            detener();
            if (!retardo || maxIndice === 0) return;
            timer = setInterval(function () {
                if (hover || arrastrando || document.hidden ||
                    document.body.classList.contains('modal-open')) return;
                irA(indice + 1);
            }, retardo);
        }

        /* --- flechas --- */

        if (btnPrev) btnPrev.addEventListener('click', function () { irA(indice - 1); iniciar(); });
        if (btnNext) btnNext.addEventListener('click', function () { irA(indice + 1); iniciar(); });

        /* --- pausa al pasar el mouse / enfocar --- */

        root.addEventListener('pointerenter', function (e) {
            if (e.pointerType === 'mouse') hover = true;
        });
        root.addEventListener('pointerleave', function (e) {
            if (e.pointerType === 'mouse') hover = false;
        });

        /* --- arrastre (mouse y táctil) --- */

        viewport.addEventListener('pointerdown', function (e) {
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            if (maxIndice === 0) return;
            arrastrando = true;
            seMovio = false;
            inicioX = e.clientX;
            deltaX = 0;
            idPuntero = e.pointerId;
        });

        viewport.addEventListener('pointermove', function (e) {
            if (!arrastrando || e.pointerId !== idPuntero) return;
            deltaX = e.clientX - inicioX;

            if (!seMovio) {
                if (Math.abs(deltaX) < 6) return;
                seMovio = true;
                track.style.transition = 'none';
                viewport.classList.add('is-dragging');
                try { viewport.setPointerCapture(idPuntero); } catch (err) { /* nada */ }
            }

            // resistencia en los extremos
            var pos = -indice * paso + deltaX;
            var limite = -maxIndice * paso;
            if (pos > 0) pos = pos * 0.35;
            if (pos < limite) pos = limite + (pos - limite) * 0.35;

            track.style.transform = 'translate3d(' + pos + 'px, 0, 0)';
        });

        function terminarArrastre(cancelado) {
            if (!arrastrando) return;
            arrastrando = false;
            viewport.classList.remove('is-dragging');

            if (seMovio && !cancelado && paso > 0) {
                var saltos = Math.round(-deltaX / paso);
                if (saltos === 0 && Math.abs(deltaX) > 40) saltos = deltaX < 0 ? 1 : -1;
                indice = Math.max(0, Math.min(maxIndice, indice + saltos));
            }

            render(true);
            iniciar();

            if (seMovio) {
                bloquearClick = true;
                setTimeout(function () { bloquearClick = false; }, 60);
            }
        }

        viewport.addEventListener('pointerup', function (e) {
            if (e.pointerId === idPuntero) terminarArrastre(false);
        });
        viewport.addEventListener('pointercancel', function (e) {
            if (e.pointerId === idPuntero) terminarArrastre(true);
        });

        // si se arrastró, el click que sigue no debe abrir nada
        viewport.addEventListener('click', function (e) {
            if (bloquearClick) { e.stopPropagation(); e.preventDefault(); }
        }, true);

        // evita arrastrar imágenes / enlaces con el drag nativo del navegador
        viewport.addEventListener('dragstart', function (e) { e.preventDefault(); });

        // el navegador puede desplazar el viewport al enfocar un elemento oculto
        viewport.addEventListener('scroll', function () { viewport.scrollLeft = 0; });

        /* --- tarjetas clickeables (modal) --- */

        slides.forEach(function (slide, i) {
            if (abreModal) {
                var nombre = slide.querySelector('h3');
                slide.setAttribute('role', 'button');
                slide.setAttribute('tabindex', '0');
                slide.setAttribute('aria-label',
                    'Ver detalles de ' + (nombre ? nombre.textContent.trim() : 'proyecto'));

                slide.addEventListener('click', function () { abrirModal(slide); });
                slide.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        abrirModal(slide);
                    }
                });
            }

            // al enfocar con teclado una tarjeta fuera de vista, mover el carrusel
            slide.addEventListener('focusin', function () {
                if (i < indice || i >= indice + porVista) {
                    indice = Math.max(0, Math.min(maxIndice, i));
                    render(true);
                }
                viewport.scrollLeft = 0;
            });
        });

        /* --- ajuste al redimensionar --- */

        var resizeTimer = null;
        window.addEventListener('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(medir, 120);
        });

        medir();
        iniciar();
    }

    Array.prototype.forEach.call(document.querySelectorAll('[data-carousel]'), Carrusel);
})();
