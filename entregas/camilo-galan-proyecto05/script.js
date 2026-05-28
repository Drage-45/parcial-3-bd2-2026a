document.addEventListener("DOMContentLoaded", () => {

    // ==========================================
    // 1. MENÚ DROPDOWN
    // ==========================================
    const buttonDropdown = document.querySelectorAll('.dropbtn');

    buttonDropdown.forEach(button => {
        button.addEventListener('click', function () {

            const menuActivo = this.nextElementSibling;

            document.querySelectorAll('.dropdown-content.mostrar')
                .forEach(menu => {

                    if (menu !== menuActivo) {
                        menu.classList.remove('mostrar');
                    }

                });

            menuActivo.classList.toggle('mostrar');
        });
    });

    // ==========================================
    // 2. SLIDER
    // ==========================================
    const slider = document.querySelector('.slider');
    const slides = document.querySelectorAll('.slide-item');

    const btnNext = document.querySelector('.next-btn');
    const btnPrev = document.querySelector('.prev-btn');

    let indexActual = 0;

    function actualizarSlider() {

        if (slider) {
            slider.style.transform = `translateX(-${indexActual * 100}%)`;
        }

    }

    if (btnNext) {

        btnNext.addEventListener('click', () => {

            indexActual++;

            if (indexActual >= slides.length) {
                indexActual = 0;
            }

            actualizarSlider();

        });

    }

    if (btnPrev) {

        btnPrev.addEventListener('click', () => {

            indexActual--;

            if (indexActual < 0) {
                indexActual = slides.length - 1;
            }

            actualizarSlider();

        });

    }

    if (slider && slides.length > 0) {

        setInterval(() => {

            indexActual++;

            if (indexActual >= slides.length) {
                indexActual = 0;
            }

            actualizarSlider();

        }, 2500);

    }

    // ==========================================
    // 3. BASE DE DATOS DE PELÍCULAS
    // ==========================================
    const baseDeDatosPeliculas = {

        "The Mandalorian and Grogu": {
            imagen: "Images/madalorian-poster.jpeg",
            genero: "Acción / Sci-Fi • 120 min • +12",
            sinopsis: "El Mandaloriano y Grogu se embarcan en una nueva aventura a través de la galaxia."
        },

        "Super Mario Bros": {
            imagen: "Images/mario-poster.jpg",
            genero: "Animación / Aventura • 92 min • Todo Público",
            sinopsis: "Un fontanero llamado Mario viaja por un laberinto subterráneo para salvar a una princesa."
        },

        "El Diablo Viste a la Moda 2": {
            imagen: "Images/moda-poster.jpeg",
            genero: "Comedia / Drama • 110 min • +14",
            sinopsis: "Miranda Priestly regresa con nuevos desafíos en el mundo de la alta costura."
        },

        "Michael": {
            imagen: "Images/michael-poster.jpg",
            genero: "Documental / Música • 130 min • +12",
            sinopsis: "Un vistazo profundo a la vida y el legado del Rey del Pop, Michael Jackson."
        },

        "La Posesion de la Momia": {
            imagen: "Images/momia.jpg",
            genero: "Terror / Suspenso • 105 min • +18",
            sinopsis: "Un grupo de arqueólogos despierta una maldición ancestral al descubrir una tumba prohibida."
        },

        "Boda Sangrienta 2": {
            imagen: "Images/boda.jpg",
            genero: "Comedia / Terror • 95 min • +16",
            sinopsis: "La noche de bodas se convierte en una pesadilla cuando la familia revela su verdadero rostro."
        },

        "Scream 7": {
            imagen: "Images/scream.jpg",
            genero: "Terror / Suspenso • 115 min • +18",
            sinopsis: "Ghostface regresa para aterrorizar a un nuevo grupo de adolescentes con nuevas reglas."
        },

        "Proyecto Fin del Mundo": {
            imagen: "Images/Proyecto.jpg",
            genero: "Ciencia Ficción • 120 min • +14",
            sinopsis: "Un grupo de desarrolladores debe detener un algoritmo autónomo antes de que tome el control."
        }

    };

    // ==========================================
    // 4. BOTONES DE CARTELERA
    // ==========================================
    const botonesComprar = document.querySelectorAll('.btn-comprar');

    botonesComprar.forEach(boton => {

        boton.addEventListener('click', function () {

            const tarjeta = this.closest('.movie-card');

            const titulo =
                tarjeta.querySelector('h3').innerText;

            // Redirige a schedule.html
            window.location.href =
                `schedule.html?pelicula=${encodeURIComponent(titulo)}`;

        });

    });

    // ==========================================
    // 5. OBTENER PELÍCULA DE LA URL
    // ==========================================
    const parametros =
        new URLSearchParams(window.location.search);

    const peliculaSeleccionada =
        parametros.get('pelicula');

    // ==========================================
    // 6. PÁGINA schedule.html
    // ==========================================
    if (
        peliculaSeleccionada &&
        document.querySelector('.movie-poster')
    ) {

        const info =
            baseDeDatosPeliculas[peliculaSeleccionada];

        if (info) {

            document.querySelector('.movie-info h1').innerText =
                peliculaSeleccionada;

            document.querySelector('.movie-info .genre').innerText =
                info.genero;

            document.querySelector('.movie-info .synopsis').innerText =
                info.sinopsis;

            document.querySelector('.movie-poster').style.backgroundImage =
                `url('${info.imagen}')`;

            document.querySelector('.movie-poster').style.backgroundSize =
                'cover';

            document.querySelector('.movie-poster').style.backgroundPosition =
                'center';

        }

        // BOTÓN CONTINUAR A ASIENTOS
        const btnContinue =
            document.getElementById('btn-continue');

        if (btnContinue) {

            btnContinue.addEventListener('click', () => {

                window.location.href =
                    `seats.html?pelicula=${encodeURIComponent(peliculaSeleccionada)}`;

            });

        }

    }

    // ==========================================
    // 7. SELECCIÓN DE FECHAS Y HORARIOS
    // ==========================================
    window.selectItem = function (elemento, clase) {

        document.querySelectorAll(`.${clase}`)
            .forEach(item => {
                item.classList.remove('active');
            });

        elemento.classList.add('active');

    };

    // ==========================================
    // 8. PÁGINA seats.html
    // ==========================================
    const tituloAsientos =
        document.getElementById('titullo-asientos') ||
        document.getElementById('titulo-asientos');

    if (peliculaSeleccionada && tituloAsientos) {

        tituloAsientos.innerText =
            `Asientos para: ${peliculaSeleccionada}`;

    }

    const precioPorAsiento = 15000;

    const asientosDisponibles =
        document.querySelectorAll('.fila .asiento:not(.ocupado)');

    const listaAsientosSpan =
        document.getElementById('lista-asientos');

    const precioTotalSpan =
        document.getElementById('precio-total');

    const btnComprarFinal =
        document.getElementById('btn-comprar-final');

    // ==========================================
    // 9. ACTUALIZAR RESUMEN
    // ==========================================
    function actualizarResumenCompra() {

        const asientosSeleccionados =
            document.querySelectorAll('.fila .asiento.seleccionado');

        const cantidad =
            asientosSeleccionados.length;

        const nombresAsientos =
            [...asientosSeleccionados].map(
                asiento => asiento.dataset.id
            );

        if (listaAsientosSpan && precioTotalSpan) {

            listaAsientosSpan.innerText =
                cantidad > 0
                    ? nombresAsientos.join(', ')
                    : '-';

            precioTotalSpan.innerText =
                (cantidad * precioPorAsiento)
                    .toLocaleString('es-CO');

        }

        if (btnComprarFinal) {

            if (cantidad > 0) {

                btnComprarFinal.removeAttribute('disabled');

            } else {

                btnComprarFinal.setAttribute('disabled', 'true');

            }

        }

    }

    // ==========================================
    // 10. EVENTOS DE ASIENTOS
    // ==========================================
    asientosDisponibles.forEach(asiento => {

        asiento.addEventListener('click', function () {

            if (!this.closest('.leyenda')) {

                this.classList.toggle('seleccionado');

                actualizarResumenCompra();

            }

        });

    });

    // ==========================================
    // 11. BOTÓN FINAL DE COMPRA
    // ==========================================
    if (btnComprarFinal) {

        btnComprarFinal.addEventListener('click', () => {

            alert(
                `¡Compra confirmada para ${peliculaSeleccionada || 'la película'}!\n\n` +
                `Asientos: ${listaAsientosSpan.innerText}\n` +
                `Total: $${precioTotalSpan.innerText}`
            );

        });

    }

});