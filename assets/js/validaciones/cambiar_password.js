function togglePassword(inputId, hideEyeId, showEyeId) {
    const input = document.getElementById(inputId);
    const hideEye = document.getElementById(hideEyeId);
    const showEye = document.getElementById(showEyeId);

    if (input.type === "password") {
        input.type = "text";        
        hideEye.style.display = "none";
        showEye.style.display = "block";
    } else {
        input.type = "password";     
        hideEye.style.display = "block";
        showEye.style.display = "none";
    }
}

function validateForm() {
    const password = document.getElementById("password");
    const confirm = document.getElementById("password_confirm");
    const passwordError = document.getElementById("password_error");
    const confirmError = document.getElementById("password_confirm_error");

    let valid = true;

    passwordError.textContent = "";
    confirmError.textContent = "";
    password.classList.remove("input-alert");
    confirm.classList.remove("input-alert");

    if (!password.value) {
        passwordError.textContent = "Debe ingresar una contraseña.";
        password.classList.add("input-alert");
        valid = false;
    } else if (password.value.length < 8) {
        passwordError.textContent = "La contraseña debe tener al menos 8 caracteres.";
        password.classList.add("input-alert");
        valid = false;
    }

    if (!confirm.value) {
        confirmError.textContent = "Debe confirmar la contraseña.";
        confirm.classList.add("input-alert");
        valid = false;
    } else if (confirm.value !== password.value) {
        confirmError.textContent = "Las contraseñas no coinciden.";
        confirm.classList.add("input-alert");
        valid = false;
    }

    if (valid) {
        document.getElementById("cambiar_password_form").submit();
    }
}
