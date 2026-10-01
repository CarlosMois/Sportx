document.addEventListener("DOMContentLoaded", function() {

    let puestoNombre = document.getElementById("Cartel2");
    let avatarInicio = document.getElementById("avatar-inicio");

    // 1. Cargar la foto de perfil desde localStorage
    const perfilGuardado = JSON.parse(localStorage.getItem("usuario_perfil"));
    if (perfilGuardado && perfilGuardado.avatar && avatarInicio) {
        avatarInicio.src = perfilGuardado.avatar;
    }

    // 2. Cargar el nombre del usuario desde la sesión de PHP
    fetch("../auth/session.php", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {
                   if (puestoNombre) {
                       puestoNombre.innerText = data.usuario["nombre"];
                   }

                } else {
                    // No alertamos el mensaje aquí para evitar molestar al usuario en el index
                    console.log(data.message);
                }

            })
            .catch(error => {
                console.error("Error:", error);
            });

});