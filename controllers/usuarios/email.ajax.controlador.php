<?php
require_once('../../models/usuarios.php');

$form_data = array();

// Controlador para validación AJAX de email
if (isset($_POST['action']) && $_POST['action'] === 'ajax') {
    $usuario = new Usuario();
    $usuario->setUsuarios_email($_POST['usuarios_email']);

    // Comprueba si el email ya existe en la base de datos
    $resultado = $usuario->validar_email(); 

    // Retorna 'error' si existe, 'success' si no
    $form_data['data'] = !empty($resultado) ? 'error' : 'success';

    echo json_encode($form_data);
}
?>
