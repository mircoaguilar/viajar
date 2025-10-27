function validate() {
    const nombreInput = document.getElementById('nombre_usuario');
    const passInput = document.getElementById('password');
    const nombreError = document.getElementById('id_usuario_parrafo');
    const passError = document.getElementById('id_password_parrafo');

    let valid = true;

    // Validar nombre de usuario
    if (nombreInput.value.trim() === '') {
        nombreInput.classList.add('input-alert');
        nombreError.textContent = 'Usuario requerido';
        valid = false;
    } else {
        nombreInput.classList.remove('input-alert');
        nombreError.textContent = '';
    }

    // Validar contraseña
    if (passInput.value.trim() === '') {
        passInput.classList.add('input-alert');
        passError.textContent = 'Contraseña requerida';
        valid = false;
    } else {
        passInput.classList.remove('input-alert');
        passError.textContent = '';
    }

    if (valid) {
        document.getElementById('id_form').submit();
    }
}

/* Funcion para mostrar/ocultar contraseña */
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const hideEye = document.getElementById("hide_eye");
    const showEye = document.getElementById("show_eye");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        hideEye.style.display = "none";
        showEye.style.display = "block";
    } else {
        passwordInput.type = "password";
        hideEye.style.display = "block";
        showEye.style.display = "none";
    }
}

/* ---- SweetAlert2 Toasts para backend ---- */
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    const status = urlParams.get('status');

    if (message) {
        let icon;
        switch (status) {
            case 'success':
                icon = 'success';
                break;
            case 'danger':
            case 'error':
                icon = 'error';
                break;
            default:
                icon = 'info';
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: icon === 'success' ? 1500 : 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: icon,
            title: message
        });
    }
});

