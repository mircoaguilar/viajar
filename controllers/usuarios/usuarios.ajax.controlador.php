<?php

require_once('../../models/usuarios.php');

$form_data = array();

if (isset($_POST['action']) && $_POST['action'] === 'ajax') {

    $usuario = new Usuario();
    $usuario->setUsuarios_nombre_usuario($_POST['usuarios_nombre_usuario']);

    $resultado = $usuario->validar_usuario_existente(); 

    if (!empty($resultado)) { 
        $form_data['data'] = 'error'; 
    } else {
        $form_data['data'] = 'success';
    }

    echo json_encode($form_data);
}
?>
