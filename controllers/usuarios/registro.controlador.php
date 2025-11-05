<?php
session_start();

require_once('../../models/personas.php');
require_once('../../models/domicilio.php');
require_once('../../models/contacto.php');
require_once('../../models/usuarios.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $fecha_nac_str = trim($_POST['fecha_nac'] ?? '');
    $domicilio_desc = trim($_POST['domicilio'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $fecha_nac_mysql = null;
    $date_parts = explode('/', $fecha_nac_str);
    if (count($date_parts) === 3) {
        $fecha_nac_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }

    $errors = [];

    try {
        $persona = new Persona('', $nombre, $apellido, $dni, $fecha_nac_mysql, 1);
        $id_persona = $persona->guardar();

        if (!$id_persona) {
            $errors[] = "Error al registrar la persona.";
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: ../../index.php?page=registro');
            exit();
        }

        $domicilio = new Domicilio($domicilio_desc, $id_persona);
        $domicilio->guardar();

        $id_tipo_contacto_movil = 1; 
        $contacto_tel = new Contacto($telefono, $id_persona, $id_tipo_contacto_movil);
        $contacto_tel->guardar();

        $usuario = new Usuario('', $username, $email, $password, $id_persona, 1);
        $usuario->guardar();

        header('Location: ../../index.php?page=login&message=' . urlencode('¡Registro exitoso! Ya podes iniciar sesión.') . '&status=success');
        exit();

    } catch (Exception $e) {
        $errors[] = "Ocurrió un error durante el registro: " . $e->getMessage();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../../index.php?page=registro');
        exit();
    }

} else {
    header('Location: ../../index.php?page=registro');
    exit();
}
?>
