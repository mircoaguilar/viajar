<?php
session_start();

require_once('../../models/usuarios.php');
require_once('../../models/proveedores.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $tipo_proveedor = trim($_POST['tipo_proveedor'] ?? '');
    $razon_social = trim($_POST['razon_social'] ?? '');
    $cuit = trim($_POST['cuit'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    $errors = [];

    if (!$tipo_proveedor) $errors[] = "Debe seleccionar un tipo de servicio.";
    if (!$razon_social) $errors[] = "Debe ingresar el nombre del proveedor.";
    if (!$cuit) $errors[] = "Debe ingresar el CUIT.";
    if (!$direccion) $errors[] = "Debe ingresar la dirección.";
    if (!$email) $errors[] = "Debe ingresar un email.";
    if (!$username) $errors[] = "Debe ingresar un nombre de usuario.";
    if (!$password) $errors[] = "Debe ingresar una contraseña.";
    if ($password !== $password_confirm) $errors[] = "Las contraseñas no coinciden.";

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header('Location: ../../registro_proveedor.php');
        exit();
    }

    try {
        $usuario = new Usuario('', $username, $email, $password, null);
        switch ($tipo_proveedor) {
            case '1': $perfil = 3; break; 
            case '2': $perfil = 5; break; 
            case '3': $perfil = 14; break; 
        }
        $usuario->setRela_perfiles($perfil);

        $id_usuario = $usuario->guardar();
        if (!$id_usuario) throw new Exception("Error al crear el usuario.");
        $proveedor = new Proveedor('', $razon_social, $cuit, $direccion, $email, $id_usuario, 'pendiente', 1);
        $id_proveedor = $proveedor->guardar();
        if (!$id_proveedor) throw new Exception("Error al crear el proveedor.");

        header('Location: ../../index.php?page=login&message=' . urlencode('¡Registro exitoso! Tu cuenta está en revisión y pronto será aprobada.') . '&status=success');
        exit();

    } catch (Exception $e) {
        $_SESSION['errors'] = ["Ocurrió un error durante el registro: " . $e->getMessage()];
        $_SESSION['form_data'] = $_POST;
        header('Location: ../../registro_proveedor.php');
        exit();
    }

} else {
    header('Location: ../../registro_proveedor.php');
    exit();
}
