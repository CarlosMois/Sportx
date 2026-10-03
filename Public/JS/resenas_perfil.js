
<script>
document.addEventListener("DOMContentLoaded", function() {
    const perfilGuardado = JSON.parse(localStorage.getItem("usuario_perfil"));
    if (perfilGuardado && perfilGuardado.avatar) {
        const avatars = document.querySelectorAll("#resena-avatar");
        avatars.forEach(img => {
            img.src = perfilGuardado.avatar;
        });
    }
});
</script>
