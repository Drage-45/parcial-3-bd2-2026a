const buttonDropdown = document.querySelectorAll('.dropbtn');

buttonDropdown.forEach(button =>{
    button.addEventListener('click', function(event){
        
        const menu = this.nextElementSibling;
        
        menu.classList.toggle('mostrar');
        
    });
});

window.addEventListener('click', function(event){
    if(!event.target.matches('.dropbtn')){
        const dropdowns = document.querySelectorAll('.dropdown-content.mostrar');
        dropdowns.forEach(menu =>  {
            menu.classList.remove('mostrar');
        });
    }
});


const slider = document.querySelector('.slider');
const imagenes = document.querySelectorAll('.img-slider');
const btnPrev = document.querySelector('.prev-btn');
const btnNext = document.querySelector('.next-btn');
const dots = document.querySelectorAll('.dot');

let indexActual = 0;
const totalImagenes = imagenes.length;
const tiempoCambio = 5000; 
let intervaloSlider;

function actualizarSlider(index) {
    const anchoSlider = slider.clientWidth;
    slider.scrollLeft = index * anchoSlider;
    dots.forEach(dot => dot.classList.remove('active'));
    dots[index].classList.add('active');
    
    indexActual = index;
}

function siguienteImagen() {
    let nuevoIndex = indexActual + 1;
    if (nuevoIndex >= totalImagenes) {
        nuevoIndex = 0; 
    }
    actualizarSlider(nuevoIndex);
}

function anteriorImagen() {
    let nuevoIndex = indexActual - 1;
    if (nuevoIndex < 0) {
        nuevoIndex = totalImagenes - 1; 
    }
    actualizarSlider(nuevoIndex);
}

function iniciarAutoplay() {
    intervaloSlider = setInterval(siguienteImagen, tiempoCambio);
}

function reiniciarAutoplay() {
    clearInterval(intervaloSlider);
    iniciarAutoplay();
}

btnNext.addEventListener('click', () => {
    siguienteImagen();
    reiniciarAutoplay();
});

btnPrev.addEventListener('click', () => {
    anteriorImagen();
    reiniciarAutoplay();
});

dots.forEach(dot => {
    dot.addEventListener('click', (e) => {
        const indexSeleccionado = parseInt(e.target.getAttribute('data-index'));
        actualizarSlider(indexSeleccionado);
        reiniciarAutoplay();
    });
});

window.addEventListener('resize', () => {
    actualizarSlider(indexActual);
});

window.addEventListener('DOMContentLoaded', () => {
    iniciarAutoplay();
});