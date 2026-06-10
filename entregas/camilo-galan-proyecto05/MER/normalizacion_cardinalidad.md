# Cardinalidad de la Base de Datos

La base de datos de Cinemas Star está diseñada mediante un modelo relacional donde las entidades se conectan mediante relaciones de uno a muchos (1:N) y uno a uno (1:1).

## Relaciones principales

- **Genero → Pelicula (1:N)**  
  Un género puede tener varias películas, pero una película pertenece a un solo género.

- **Pelicula → Funcion (1:N)**  
  Una película puede tener varias funciones con diferentes horarios.

- **Sala → Funcion (1:N)**  
  Una sala puede tener muchas funciones programadas.

- **Sala → Butaca (1:N)**  
  Una sala contiene varias butacas.

- **Funcion → Funcion_Butaca (1:N)**  
  Una función genera varios registros de asientos disponibles u ocupados.

- **Butaca → Funcion_Butaca (1:N)**  
  Una butaca puede participar en diferentes funciones.

- **Funcion_Butaca → Boleto (1:1)**  
  Un asiento específico de una función puede generar un solo boleto de compra.

- **Cliente → Venta (1:N)**  
  Un cliente puede realizar varias compras.

- **Venta → Boleto (1:N)**  
  Una venta puede incluir varios boletos.

- **Venta → Venta_Detalle (1:N)**  
  Una venta puede contener varios detalles de compra.

- **Producto → Venta_Detalle (1:N)**  
  Un producto puede aparecer en diferentes ventas.

Estas relaciones permiten gestionar películas, funciones, asientos, boletos y productos de confitería dentro del sistema.

# Normalización de la Base de Datos

La base de datos de Cinemas Star fue diseñada aplicando las tres primeras formas normales para evitar duplicidad de información y mejorar la integridad de los datos.

## Primera Forma Normal (1FN)

Una tabla cumple la primera forma normal cuando todos sus campos contienen valores atómicos y no existen datos repetidos dentro de una misma columna.

En el sistema:

- La tabla **pelicula** almacena una sola película por registro.
- La tabla **producto** almacena un producto individual.
- La tabla **cliente** contiene datos separados como nombre y correo.

Esto evita guardar múltiples valores en un mismo campo.

---

## Segunda Forma Normal (2FN)

Una tabla cumple la segunda forma normal cuando está en 1FN y todos sus atributos dependen completamente de la clave primaria.

En el sistema:

- **funcion_butaca** relaciona una función con una butaca usando su propia clave primaria.
- **venta_detalle** depende de una venta específica y almacena únicamente los datos correspondientes a ese detalle.

Esto evita información que no pertenece completamente a un registro.

---

## Tercera Forma Normal (3FN)

Una tabla cumple la tercera forma normal cuando está en 2FN y sus atributos no dependen de otros campos que no sean la clave primaria.

En el sistema:

- Los géneros se separan en la tabla **genero** y no se repiten en cada película.
- Los datos de la sala están separados de las funciones.
- Los productos tienen su propia tabla y se relacionan mediante **venta_detalle**.

Esto reduce la redundancia y facilita la actualización de información.

---

La aplicación de estas reglas permite una base de datos más organizada, eficiente y con menor riesgo de inconsistencias.