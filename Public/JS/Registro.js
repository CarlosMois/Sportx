
const formulario = document.querySelector(".login-form");
const mensaje = document.querySelector("#mensaje");

formulario.addEventListener("submit", registrarUsuario);


async function registrarUsuario(event) {

    event.preventDefault();

    ocultarMensaje();

    const nombre = document.querySelector("#fullname").value.trim();
    const correo = document.querySelector("#email").value.trim();
    const password = document.querySelector("#password").value;

    if (nombre === "" || correo === "" || password === "") {
        mostrarMensaje("Todos los campos son obligatorios.", "error");
        return;
    }

    const datos = new FormData();

    datos.append("nombre", nombre);
    datos.append("correo", correo);
    datos.append("password", password);

    try {

        const respuesta = await fetch("../Usuarios/create.php", {
            method: "POST",
            body: datos
        });

        if (!respuesta.ok) {
            throw new Error("Error en la comunicación con el servidor.");
        }

        const resultado = await respuesta.json();

        if (resultado.success) {

            mostrarMensaje(resultado.message, "success");

            formulario.reset();

            setTimeout(() => {
                window.location.href = "../Inicio de sesión/index.html";
            }, 1500);

        } else {

            mostrarMensaje(resultado.message, "error");

        }

    } catch (error) {

        console.error(error);

        mostrarMensaje(
            "No se pudo completar el registro. Inténtalo nuevamente.",
            "error"
        );
    }
}


function mostrarMensaje(texto, tipo) {

    mensaje.textContent = texto;

    mensaje.classList.remove("success", "error");

    mensaje.classList.add(tipo);

    mensaje.style.display = "block";
}


function ocultarMensaje() {

    mensaje.textContent = "";

    mensaje.classList.remove("success", "error");

    mensaje.style.display = "none";
}
