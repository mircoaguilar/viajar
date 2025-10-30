function validate_username(event) {
    const usernameInput = event.target;
    const errorUserElement = document.getElementById('error-usuario');

    errorUserElement.textContent = "";
    usernameInput.classList.remove("input-alert");

    if (usernameInput.value.trim() === '') return;

    $.ajax({
        url: "controllers/usuarios/usuarios.ajax.controlador.php",
        type: "post",
        data: {
            'usuarios_nombre_usuario': usernameInput.value,
            'action': 'ajax'
        },
        success: function(response) {
            try {
                let data = JSON.parse(response);
                if (data.data === 'error') {
                    errorUserElement.textContent = 'El usuario ya existe.';
                    usernameInput.classList.add('input-alert');
                    usernameInput.value = '';
                }
            } catch (e) {
                console.error("Error parseando JSON usuario:", e);
                errorUserElement.textContent = 'Error en la validación de usuario.';
                usernameInput.classList.add('input-alert');
            }
        },
        error: function() {
            errorUserElement.textContent = 'Error de comunicación al validar usuario.';
            usernameInput.classList.add('input-alert');
        }
    });
}

function validate_email(event) {
    const emailInput = event.target;
    const errorEmailElement = document.getElementById('error-email');

    errorEmailElement.textContent = "";
    emailInput.classList.remove("input-alert");

    if (emailInput.value.trim() === '') return;

    if (!validarEmail(emailInput.value)) {
        errorEmailElement.textContent = "Ingrese un correo válido.";
        emailInput.classList.add("input-alert");
        return;
    }

    $.ajax({
        url: "controllers/usuarios/email.ajax.controlador.php",
        type: "post",
        data: {
            'usuarios_email': emailInput.value,
            'action': 'ajax'
        },
        success: function(response) {
            try {
                let data = JSON.parse(response);
                if (data.data === 'error') {
                    errorEmailElement.textContent = 'El email ya existe.';
                    emailInput.classList.add('input-alert');
                    emailInput.value = '';
                }
            } catch (e) {
                console.error("Error parseando JSON email:", e);
                errorEmailElement.textContent = 'Error en la validación de email.';
                emailInput.classList.add('input-alert');
            }
        },
        error: function() {
            errorEmailElement.textContent = 'Error de comunicación al validar email.';
            emailInput.classList.add('input-alert');
        }
    });
}

function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}


function validarFormulario() {
    const personaSelect = document.getElementById("rela_personas");
    const usuarioInput = document.getElementById("id_nombre_usuario");
    const emailInput = document.getElementById("id_email");
    const perfilSelect = document.getElementById("rela_perfiles");

    const errorPersona = document.getElementById("error-persona");
    const errorUsuario = document.getElementById("error-usuario");
    const errorEmail = document.getElementById("error-email");
    const errorPerfil = document.getElementById("error-perfil");

    let formularioValido = true;

    errorPersona.textContent = "";
    errorUsuario.textContent = "";
    errorEmail.textContent = "";
    errorPerfil.textContent = "";

    personaSelect.classList.remove("input-alert");
    usuarioInput.classList.remove("input-alert");
    emailInput.classList.remove("input-alert");
    perfilSelect.classList.remove("input-alert");

    if (personaSelect.value === "") {
        errorPersona.textContent = "Debe seleccionar una persona.";
        personaSelect.classList.add("input-alert");
        formularioValido = false;
    }

    if (usuarioInput.value.trim().length < 3) {
        errorUsuario.textContent = "El nombre de usuario debe tener al menos 3 caracteres.";
        usuarioInput.classList.add("input-alert");
        formularioValido = false;
    }

    if (!validarEmail(emailInput.value.trim())) {
        errorEmail.textContent = "Ingrese un correo válido.";
        emailInput.classList.add("input-alert");
        formularioValido = false;
    }

    if (perfilSelect.value === "") {
        errorPerfil.textContent = "Debe seleccionar un perfil.";
        perfilSelect.classList.add("input-alert");
        formularioValido = false;
    }

    if (formularioValido) {
        document.getElementById("form-crear-usuario").submit();
    }
}


