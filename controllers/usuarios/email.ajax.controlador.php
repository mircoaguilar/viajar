<?php
require_once('../../models/usuarios.php');

$form_data = array();

if (isset($_POST['action']) && $_POST['action'] === 'ajax') {
    $usuario = new Usuario();
    $usuario->setUsuarios_email($_POST['usuarios_email']);

    $resultado = $usuario->validar_email(); 

    $form_data['data'] = !empty($resultado) ? 'error' : 'success';

    echo json_encode($form_data);
}
?>
