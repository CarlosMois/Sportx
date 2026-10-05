document.addEventListener("DOMContentLoaded", () => {

    const formulario = document.querySelector(".login-form");

    if (!formulario) {
        console.error("Registration form not found.");
        return;
    }

    formulario.addEventListener("submit", registrarUsuario);

});


async function registrarUsuario(event) {

    event.preventDefault();

    const formulario = event.target;

    const nombre = document.getElementById("fullname").value.trim();
    const correo = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    // Validación básica en JavaScript
    if (nombre === "" || correo === "" || password === "") {
        mostrarMensaje("All fields are mandatory.", "error");
        return;
    }

    if (password.length < 6) {
        mostrarMensaje(
            "Password must be at least 6 characters long.",
            "error"
        );
        return;
    }

    // Crear FormData
    const datos = new FormData();

    datos.append("nombre", nombre);
    datos.append("correo", correo);
    datos.append("password", password);

    try {

        mostrarMensaje("Registering user...", "info");

        const respuesta = await fetch("../registro/create.php", {
            method: "POST",
            body: datos
        });

        if (!respuesta.ok) {
            throw new Error("Error HTTP: " + respuesta.status);
        }

        const resultado = await respuesta.json();

        if (resultado.success) {

            mostrarMensaje(resultado.message, "success");

            // Limpiar formulario
            formulario.reset();

            // Redireccionar después de registrarse
            setTimeout(() => {
                window.location.href = "sesion.html";
            }, 1000);

        } else {

            mostrarMensaje(resultado.message, "error");

        }

    } catch (error) {

        console.error("Error:", error);

        mostrarMensaje(
            "Unable to connect to the server.",
            "error"
        );

    }

}


function mostrarMensaje(mensaje, tipo) {

    let contenedor = document.getElementById("mensaje-registro");

    if (!contenedor) {
        return;
    }

    contenedor.textContent = mensaje;

    contenedor.className = "";

    contenedor.classList.add("mensaje-registro");

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