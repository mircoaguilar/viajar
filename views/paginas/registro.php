<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="assets/css/registro.css">
</head>
<body>
<div class="cabecera">
    <div class="top-bar">
        <div class="logo">
            <a href="index.php">ViajAR</a>
        </div>
    </div>
</div>

<form method="post" action="controllers/usuarios/registro.controlador.php">
    <h2>Bienvenido</h2>
    <p>Iniciá tu registro</p>

    <?php
    $formData = $_SESSION['form_data'] ?? [];
    unset($_SESSION['form_data']);
    ?>

    <div class="form-row">
        <div class="input-wrapper">
            <input type="text" name="nombre" placeholder="Nombre"
                value="<?php echo htmlspecialchars($formData['nombre'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-id-card" style="color: #007cdb;"></i></span>
            <div id="error-nombre" class="error-message"></div>
        </div>

        <div class="input-wrapper">
            <input type="text" name="apellido" placeholder="Apellido"
                value="<?php echo htmlspecialchars($formData['apellido'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-id-card" style="color: #007cdb;"></i></span>
            <div id="error-apellido" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper">
            <input type="text" name="dni" placeholder="Número de DNI"
                value="<?php echo htmlspecialchars($formData['dni'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-id-card" style="color: #007cdb;"></i></span>
            <div id="error-dni" class="error-message"></div>
        </div>

        <div class="input-wrapper">
            <input type="text" name="fecha_nac" id="fecha_nac" placeholder="Fecha de nacimiento"
                value="<?php echo htmlspecialchars($formData['fecha_nac'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-calendar" style="color: #007cdb;"></i></span>
            <div id="error-fecha_nac" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper">
            <input type="text" name="domicilio" placeholder="Domicilio"
                value="<?php echo htmlspecialchars($formData['domicilio'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-house" style="color: #007cdb;"></i></span>
            <div id="error-domicilio" class="error-message"></div>
        </div>

        <div class="input-wrapper">
            <input type="text" name="telefono" placeholder="Teléfono"
                value="<?php echo htmlspecialchars($formData['telefono'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-phone" style="color: #007cdb;"></i></span>
            <div id="error-telefono" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper">
            <input type="text" id="id_nombre_usuario" name="username" placeholder="Nombre de usuario"
                value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-user" style="color: #007cdb;"></i></span>
            <p id="error-usuario" class="error-message"></p>
        </div>

        <div class="input-wrapper">
            <input type="email" id="id_email" name="email" placeholder="Correo Electrónico"
                value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>">
            <span class="input-icon-register"><i class="fa-solid fa-envelope" style="color: #007cdb;"></i></span>
            <p id="error-email" class="error-message"></p>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="password" name="password" id="id_password_registro" placeholder="Contraseña">
            <span class="toggle-password" onclick="togglePassword('id_password_registro','hide_eye','show_eye')">
                <i id="show_eye" class="fa-solid fa-eye" style="color:#007cdb;"></i>
                <i id="hide_eye" class="fa-solid fa-eye-slash" style="color:#007cdb;"></i>
            </span>
            <span class="input-icon-register"><i class="fa-solid fa-lock" style="color:#007cdb;"></i></span>
            <div id="error-password" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="password" name="password_confirm" id="id_password_confirm_registro" placeholder="Confirmar Contraseña">
            <span class="toggle-password" onclick="togglePassword('id_password_confirm_registro','hide_eye_confirm','show_eye_confirm')">
                <i id="show_eye_confirm" class="fa-solid fa-eye" style="color:#007cdb;"></i>
                <i id="hide_eye_confirm" class="fa-solid fa-eye-slash" style="color:#007cdb;"></i>
            </span>
            <span class="input-icon-register"><i class="fa-solid fa-lock" style="color:#007cdb;"></i></span>
            <div id="error-password_confirm" class="error-message"></div>
        </div>
    </div>

    <input class="btn" type="submit" value="Registrarse">
</form>

<script src="assets/js/validaciones/registro.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://kit.fontawesome.com/ab960f05c2.js" crossorigin="anonymous"></script>
<script>
    flatpickr("#fecha_nac", {
        dateFormat: "d/m/Y",
        maxDate: "today",
        locale: "es"
    });
</script>
</body>
</html>