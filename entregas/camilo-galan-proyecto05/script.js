// =============================================
// NAVBAR — oscurecer al hacer scroll
// =============================================
(function () {
    const navbar = document.getElementById("navbar");
    if (!navbar) return;
    const onScroll = () => navbar.classList.toggle("scrolled", window.scrollY > 10);
    window.addEventListener("scroll", onScroll, { passive: true });
    onScroll();
})();


document.addEventListener("DOMContentLoaded", () => {

    // =============================================
    // DROPDOWN — cierre al click fuera
    // =============================================
    document.querySelectorAll(".dropbtn").forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            const menu   = btn.nextElementSibling;
            const isOpen = menu.classList.contains("mostrar");
            document.querySelectorAll(".dropdown-content").forEach(m => m.classList.remove("mostrar"));
            if (!isOpen) menu.classList.add("mostrar");
        });
    });

    document.addEventListener("click", () => {
        document.querySelectorAll(".dropdown-content").forEach(m => m.classList.remove("mostrar"));
    });


    // =============================================
    // SLIDER — autoplay, dots, ken-burns
    // =============================================
    const sliderEl    = document.querySelector(".slider");
    const slides      = document.querySelectorAll(".slide-item");
    const dotsWrapper = document.getElementById("dots");

    if (sliderEl && slides.length) {

        let pos      = 0;
        let autoPlay = null;

        // Generar dots
        if (dotsWrapper) {
            slides.forEach((_, i) => {
                const dot = document.createElement("div");
                dot.classList.add("dot");
                if (i === 0) dot.classList.add("active");
                dot.addEventListener("click", () => { goTo(i); startAuto(); });
                dotsWrapper.appendChild(dot);
            });
        }

        function goTo(index) {
            slides[pos].classList.remove("active");
            if (dotsWrapper) dotsWrapper.children[pos]?.classList.remove("active");

            pos = (index + slides.length) % slides.length;

            sliderEl.style.transform = `translateX(-${pos * 100}%)`;
            slides[pos].classList.add("active");
            if (dotsWrapper) dotsWrapper.children[pos]?.classList.add("active");
        }

        function startAuto() {
            stopAuto();
            autoPlay = setInterval(() => goTo(pos + 1), 5500);
        }

        function stopAuto() {
            clearInterval(autoPlay);
        }

        document.querySelector(".next-btn")?.addEventListener("click", () => { goTo(pos + 1); startAuto(); });
        document.querySelector(".prev-btn")?.addEventListener("click", () => { goTo(pos - 1); startAuto(); });

        const container = sliderEl.closest(".slider-container");
        container?.addEventListener("mouseenter", stopAuto);
        container?.addEventListener("mouseleave", startAuto);

        slides[0].classList.add("active");
        startAuto();
    }


    // =============================================
    // ASIENTOS — selección + precio dinámico
    // =============================================
    const asientos      = document.querySelectorAll(".asiento:not(.ocupado)");
    const inputAsientos = document.getElementById("asientos");
    const boton         = document.getElementById("btn-comprar-final");
    const listaEl       = document.getElementById("lista-asientos");
    const precioEl      = document.getElementById("precio-total");

    if (asientos.length && inputAsientos) {

        let seleccionados = [];

        const precioBase = precioEl
            ? parseInt(precioEl.textContent.replace(/\./g, "").replace(/,/g, ""), 10) || 0
            : 0;

        asientos.forEach(asiento => {
            asiento.addEventListener("click", () => {
                const id     = asiento.dataset.id;
                const numero = asiento.dataset.numero;

                if (asiento.classList.contains("seleccionado")) {
                    asiento.classList.remove("seleccionado");
                    seleccionados = seleccionados.filter(a => a.id !== id);
                } else {
                    asiento.classList.add("seleccionado");
                    seleccionados.push({ id, numero });
                }

                actualizarCompra();
            });
        });

        function actualizarCompra() {
            inputAsientos.value = seleccionados.map(a => a.id).join(",");

            if (listaEl) {
                listaEl.textContent = seleccionados.length
                    ? seleccionados.map(a => a.numero).join(", ")
                    : "—";
            }

            if (precioEl && precioBase) {
                const total = precioBase * seleccionados.length;
                precioEl.textContent = total.toLocaleString("es-CO");
            }

            if (boton) boton.disabled = seleccionados.length === 0;
        }
    }

}); // fin DOMContentLoaded