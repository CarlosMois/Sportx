document.addEventListener("DOMContentLoaded", () => {

    const formulario = document.querySelector(".login-form");

    if (!formulario) {
        console.error("No se encontró el formulario de inicio de sesión.");
        return;
    }

    formulario.addEventListener("submit", iniciarSesion);

});

async function iniciarSesion(event) {

    event.preventDefault();

    const formulario = event.target;

    const correo = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    // Validaciones básicas
    if (correo === "" || password === "") {

        mostrarMensaje(
            "El correo y la contraseña son obligatorios.",
            "error"
        );

        return;
    }

    // Validar formato del correo
    if (!validarCorreo(correo)) {

        mostrarMensaje(
            "Ingresa un correo electrónico válido.",
            "error"
        );

        return;
    }

    const datos = new FormData();

    datos.append("correo", correo);
    datos.append("password", password);

    try {

        mostrarMensaje(
            "Iniciando sesión...",
            "info"
        );

        const respuesta = await fetch("../auth/login.php", {
            method: "POST",
            body: datos,
            credentials: "same-origin"
        });

        if (!respuesta.ok) {
            throw new Error("Error HTTP: " + respuesta.status);
        }

        const resultado = await respuesta.json();

        if (resultado.success) {

            mostrarMensaje(
                resultado.message,
                "success"
            );

            formulario.reset();

            setTimeout(() => {

                window.location.href = "index1.php";

            }, 3000);

        } else {

            mostrarMensaje(
                resultado.message,
                "error"
            );

        }

    } catch (error) {

        console.error("Error:", error);

        mostrarMensaje(
            "No fue posible conectar con el servidor.",
            "error"
        );

    }

}


function validarCorreo(correo) {

    const expresion = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    return expresion.test(correo);

}


function mostrarMensaje(mensaje, tipo) {

    const contenedor = document.getElementById("mensaje-login");

    if (!contenedor) {
        return;
    }

    contenedor.textContent = mensaje;

    contenedor.className = "mensaje-login";

    if (tipo === "success") {
        contenedor.classList.add("mensaje-exito");
    }

    if (tipo === "error") {
        contenedor.classList.add("mensaje-error");
    }

    if (tipo === "info") {
        contenedor.classList.add("mensaje-info");
    }

}