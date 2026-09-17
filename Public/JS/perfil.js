document.addEventListener("DOMContentLoaded", function() {

    let puestoNombre = document.getElementById("Usuario2")
    
    fetch("../auth/session.php", {
                method: "POST"
            })
            .then(response => response.json())
            .then(data => {

                if (data.success) {

                   puestoNombre.innerText = data.usuario["nombre"];

                } else {

                    alert(data.message);

                }

            })
            .catch(error => {

                console.error("Error:", error);
                alert("Ocurrió un error al cerrar sesión.");

            });

});