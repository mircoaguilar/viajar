<?php
require_once ('../../vendor/autoload.php');
require_once ('../../models/usuarios.php'); 
require_once ('../../models/auditoria.php'); 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$email_destino = $_GET['email'] ?? null;
$origin = $_GET['origin'] ?? 'public';

if (!$email_destino || !filter_var($email_destino, FILTER_VALIDATE_EMAIL)) {
    $redirect_page = $origin === 'admin' ? 'usuarios' : 'login';
    header("Location: /viajAR/index.php?page=$redirect_page&message=Email inválido o no proporcionado para el restablecimiento.&status=danger");
    exit;
}

$usuario_model = new Usuario();
$usuario_model->setUsuarios_email($email_destino);
$user_data_array = $usuario_model->validar_email(); 

if (empty($user_data_array) || !isset($user_data_array[0]['id_usuarios'])) {
    $redirect_page = $origin === 'admin' ? 'usuarios' : 'login';
    header("Location: /viajAR/index.php?page=$redirect_page&message=Solicitud de restablecimiento procesada. Si el email existe, recibirá un enlace.&status=success");
    exit;
}

$id_usuario_para_reset = $user_data_array[0]['id_usuarios']; 
$reset_token = $usuario_model->generar_token_reset($id_usuario_para_reset);

$reset_link = "http://localhost/viajAR/index.php?page=cambiar_password&id_usuario=" . urlencode($id_usuario_para_reset) . "&token=" . urlencode($reset_token);

$phpmailer = new PHPMailer(true); 

try {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587; 
    $phpmailer->Username = 'mircoaguilar02@gmail.com';
    $phpmailer->Password = 'ztfd efur zara esyo'; 
    $phpmailer->CharSet = 'UTF-8';

    $phpmailer->setFrom('mircoaguilar02@gmail.com', 'Área de Sistemas ViajAR');
    $phpmailer->addAddress($email_destino); 

    $phpmailer->isHTML(true);
    $phpmailer->Subject = 'Restablecimiento de Contraseña para ViajAR';
    $phpmailer->Body = 'Hola,<br><br>'
                     . 'Solicitaste restablecer tu contraseña para tu cuenta de ViajAR. Haz clic en el enlace:<br><br>'
                     . '<a href="' . $reset_link . '" style="background-color:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;">Restablecer Contraseña</a><br><br>'
                     . 'Este enlace expirará en 1 hora. Si no solicitaste este cambio, ignora este correo.<br><br>'
                     . 'Saludos,<br>El equipo de ViajAR';
    $phpmailer->AltBody = "Hola,\n\n"
                        . "Solicitaste restablecer tu contraseña. Copia y pega el siguiente enlace:\n$reset_link\n\n"
                        . "Este enlace expirará en 1 hora. Si no solicitaste este cambio, ignora este correo.\n\n"
                        . "Saludos,\nEl equipo de ViajAR";

    $phpmailer->send();

    $auditoria = new Auditoria(
        '', 
        $_SESSION['id_usuarios'] ?? null,
        'Envío de email',
        "Se envió un email de restablecimiento de contraseña a $email_destino"
    );
    $auditoria->guardar();

    $redirect_page = $origin === 'admin' ? 'usuarios' : 'login';
    header("Location: /viajAR/index.php?page=$redirect_page&message=Email de restablecimiento enviado correctamente.&status=success");
    exit;

} catch (Exception $e) {
    error_log("Error al enviar el email a $email_destino: " . $phpmailer->ErrorInfo);

    $auditoria = new Auditoria(
        '', 
        $_SESSION['id_usuarios'] ?? null,
        'Error envío email',
        "Falló el intento de envío de restablecimiento a $email_destino"
    );
    $auditoria->guardar();

    $redirect_page = $origin === 'admin' ? 'usuarios' : 'login';
    header("Location: /viajAR/index.php?page=$redirect_page&message=Error al enviar el email. Verifica la configuración o intenta más tarde.&status=danger");
    exit;
}
?>
