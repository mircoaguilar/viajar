<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="assets/css/login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
</head>
<body>

<?php

    $id_usuario_a_cambiar = $_GET['id_usuario'] ?? ($_SESSION['id_usuarios'] ?? null);
    $token_presente = $_GET['token'] ?? null; 
    
    if (!$id_usuario_a_cambiar) {
        header("Location: index.php?page=login&message=Acceso no autorizado al cambio de contraseña. Inicie sesión o solicite un restablecimiento.&status=danger");
        exit;
    }
?>

<div class="contenedor-principal">
    <form id="cambiar_password_form" method="post" action="controllers/usuarios/usuarios.controlador.php">
        <input type="hidden" name="action" value="cambiar_password" />
        
        <input type="hidden" name="id_usuarios" value="<?php echo htmlspecialchars($id_usuario_a_cambiar); ?>" />
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token_presente ?? ''); ?>" />

        <h2>Cambiar Contraseña</h2> 
        <?php if (isset($_GET['message'])): ?>
        <div class="alert <?php echo ($_GET['status'] == 'success') ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
        <?php endif; ?>
        
        <p>Por favor, ingrese su nueva contraseña.</p>

        <div class="input-wrapper">
             <input type="password" id="password" name="usuarios_password" placeholder="Nueva Contraseña">
                <span class="fa-icon">
                     <i class="fa-solid fa-lock" style="color: #007cdb;"></i>
                </span>
             <span class="toggle-password" onclick="togglePassword('password', 'hide_eye_1', 'show_eye_1')">
              <i id="hide_eye_1" class="fa-solid fa-eye-slash" style="color:#007cdb; display: block;"></i>
              <i id="show_eye_1" class="fa-solid fa-eye" style="color: #007cdb; display: none;"></i>
             </span>
             <p id="password_error" class="mensaje-error"></p>
         </div>

        <div class="input-wrapper">
        <input type="password" id="password_confirm" name="password_confirm" placeholder="Confirmar nueva contraseña" >
        <span class="fa-icon">
        <i class="fa-solid fa-lock" style="color: #007cdb;"></i>
        </span>
        <span class="toggle-password" onclick="togglePassword('password_confirm', 'hide_eye_2', 'show_eye_2')">
        <i id="hide_eye_2" class="fa-solid fa-eye-slash" style="color:#007cdb; display: block;"></i>
        <i id="show_eye_2" class="fa-solid fa-eye" style="color: #007cdb; display: none;"></i>
        </span>
        <p id="password_confirm_error" class="mensaje-error"></p>
        </div>
        
        <button type="button" class="btn-confirmpassword" onclick="validateForm()">Cambiar Contraseña</button>


    </form>
</div>
<script src="assets/js/validaciones/cambiar_password.js"></script>
</body>
</html>