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

<form id="form_proveedor" method="post" action="controllers/proveedores/registro.proveedor.controlador.php">
    <h2>Registrá tu Servicio</h2>
    <p>Completá tus datos para registrarte como proveedor</p>

    <?php
    $formData = $_SESSION['form_data'] ?? [];
    unset($_SESSION['form_data']);
    if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
        echo '<div class="alert alert-danger" style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">';
        echo '<ul>';
        foreach ($_SESSION['errors'] as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        unset($_SESSION['errors']); 
    }
    ?>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <select name="tipo_proveedor">
                <option value="">Seleccioná tu servicio</option>
                <option value="1" <?php echo (isset($formData['tipo_proveedor']) && $formData['tipo_proveedor']=='1')?'selected':''; ?>>Hospedaje</option>
                <option value="2" <?php echo (isset($formData['tipo_proveedor']) && $formData['tipo_proveedor']=='2')?'selected':''; ?>>Transporte</option>
                <option value="3" <?php echo (isset($formData['tipo_proveedor']) && $formData['tipo_proveedor']=='3')?'selected':''; ?>>Guía Turístico</option>
            </select>
            <span class="input-icon-register">
                <i class="fa-solid fa-suitcase-rolling" style="color:#007cdb;"></i>
            </span>
            <div id="error-tipo_proveedor" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" name="razon_social" placeholder="Nombre del proveedor" 
                   value="<?php echo htmlspecialchars($formData['razon_social'] ?? ''); ?>" >
            <span class="input-icon-register"><i class="fa-solid fa-id-card" style="color:#007cdb;"></i></span>
            <div id="error-razon_social" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" name="cuit" placeholder="CUIT" 
                   value="<?php echo htmlspecialchars($formData['cuit'] ?? ''); ?>" >
            <span class="input-icon-register"><i class="fa-solid fa-file-invoice" style="color:#007cdb;"></i></span>
            <div id="error-cuit" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" name="direccion" placeholder="Dirección" 
                   value="<?php echo htmlspecialchars($formData['direccion'] ?? ''); ?>" >
            <span class="input-icon-register"><i class="fa-solid fa-house" style="color:#007cdb;"></i></span>
            <div id="error-direccion" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" id="id_email" name="email" placeholder="Correo Electrónico"
                   value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" >
            <span class="input-icon-register"><i class="fa-solid fa-envelope" style="color:#007cdb;"></i></span>
            <div id="error-email" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="text" id="id_username" name="username" placeholder="Nombre de usuario"
                   value="<?php echo htmlspecialchars($formData['username'] ?? ''); ?>" >
            <span class="input-icon-register"><i class="fa-solid fa-user" style="color:#007cdb;"></i></span>
            <div id="error-username" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="password" name="password" id="id_password" placeholder="Contraseña">
            <span class="toggle-password" onclick="togglePassword('id_password','hide_eye_1','show_eye_1')">
                <i id="show_eye_1" class="fa-solid fa-eye" style="color:#007cdb; display:none;"></i>
                <i id="hide_eye_1" class="fa-solid fa-eye-slash" style="color:#007cdb;"></i>
            </span>
            <span class="input-icon-register"><i class="fa-solid fa-lock" style="color:#007cdb;"></i></span>
            <div id="error-password" class="error-message"></div>
        </div>
    </div>

    <div class="form-row">
        <div class="input-wrapper full-width">
            <input type="password" name="password_confirm" id="id_password_confirm" placeholder="Confirmar Contraseña">
            <span class="toggle-password" onclick="togglePassword('id_password_confirm','hide_eye_2','show_eye_2')">
                <i id="show_eye_2" class="fa-solid fa-eye" style="color:#007cdb; display:none;"></i>
                <i id="hide_eye_2" class="fa-solid fa-eye-slash" style="color:#007cdb;"></i>
            </span>
            <span class="input-icon-register"><i class="fa-solid fa-lock" style="color:#007cdb;"></i></span>
            <div id="error-password_confirm" class="error-message"></div>
        </div>
    </div>

    <input class="btn" type="submit" value="Registrarse">

</form>

<script src="assets/js/registro_proveedor.js"></script>
</body>
</html>