function validarRegistro() {
    const usuario = document.getElementById("id_nombre_usuario_registro");
    const email = document.getElementById("id_email_registro");
    const password = document.getElementById("id_password_registro");

    const errorUsuario = document.getElementById("error-usuario-registro");
    const errorEmail = document.getElementById("error-email-registro");
    const errorPassword = document.getElementById("error-password-registro");

    errorUsuario.textContent = "";
    errorEmail.textContent = "";
    errorPassword.textContent = "";
    usuario.classList.remove("input-alert");
    email.classList.remove("input-alert");
    password.classList.remove("input-alert");

    let valid = true;

    if (usuario.value.trim().length < 3) {
        errorUsuario.textContent = "Debe ingresar un usuario válido.";
        usuario.classList.add("input-alert");
        valid = false;
    }

    const emailValue = email.value.trim();
    if (!emailValue || !validarEmail(emailValue)) {
        errorEmail.textContent = "Ingrese un correo válido.";
        email.classList.add("input-alert");
        valid = false;
    }

    if (!password.value.trim() || password.value.length < 6) {
        errorPassword.textContent = "La contraseña debe tener al menos 6 caracteres.";
        password.classList.add("input-alert");
        valid = false;
    }

    if (valid) {
        document.getElementById("form-registro").submit();
    }
}


function togglePassword() {
    const passwordInput = document.getElementById("id_password_registro");
    const hideEye = document.getElementById("hide_eye");
    const showEye = document.getElementById("show_eye");

    if (!passwordInput || !hideEye || !showEye) return;

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


$(document).ready(function() {
    $('#rela_personas').select2();
    $('#rela_perfiles').select2();
});

$(document).on('click', '.editar-persona-btn', function(e) {
    e.preventDefault();
    const personaId = $(this).data('persona-id');

    $.ajax({
        url: 'controllers/personas/personas.controlador.php',
        method: 'GET',
        data: { action: 'obtener', id: personaId },
        success: function(response) {
            try {
                const data = (typeof response === 'string') ? JSON.parse(response) : response;

                if (data.success) {
                    const persona = data.persona;
                    $('#datos-persona-lectura').html(`
                        <p><strong>Nombre:</strong> ${persona.personas_nombre}</p>
                        <p><strong>Apellido:</strong> ${persona.personas_apellido}</p>
                        <p><strong>DNI:</strong> ${persona.personas_dni}</p>
                    `);
                    $('#id_personas').val(persona.id_personas);
                    $('#personas_nombre').val(persona.personas_nombre);
                    $('#personas_apellido').val(persona.personas_apellido);
                    $('#personas_dni').val(persona.personas_dni);

                    $('#datos-persona-edicion').hide();
                    $('#datos-persona-lectura').show();
                    $('#boton-editar-persona').show();
                    $('#editar-persona-modal').fadeIn();
                    $('#overlay').fadeIn();
                } else {
                    alert("No se encontraron datos de la persona.");
                }
            } catch (err) {
                console.error("Error parseando JSON persona:", err);
                alert("Error al cargar los datos de la persona.");
            }
        },
        error: function() {
            alert("Error al comunicarse con el servidor.");
        }
    });
});

$('#boton-editar-persona').on('click', function() {
    $('#datos-persona-lectura').hide();
    $('#datos-persona-edicion').show();
    $(this).hide();
});

$('#cancelar-edicion-persona').on('click', function() {
    $('#datos-persona-edicion').hide();
    $('#datos-persona-lectura').show();
    $('#boton-editar-persona').show();
});

$('#cerrar-modal-persona, #overlay').on('click', function() {
    $('#editar-persona-modal').fadeOut();
    $('#overlay').fadeOut();
});

$('#form-editar-persona').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: 'controllers/personas/personas.controlador.php',
        method: 'POST',
        data: formData + '&action=actualizar',
        success: function(response) {
            try {
                const data = (typeof response === 'string') ? JSON.parse(response) : response;
                if (data.success) {
                    alert("Datos actualizados correctamente.");
                    $('#editar-persona-modal').fadeOut();
                    $('#overlay').fadeOut();
                    location.reload();
                } else {
                    alert("Error al actualizar: " + (data.message || ""));
                }
            } catch (err) {
                console.error("Error parseando respuesta actualización:", err);
                alert("Error inesperado al actualizar.");
            }
        },
        error: function() {
            alert("Error en la comunicación con el servidor.");
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();

            const id = btn.dataset.id;
            const entity = btn.dataset.entity;
            const action = btn.dataset.action || 'eliminar';
            const idName = btn.dataset.idName || `id_${entity}`;

            Swal.fire({
                title: `¿Seguro que quieres eliminar este registro de ${entity}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = `controllers/${entity}/${entity}.controlador.php`;

                    const inputAction = document.createElement('input');
                    inputAction.type = 'hidden';
                    inputAction.name = 'action';
                    inputAction.value = action;
                    form.appendChild(inputAction);

                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = idName;
                    inputId.value = id;
                    form.appendChild(inputId);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
});


