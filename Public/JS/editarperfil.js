document.addEventListener("DOMContentLoaded", () => {
    cargarDatosPerfil();

    const btnEditProfile = document.querySelector(".btn-custom");
    if (btnEditProfile) {
        btnEditProfile.addEventListener("click", abrirModalEditarPerfil);
    }
});

function cargarDatosPerfil() {
    const perfilGuardado = JSON.parse(localStorage.getItem("usuario_perfil"));

    if (perfilGuardado) {
        if (perfilGuardado.nombre) {
            document.getElementById("Usuario2").textContent = perfilGuardado.nombre;
        }
        if (perfilGuardado.email) {
            document.querySelector(".info-group:nth-child(2) p").textContent = perfilGuardado.email;
        }
        if (perfilGuardado.telefono) {
            document.querySelector(".info-group:nth-child(3) p").textContent = perfilGuardado.telefono;
        }
        if (perfilGuardado.deporteFavorito) {
            document.querySelector(".info-group:nth-child(4) p").textContent = perfilGuardado.deporteFavorito;
        }
        if (perfilGuardado.avatar) {
            document.querySelector(".avatar-large").src = perfilGuardado.avatar;
        }
    }
}

function abrirModalEditarPerfil() {
    const nombreActual = document.getElementById("Usuario2").textContent.trim();
    const emailActual = document.querySelector(".info-group:nth-child(2) p").textContent.trim();
    const telefonoActual = document.querySelector(".info-group:nth-child(3) p").textContent.trim();
    const deporteActual = document.querySelector(".info-group:nth-child(4) p").textContent.trim();

    let modalElement = document.getElementById("modalEditarPerfil");
    if (!modalElement) {
        const modalHTML = `
            <div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalEditarPerfilLabel"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEditarPerfil">
                                <div class="mb-3">
                                    <label for="editNombre" class="form-label fw-bold">Full Name</label>
                                    <input type="text" class="form-control" id="editNombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label fw-bold">Email</label>
                                    <input type="email" class="form-control" id="editEmail" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editTelefono" class="form-label fw-bold">Phone</label>
                                    <input type="text" class="form-control" id="editTelefono" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editDeporte" class="form-label fw-bold">Favorite Sport</label>
                                    <input type="text" class="form-control" id="editDeporte" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editAvatar" class="form-label fw-bold">Change Profile Picture</label>
                                    <input type="file" class="form-control" id="editAvatar" accept="image/*">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="btnGuardarPerfil" style="background-color: #007AA2; border: none;">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML("beforeend", modalHTML);
        modalElement = document.getElementById("modalEditarPerfil");

        document.getElementById("btnGuardarPerfil").addEventListener("click", guardarCambiosPerfil);
    }

    document.getElementById("editNombre").value = nombreActual;
    document.getElementById("editEmail").value = emailActual;
    document.getElementById("editTelefono").value = telefonoActual;
    document.getElementById("editDeporte").value = deporteActual;

    const modalInstance = new bootstrap.Modal(modalElement);
    modalInstance.show();
}

function guardarCambiosPerfil() {
    const nuevoNombre = document.getElementById("editNombre").value.trim();
    const nuevoEmail = document.getElementById("editEmail").value.trim();
    const nuevoTelefono = document.getElementById("editTelefono").value.trim();
    const nuevoDeporte = document.getElementById("editDeporte").value.trim();
    const inputAvatar = document.getElementById("editAvatar");

    if (!nuevoNombre || !nuevoEmail || !nuevoTelefono || !nuevoDeporte) {
        alert("Por favor completa todos los campos requeridos.");
        return;
    }

    document.getElementById("Usuario2").textContent = nuevoNombre;
    document.querySelector(".info-group:nth-child(2) p").textContent = nuevoEmail;
    document.querySelector(".info-group:nth-child(3) p").textContent = nuevoTelefono;
    document.querySelector(".info-group:nth-child(4) p").textContent = nuevoDeporte;

    const datosPerfil = {
        nombre: nuevoNombre,
        email: nuevoEmail,
        telefono: nuevoTelefono,
        deporteFavorito: nuevoDeporte
    };

    if (inputAvatar.files && inputAvatar.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const imagenBase64 = e.target.result;
            document.querySelector(".avatar-large").src = imagenBase64;
            datosPerfil.avatar = imagenBase64;
            
            localStorage.setItem("usuario_perfil", JSON.stringify(datosPerfil));
        };
        reader.readAsDataURL(inputAvatar.files[0]);
    } else {
        const perfilExistente = JSON.parse(localStorage.getItem("usuario_perfil"));
        if (perfilExistente && perfilExistente.avatar) {
            datosPerfil.avatar = perfilExistente.avatar;
        }
        localStorage.setItem("usuario_perfil", JSON.stringify(datosPerfil));
    }
    /*
    fetch('../Controlador/actualizar_perfil.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datosPerfil)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Perfil actualizado en base de datos:', data);
    })
    .catch(error => console.error('Error al actualizar en BD:', error));
    */
    const modalElement = document.getElementById("modalEditarPerfil");
    const modalInstance = bootstrap.Modal.getInstance(modalElement);
    if (modalInstance) {
        modalInstance.hide();
    }
}