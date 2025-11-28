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
    <title>Registro de Proveedor</title>
    <link rel="stylesheet" href="assets/css/registro.css">
    <script src="https://kit.fontawesome.com/ab960f05c2.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="cabecera">
    <div class="top-bar">
        <div class="logo">
            <a href="index.php">ViajAR</a>
        </div>
    </div>
</div>

<form method="post" action="controllers/usuarios/registro_proveedor.controlador.php">
    <h2>Registrá tu Servicio</h2>
    <p>Completá tus datos para registrarte como proveedor</p>

    <?php
    $formData = $_SESSION['form_data'] ?? [];
    unset($_SESSION['form_data']);
    ?>

    <!-- Tipo de proveedor -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <select name="tipo_proveedor" required>
                <option value="">Seleccioná tu servicio</option>
                <option value="1" <?php echo (isset($formData['tipo_proveedor']) && $formData['tipo_proveedor']=='1')?'selected':''; ?>>Hospedaje</option>
                <option value="2" <?php echo (isset($formData['tipo_proveedor']) && $formData['tipo_proveedor']=='2')?'selected':''; ?>>Transporte</option>
                <option value="3" <?php echo (isset($formData['tipo_proveedor']) && $formData['tipo_proveedor']=='3')?'selected':''; ?>>Guía Turístico</option>
            </select>
            <div id="error-tipo_proveedor" class="error-message"></div>
        </div>
    </div>

    <!-- Razón social / Nombre del proveedor -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" name="razon_social" placeholder="Nombre del proveedor" 
                   value="<?php echo htmlspecialchars($formData['razon_social'] ?? ''); ?>" required>
            <span class="input-icon-register"><i class="fa-solid fa-id-card" style="color:#007cdb;"></i></span>
            <div id="error-razon_social" class="error-message"></div>
        </div>
    </div>

    <!-- CUIT -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" name="cuit" placeholder="CUIT" 
                   value="<?php echo htmlspecialchars($formData['cuit'] ?? ''); ?>" required>
            <span class="input-icon-register"><i class="fa-solid fa-file-invoice" style="color:#007cdb;"></i></span>
            <div id="error-cuit" class="error-message"></div>
        </div>
    </div>

    <!-- Dirección -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" name="direccion" placeholder="Dirección" 
                   value="<?php echo htmlspecialchars($formData['direccion'] ?? ''); ?>" required>
            <span class="input-icon-register"><i class="fa-solid fa-house" style="color:#007cdb;"></i></span>
            <div id="error-direccion" class="error-message"></div>
        </div>
    </div>

    <!-- Email -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="email" name="email" placeholder="Correo Electrónico" 
                   value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
            <span class="input-icon-register"><i class="fa-solid fa-envelope" style="color:#007cdb;"></i></span>
            <div id="error-email" class="error-message"></div>
        </div>
    </div>

    <!-- Nombre de usuario -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" name="username" placeholder="Nombre de usuario" 
                   value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" required>
            <span class="input-icon-register"><i class="fa-solid fa-user" style="color:#007cdb;"></i></span>
            <div id="error-username" class="error-message"></div>
        </div>
    </div>

    <!-- Contraseña -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="password" name="password" id="id_password" placeholder="Contraseña" required>
            <span class="toggle-password" onclick="togglePassword('id_password','hide_eye','show_eye')">
                <i id="show_eye" class="fa-solid fa-eye" style="color:#007cdb;"></i>
                <i id="hide_eye" class="fa-solid fa-eye-slash" style="color:#007cdb;"></i>
            </span>
            <span class="input-icon-register"><i class="fa-solid fa-lock" style="color:#007cdb;"></i></span>
            <div id="error-password" class="error-message"></div>
        </div>
    </div>

    <!-- Confirmar contraseña -->
    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="password" name="password_confirm" id="id_password_confirm" placeholder="Confirmar Contraseña" required>
            <span class="toggle-password" onclick="togglePassword('id_password_confirm','hide_eye_confirm','show_eye_confirm')">
                <i id="show_eye_confirm" class="fa-solid fa-eye" style="color:#007cdb;"></i>
                <i id="hide_eye_confirm" class="fa-solid fa-eye-slash" style="color:#007cdb;"></i>
            </span>
            <span class="input-icon-register"><i class="fa-solid fa-lock" style="color:#007cdb;"></i></span>
            <div id="error-password_confirm" class="error-message"></div>
        </div>
    </div>

    <input class="btn" type="submit" value="Registrarse">

</form>

<script src="assets/js/validaciones/registro_proveedor.js"></script>
<script>
function togglePassword(id, hide_id, show_id){
    const input = document.getElementById(id);
    const hide = document.getElementById(hide_id);
    const show = document.getElementById(show_id);
    if(input.type === "password"){
        input.type = "text";
        hide.style.display = "none";
        show.style.display = "inline";
    } else {
        input.type = "password";
        hide.style.display = "inline";
        show.style.display = "none";
    }
}
</script>

</body>
</html>
