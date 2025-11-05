<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="assets/css/login.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Inicio de Sesión</title>
</head>

<div class="cabecera">
        <div class="top-bar">
            <div class="logo">
                <a href="index.php">ViajAR</a>
            </div>
        </div>
    </div>

<div class="contenedor-principal">
    <?php
$message = $_GET['message'] ?? '';
$status = $_GET['status'] ?? '';
if ($message) {
    echo "<script>
        window.loginAlert = {
            message: '" . addslashes($message) . "',
            status: '" . addslashes($status) . "'
        };
    </script>";
}
?>

    <form id="id_form" method="post" action="controllers/login.controlador.php" onsubmit="return validate()">
        <input type="hidden" name="action" value="login" />
        <h2>Iniciar Sesión</h2>
        <p></p>
        <div class="input-wrapper">
            <input type="text" class="" id="nombre_usuario" placeholder="Nombre de usuario" name="nombre_usuario" >
          
              <span class="fa-icon">
                    <i class="fa-solid fa-user" style="color: #007cdb;"></i>
             </span>

            <p id="id_usuario_parrafo" class="mensaje-error"></p> 
        </div>
        <div class="input-wrapper">
            <input type="password" class="" id="password" name="password" placeholder="Contraseña" > 
           
            <span class="fa-icon">
            <i class="fa-solid fa-lock" style="color: #007cdb;"></i>
            </span>
            
           <span class="toggle-password" onclick="togglePassword()">
                <i id="hide_eye" class="fa-solid fa-eye-slash" style="color:#007cdb"></i>
                <i id="show_eye" class="fa-solid fa-eye" style="color: #007cdb;"></i>
          
            </span>
           <p id="id_password_parrafo" class="mensaje-error"></p>

        </div>
        <div class="recordar">
            <a href="index.php?page=recuperar_contrasena">¿Olvidó su contraseña?</a>
        </div>
        <button onclick="validate()" type="button" class="btn">Ingresar</button>
        <div class="registrarse">
            Quiero hacer el <a href="index.php?page=registro">registro</a>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/validaciones/login.js"></script>