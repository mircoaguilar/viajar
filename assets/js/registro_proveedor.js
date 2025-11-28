const ERROR_ID_MAP = {
  username: 'error-username',
  email: 'error-email'
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
        mostrarError(usernameInput, 'Error validando usuario.');
      }
    }
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
        mostrarError(emailInput, 'Error validando email.');
      }
    }
  });
}

function togglePassword(id, hideId, showId) {
  const input = document.getElementById(id);
  const hide = document.getElementById(hideId);
  const show = document.getElementById(showId);

  if (!input || !hide || !show) return;

  if (input.type === "password") {
    input.type = "text";
    hide.style.display = "none";
    show.style.display = "inline";
  } else {
    input.type = "password";
    hide.style.display = "inline";
    show.style.display = "none";
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('form_proveedor');
  if (!form) return;

  const inputs = form.querySelectorAll('input, select');

  const usernameInput = document.getElementById('id_username');
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

    const tipo = form.querySelector('select[name="tipo_proveedor"]');
    const razon = form.querySelector('input[name="razon_social"]');
    const cuit = form.querySelector('input[name="cuit"]');
    const direccion = form.querySelector('input[name="direccion"]');
    const email = form.querySelector('input[name="email"]');
    const username = form.querySelector('input[name="username"]');
    const pass = document.getElementById('id_password');
    const pass2 = document.getElementById('id_password_confirm');

    if (tipo.value === '') {
      mostrarError(tipo, 'Debés seleccionar un tipo de proveedor.');
      valido = false;
    }

    if (razon.value.trim().length < 3) {
      mostrarError(razon, 'Ingresá un nombre válido.');
      valido = false;
    }

    const cuitValue = cuit.value.trim().replace(/-/g, '');

    if (!/^\d{11}$/.test(cuitValue)) {
      mostrarError(cuit, 'El CUIT debe tener 11 números (con o sin guiones).');
      valido = false;
    }

    if (direccion.value.trim().length < 5) {
      mostrarError(direccion, 'Ingresá una dirección válida.');
      valido = false;
    }

    if (!validarEmail(email.value.trim())) {
      mostrarError(email, 'Correo electrónico inválido.');
      valido = false;
    }

    if (username.value.trim().length < 4) {
      mostrarError(username, 'El usuario debe tener mínimo 4 caracteres.');
      valido = false;
    }

    if (pass.value.trim().length < 6) {
      mostrarError(pass, 'La contraseña debe tener al menos 6 caracteres.');
      valido = false;
    }

    if (pass.value !== pass2.value) {
      mostrarError(pass2, 'Las contraseñas no coinciden.');
      valido = false;
    }

    if (valido) form.submit();
  });
});
