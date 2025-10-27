<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="assets/css/login.css"> <!-- Usamos tu login.css -->
    <script src="https://kit.fontawesome.com/4b139d7caf.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="cabecera">
        <div class="top-bar">
            <div class="logo">
                <a href="index.php">ViajAR</a>
            </div>
           <!-- <div class="right-links"> 
                <a href="index.php?page=registro" class="button">Crear una cuenta</a>
                <a href="index.php?page=login" class="button">Iniciar Sesión</a>
            </div>-->
        </div>
    </div>

<div class="contenedor-principal">
    <form method="get" action="controllers/usuarios/email.controlador.php">
        <h2>¿Olvidaste tu Contraseña?</h2>

        <?php if (isset($_GET['message'])): ?>
        <div class="alert <?php echo ($_GET['status'] == 'success') ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
        <?php endif; ?>

        <p>Ingresá tu correo electrónico registrado y te enviaremos un enlace para restablecer tu contraseña.</p>

        <div class="input-wrapper">
            <input type="email" name="email" placeholder="Correo electrónico" >
            <span class="fa-icon">
                <i class="fa-solid fa-envelope" style="color: #007cdb;"></i>
            </span>
        </div>

        <button type="submit" class="btn-confirmpassword">Enviar Enlace</button>
    </form>
</div>

</body>
</html>
