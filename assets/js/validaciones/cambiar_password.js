/* Funcion del ojito que muestra/oculta la contraseña */
function togglePassword(inputId, hideEyeId, showEyeId) {
    const input = document.getElementById(inputId);
    const hideEye = document.getElementById(hideEyeId);
    const showEye = document.getElementById(showEyeId);

    if (input.type === "password") {
        input.type = "text";          // mostrar contraseña
        hideEye.style.display = "none";
        showEye.style.display = "block";
    } else {
        input.type = "password";      // ocultar contraseña
        hideEye.style.display = "block";
        showEye.style.display = "none";
    }
}

/* validaciones del formulario */
function validateForm() {
    const password = document.getElementById("password");
    const confirm = document.getElementById("password_confirm");
    const passwordError = document.getElementById("password_error");
    const confirmError = document.getElementById("password_confirm_error");

    let valid = true;

    // limpiar mensajes y bordes rojos
    passwordError.textContent = "";
    confirmError.textContent = "";
    password.classList.remove("input-alert");
    confirm.classList.remove("input-alert");

    // chequear contraseña
    if (!password.value) {
        passwordError.textContent = "Debe ingresar una contraseña.";
        password.classList.add("input-alert");
        valid = false;
    } else if (password.value.length < 8) {
        passwordError.textContent = "La contraseña debe tener al menos 8 caracteres.";
        password.classList.add("input-alert");
        valid = false;
    }

    // chequear confirmación
    if (!confirm.value) {
        confirmError.textContent = "Debe confirmar la contraseña.";
        confirm.classList.add("input-alert");
        valid = false;
    } else if (confirm.value !== password.value) {
        confirmError.textContent = "Las contraseñas no coinciden.";
        confirm.classList.add("input-alert");
        valid = false;
    }

    // si todo está bien, enviar formulario
    if (valid) {
        document.getElementById("cambiar_password_form").submit();
    }
}
