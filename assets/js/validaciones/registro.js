const ERROR_ID_MAP = {
  username: 'error-usuario',
};

function getErrorElement(input) {
  const defaultId = `error-${input.name}`;
  const mappedId = ERROR_ID_MAP[input.name] || defaultId;
  return document.getElementById(mappedId);
}

function limpiarError(input) {
  input.classList.remove('input-error', 'input-alert');
  const err = getErrorElement(input);
  if (err) {
    err.textContent = '';
    err.style.display = 'none';
  }
}

function mostrarError(input, mensaje) {
  input.classList.add('input-error');
  const err = getErrorElement(input);
  if (err) {
    err.textContent = mensaje;
    err.style.display = 'block';
  } else {
    console.warn(`No encontré contenedor de error para ${input.name}`);
  }
}

function validarEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

function validate_username(event) {
  const usernameInput = event.target;
  limpiarError(usernameInput);
  if (usernameInput.value.trim() === '') return;

  $.ajax({
    url: 'controllers/usuarios/usuarios.ajax.controlador.php',
    type: 'post',
    data: { usuarios_nombre_usuario: usernameInput.value, action: 'ajax' },
    success: function (response) {
      try {
        const data = JSON.parse(response);
        if (data.data === 'error') {
          mostrarError(usernameInput, 'El usuario ya existe.');
          usernameInput.classList.add('input-alert');
          usernameInput.value = '';
          usernameInput.focus();
        }
      } catch (e) {
        console.error('Error parseando JSON usuario:', e, response);
        mostrarError(usernameInput, 'Error en la validación de usuario.');
        usernameInput.classList.add('input-alert');
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error('Error AJAX usuario:', textStatus, errorThrown);
      mostrarError(usernameInput, 'Error de comunicación con el servidor al validar usuario.');
      usernameInput.classList.add('input-alert');
    },
  });
}

function validate_email(event) {
  const emailInput = event.target;
  limpiarError(emailInput);
  if (emailInput.value.trim() === '') return;

  $.ajax({
    url: 'controllers/usuarios/email.ajax.controlador.php',
    type: 'post',
    data: { usuarios_email: emailInput.value, action: 'ajax' },
    success: function (response) {
      try {
        const data = JSON.parse(response);
        if (data.data === 'error') {
          mostrarError(emailInput, 'El email ya está registrado.');
          emailInput.classList.add('input-alert');
          emailInput.value = '';
          emailInput.focus();
        }
      } catch (e) {
        console.error('Error parseando JSON email:', e, response);
        mostrarError(emailInput, 'Error al validar email.');
        emailInput.classList.add('input-alert');
      }
    },
    error: function () {
      mostrarError(emailInput, 'Error de comunicación con el servidor.');
      emailInput.classList.add('input-alert');
    },
  });
}

function togglePassword(id, hideId, showId) {
  const input = document.getElementById(id);
  const hideEye = document.getElementById(hideId);
  const showEye = document.getElementById(showId);
  if (!input) return;

  if (input.type === 'password') {
    input.type = 'text';
    if (showEye) showEye.style.display = 'inline';
    if (hideEye) hideEye.style.display = 'none';
  } else {
    input.type = 'password';
    if (showEye) showEye.style.display = 'none';
    if (hideEye) hideEye.style.display = 'inline';
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form');
  if (!form) return;

  const inputs = form.querySelectorAll('input');

  const usernameInput = document.getElementById('id_nombre_usuario');
  if (usernameInput) usernameInput.addEventListener('blur', validate_username);

  const emailInput = document.getElementById('id_email');
  if (emailInput) emailInput.addEventListener('blur', validate_email);

  inputs.forEach((input) => {
    input.addEventListener('input', () => limpiarError(input));
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    let valido = true;

    inputs.forEach((input) => limpiarError(input));

    inputs.forEach((input) => {
      const nombre = input.name;
      const valor = input.value.trim();

      if (nombre === 'nombre' && valor === '') {
        mostrarError(input, 'El nombre es obligatorio');
        valido = false;
      } else if (nombre === 'apellido' && valor === '') {
        mostrarError(input, 'El apellido es obligatorio');
        valido = false;
      } else if (nombre === 'dni' && (valor === '' || isNaN(valor))) {
        mostrarError(input, 'Ingrese un DNI válido');
        valido = false;
      } else if (nombre === 'fecha_nac' && valor === '') {
        mostrarError(input, 'La fecha de nacimiento es obligatoria');
        valido = false;
      } else if (nombre === 'domicilio' && valor === '') {
        mostrarError(input, 'El domicilio es obligatorio');
        valido = false;
      } else if (nombre === 'telefono' && (valor === '' || isNaN(valor))) {
        mostrarError(input, 'Ingrese un teléfono válido');
        valido = false;
      } else if (nombre === 'username' && valor === '') {
        mostrarError(input, 'El nombre de usuario es obligatorio');
        valido = false;
      } else if (nombre === 'email') {
        if (valor === '') {
          mostrarError(input, 'El correo electrónico es obligatorio');
          valido = false;
        } else if (!validarEmail(valor)) {
          mostrarError(input, 'Ingrese un correo electrónico válido');
          valido = false;
        }
      } else if (nombre === 'password') {
        if (valor === '') {
          mostrarError(input, 'La contraseña es obligatoria');
          valido = false;
        } else if (valor.length < 6) {
          mostrarError(input, 'La contraseña debe tener al menos 6 caracteres');
          valido = false;
        }
      } else if (nombre === 'password_confirm') {
        const pass = form.querySelector("input[name='password']").value;
        if (valor === '') {
          mostrarError(input, 'Debe confirmar la contraseña');
          valido = false;
        } else if (valor !== pass) {
          mostrarError(input, 'Las contraseñas no coinciden');
          valido = false;
        }
      }
    });

    if (valido) form.submit();
  });
});
