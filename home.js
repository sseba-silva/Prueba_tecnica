document.addEventListener("DOMContentLoaded", function () {
    // Asignar eventos a los botones
    document.getElementById("guardarProducto").addEventListener("click", function (event) {
        event.preventDefault();
        validarFormulario();
    });

    document.getElementById("bodega").addEventListener("change", function () {
        let bodegaId = this.value;
        bodegaId ? cargarSucursales(bodegaId) : limpiarOpciones("sucursal");
    });

    cargarMonedas();
    cargarBodegas();
});


async function validarFormulario() {

    let errores = [];

    let codigo = document.getElementById("codigo").value.trim();
    let nombre = document.getElementById("nombre").value.trim();
    let bodega = document.getElementById("bodega").value;
    let sucursal = document.getElementById("sucursal").value;
    let moneda = document.getElementById("moneda").value;
    let precio = document.getElementById("precio").value.trim();
    let descripcion = document.querySelector("textarea").value.trim();

    let validar = await validarCodigo(codigo)

    let materiales = [...document.querySelectorAll("input[type='checkbox']:checked")].map(cb => cb.value);

    if (!codigo ) {
        errores.push("El código del producto no puede estar en blanco.");
    }

    if (!codigo.match(/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z0-9]{5,15}$/)) {
        errores.push("El nombre del producto debe tener entre 2 y 50 caracteres.");
    }

    if (validar === true){
        errores.push("El código del producto ya está registrado.");
    }

    if (!nombre) {
        errores.push("El nombre no puede estar en blanco");
    }

    if (nombre.length < 2 || nombre.length > 50) {
        errores.push("El nombre debe tener entre 2 y 50 caracteres.");
    }

    if (!precio) {
        errores.push("El precio no puede estar en blanco.");
    }

    if (!precio.match(/^(\d+(\.\d{1,2})?)$/)) {
        errores.push("El precio debe ser un número positivo con hasta dos decimales.");
    }

    if (materiales.length < 2) {
        errores.push("Debe seleccionar al menos dos materiales para el producto.");
    }

    if (!bodega ) {
        errores.push("Debe seleccionar una bodega");
    }

    if (!sucursal ) {
        errores.push("Debe seleccionar una sucursal para la bodega seleccionada");
    }

    if (!moneda ) {
        errores.push("Debe seleccionar una moneda para el producto.");
    }

    if (!descripcion) {
        errores.push("La descripción del producto no puede estar en blanco.");
    }

    if (descripcion.length < 10 || descripcion.length > 1000) {
        errores.push("La descripción debe tener entre 10 y 1000 caracteres.");
    }

    if (errores.length > 0) {
        alert(errores.join("\n"));
    } else {
        enviarFormulario({ codigo, nombre, bodega, sucursal, moneda, precio, descripcion, materiales });
    }
}

function enviarFormulario(datos) {
    let formData = new FormData();
    Object.keys(datos).forEach(key => {
        if (Array.isArray(datos[key])) {
            datos[key].forEach(value => formData.append(`${key}[]`, value)); // Enviar materiales como array
        } else {
            formData.append(key, datos[key]);
        }
    });

    fetch("api.php?accion=insertar_producto", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.status === "success") {
            document.forms["productoForm"].reset();
        }
    })
    .catch(error => console.error("Error:", error));
}

function cargarMonedas() {
    fetch("api.php?accion=obtener_monedas")
        .then(response => response.json())
        .then(data => {
            let monedaSelect = document.getElementById("moneda");
            monedaSelect.innerHTML = '<option value="">Seleccione una moneda</option>';
            data.forEach(({nombre_moneda,  id_moneda:moneda}) => {
                monedaSelect.appendChild(new Option(nombre_moneda, moneda));
            });
        })
        .catch(error => console.error("Error al cargar monedas:", error));
}

function cargarBodegas() {
    fetch("api.php?accion=obtener_bodegas")
        .then(response => response.json())
        .then(data => {
            let bodegaSelect = document.getElementById("bodega");
            bodegaSelect.innerHTML = '<option value="">Seleccione una bodega</option>';
            data.forEach(({nombre_bodega,  id_bodega:bodega}) => {
                bodegaSelect.appendChild(new Option(nombre_bodega, bodega));
            });
        })
        .catch(error => console.error("Error al cargar bodegas:", error));
}

function cargarSucursales(bodegaId) {
    fetch(`api.php?accion=obtener_sucursales&id_bodega=${bodegaId}`)
        .then(response => response.json())
        .then(data => {
            let sucursalSelect = document.getElementById("sucursal");
            sucursalSelect.innerHTML = '<option value="">Seleccione una sucursal</option>';
            data.forEach(({nombre_sucursal,  id_sucursal:sucursal}) => {
                sucursalSelect.appendChild(new Option(nombre_sucursal, sucursal));
            });
        })
        .catch(error => console.error("Error al cargar sucursales:", error));
}

function limpiarOpciones(selectId) {
    document.getElementById(selectId).innerHTML = '<option value="">Seleccione una opción</option>';
}

async function validarCodigo(codigo) {
    try {
        let response = await fetch(`api.php?accion=obtener_codigo&codigo=${codigo}`);
        let data = await response.json();
        
        if (Object.keys(data).length > 0) {
            console.log("Código existente");
            return true;
        } else {
            console.log("Código disponible");
            return false;
        }
    } catch (error) {
        console.error("Error al validar el código:", error);
        return false;
    }
}

