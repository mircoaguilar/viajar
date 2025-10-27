<?php

require_once('../../models/usuarios.php');

$form_data = array();

// Verificar acción AJAX ---
if (isset($_POST['action']) && $_POST['action'] === 'ajax') {

    // Instanciar modelo Usuario y asignar nombre de usuario ---
    $usuario = new Usuario();
    $usuario->setUsuarios_nombre_usuario($_POST['usuarios_nombre_usuario']);

    // Validar si el usuario ya existe ---
    $resultado = $usuario->validar_usuario_existente(); 

    // Devolver resultado en JSON ---
    if (!empty($resultado)) { 
        $form_data['data'] = 'error'; // El usuario ya existe
    } else {
        $form_data['data'] = 'success'; // El usuario no existe
    }

    echo json_encode($form_data);
}
?>
