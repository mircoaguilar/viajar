<?php
session_start();

require_once(__DIR__ . '/../../models/usuarios.php');
require_once(__DIR__ . '/../../models/proveedor.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_proveedor     = trim($_POST['tipo_proveedor'] ?? '');
    $razon_social       = trim($_POST['razon_social'] ?? '');
    $cuit               = trim($_POST['cuit'] ?? '');
    $direccion          = trim($_POST['direccion'] ?? '');
    $email              = trim($_POST['email'] ?? '');
    $username           = trim($_POST['username'] ?? '');
    $password           = $_POST['password'] ?? '';
    $password_confirm   = $_POST['password_confirm'] ?? '';

    $errors = [];

    if (!$tipo_proveedor) $errors[] = "Debe seleccionar un tipo de proveedor.";
    if (!$razon_social)   $errors[] = "Debe ingresar la razón social.";
    if (!$cuit)           $errors[] = "Debe ingresar el CUIT.";
    if (!$direccion)      $errors[] = "Debe ingresar la dirección.";
    if (!$email)          $errors[] = "Debe ingresar un email.";
    if (!$username)       $errors[] = "Debe ingresar un nombre de usuario.";
    if (!$password)       $errors[] = "Debe ingresar una contraseña.";
    if ($password !== $password_confirm) $errors[] = "Las contraseñas no coinciden.";

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../../index.php?page=registro_proveedor');
        exit();
    }

    try {
        switch ($tipo_proveedor) {
            case '1': $perfil = 3;  break; 
            case '2': $perfil = 5;  break; 
            case '3': $perfil = 14; break; 
            default:
                throw new Exception("Tipo de proveedor inválido.");
        }
        $id_usuario = Proveedor::crearUsuarioProveedor($username, $email, $password, $perfil);

        if (!$id_usuario) {
            throw new Exception("Error al crear el usuario. El nombre de usuario o email pueden estar en uso.");
        }

        $proveedor = new Proveedor(
            '',                 
            $razon_social,      
            $cuit,              
            $direccion,        
            $email,             
            $tipo_proveedor,   
            $id_usuario,        
            'pendiente'         
        );

        $id_proveedor = $proveedor->guardar();
        if (!$id_proveedor) {
            throw new Exception("Error al registrar los datos del proveedor.");
        }

        header('Location: ../../index.php?page=login&message=' .
            urlencode('¡Registro exitoso! Tu cuenta está en revisión.') .
            '&status=success');
        exit();

    } catch (Exception $e) {

        $errors[] = "Ocurrió un error durante el registro: " . $e->getMessage();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;

        header('Location: ../../index.php?page=registro_proveedor');
        exit();
    }

} else {
    header('Location: ../../index.php?page=registro_proveedor');
    exit();
}