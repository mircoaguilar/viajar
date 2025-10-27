<?php
session_start();

require_once('../../models/personas.php');
require_once('../../models/domicilio.php');
require_once('../../models/contacto.php');
require_once('../../models/usuarios.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- 1. Recibir y limpiar datos ---
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $fecha_nac_str = trim($_POST['fecha_nac'] ?? '');
    $domicilio_desc = trim($_POST['domicilio'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Convertir fecha a formato MySQL YYYY-MM-DD
    $fecha_nac_mysql = null;
    $date_parts = explode('/', $fecha_nac_str);
    if (count($date_parts) === 3) {
        $fecha_nac_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    }

    $errors = [];

    try {
        // --- 2. Guardar persona ---
        $persona = new Persona('', $nombre, $apellido, $dni, $fecha_nac_mysql, 1);
        $id_persona = $persona->guardar();

        if (!$id_persona) {
            $errors[] = "Error al registrar la persona.";
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            header('Location: ../../index.php?page=registro');
            exit();
        }

        // --- 3. Guardar domicilio ---
        $domicilio = new Domicilio($domicilio_desc, $id_persona);
        $domicilio->guardar();

        // --- 4. Guardar contacto (teléfono) ---
        $id_tipo_contacto_movil = 1; // Tipo contacto: móvil
        $contacto_tel = new Contacto($telefono, $id_persona, $id_tipo_contacto_movil);
        $contacto_tel->guardar();

        // --- 5. Guardar usuario ---
        $usuario = new Usuario('', $username, $email, $password, $id_persona, 1);
        $usuario->guardar();

        // --- 6. Registro exitoso: redirigir a login ---
        header('Location: ../../index.php?page=login&message=' . urlencode('¡Registro exitoso! Ya podes iniciar sesión.') . '&status=success');
        exit();

    } catch (Exception $e) {
        // Captura errores generales
        $errors[] = "Ocurrió un error durante el registro: " . $e->getMessage();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../../index.php?page=registro');
        exit();
    }

} else {
    // Si no es POST, redirige al formulario
    header('Location: ../../index.php?page=registro');
    exit();
}
?>
