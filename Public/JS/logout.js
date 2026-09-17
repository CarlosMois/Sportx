document.addEventListener("DOMContentLoaded", function () {

    const btnCerrarSesion = document.getElementById("btnCerrarSesion");

    if (btnCerrarSesion) {

        btnCerrarSesion.addEventListener("click", function () {
            
            fetch("../auth/logout.php", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {

                    window.location.href = "sesion.html";

                } else {

                    alert(data.message);

                }

            })
            .catch(error => {

                console.error("Error:", error);
                alert("Ocurrió un error al cerrar sesión.");

            });

        });

    }

});