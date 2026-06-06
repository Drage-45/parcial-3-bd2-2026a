console.log("SCRIPT NUEVO CARGADO");


document.addEventListener("DOMContentLoaded", () => {


    // =========================
    // DROPDOWN
    // =========================

    document.querySelectorAll(".dropbtn")
        .forEach(btn => {

            btn.addEventListener("click", () => {

                btn.nextElementSibling
                    .classList.toggle("mostrar");

            });

        });



    // =========================
    // SLIDER
    // =========================


    const slider =
        document.querySelector(".slider");


    const slides =
        document.querySelectorAll(".slide-item");


    let posicion = 0;


    document.querySelector(".next-btn")
        ?.addEventListener("click", () => {


            posicion++;

            if (posicion >= slides.length)
                posicion = 0;


            slider.style.transform =
                `translateX(-${posicion * 100}%)`;

        });



    document.querySelector(".prev-btn")
        ?.addEventListener("click", () => {


            posicion--;

            if (posicion < 0)
                posicion = slides.length - 1;


            slider.style.transform =
                `translateX(-${posicion * 100}%)`;

        });



// =========================
// ASIENTOS
// =========================

const asientos =
document.querySelectorAll(".asiento:not(.ocupado)");


const inputAsientos =
document.getElementById("asientos");


const boton =
document.getElementById("btn-comprar-final");


const lista =
document.getElementById("lista-asientos");


let seleccionados = [];



asientos.forEach(asiento => {


    asiento.addEventListener("click",()=>{


        const id =
        asiento.dataset.id;


        const numero =
        asiento.dataset.numero;



        if(asiento.classList.contains("seleccionado")){


            asiento.classList.remove("seleccionado");


            seleccionados =
            seleccionados.filter(
                a => a.id !== id
            );


        }else{


            asiento.classList.add("seleccionado");


            seleccionados.push({

                id:id,
                numero:numero

            });

        }



        actualizarCompra();



    });


});



function actualizarCompra(){


    let ids =
    seleccionados.map(
        a=>a.id
    );


    inputAsientos.value =
    ids.join(",");



    if(lista){

        lista.innerHTML =
        seleccionados.length > 0
        ?
        seleccionados.map(a=>a.numero).join(", ")
        :
        "-";

    }



    if(seleccionados.length > 0){

        boton.disabled=false;

    }else{

        boton.disabled=true;

    }



};


});

let carrito = [];

document.querySelectorAll(".btn-comprar")
.forEach(btn=>{

btn.onclick=()=>{

let id = btn.dataset.id;

carrito.push(id);

localStorage.setItem(
"carrito",
JSON.stringify(carrito)
);

alert("Producto agregado");

}

});

// =================================
// CARRITO PRODUCTOS
// =================================


function cargarCarrito(){


const lista =
document.getElementById("lista-carrito");


const total =
document.getElementById("total");



if(!lista){
    return;
}



let productos =
JSON.parse(localStorage.getItem("productos")) || [];



let suma=0;



lista.innerHTML="";



productos.forEach((p,index)=>{


let subtotal =
p.precio*p.cantidad;



suma += subtotal;



lista.innerHTML += `

<div class="bloque-card">


<h3>${p.nombre}</h3>


<p>
Cantidad: ${p.cantidad}
</p>


<p>
$${subtotal.toLocaleString()}
</p>


<button 
onclick="eliminarProducto(${index})">

Eliminar

</button>


</div>

`;


});



total.innerText =
suma.toLocaleString("es-CO");


}



function eliminarProducto(index){


let productos =
JSON.parse(localStorage.getItem("productos")) || [];


productos.splice(index,1);



localStorage.setItem(
"productos",
JSON.stringify(productos)
);


cargarCarrito();


}



document.addEventListener(
"DOMContentLoaded",
()=>{

cargarCarrito();

});