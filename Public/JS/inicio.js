document.addEventListener("DOMContentLoaded", function() {

    let puestoNombre = document.getElementById("Cartel2");
    let avatarInicio = document.getElementById("avatar-inicio");
    let deportesInicio = document.getElementById("user-sports-inicio");

    // 1. Cargar datos del perfil desde localStorage
    const perfilGuardado = JSON.parse(localStorage.getItem("usuario_perfil"));
    if (perfilGuardado) {
        // Cargar la foto de perfil
        if (perfilGuardado.avatar && avatarInicio) {
            avatarInicio.src = perfilGuardado.avatar;
        }
        // Cargar los deportes favoritos en lugar de "Welcome back"
        if (perfilGuardado.deporteFavorito && deportesInicio) {
            deportesInicio.textContent = perfilGuardado.deporteFavorito;
        }
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
                    console.log(data.message);
                }

            })
            .catch(error => {
                console.error("Error:", error);
            });

});