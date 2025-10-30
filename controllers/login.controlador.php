<?php
require_once "../models/usuarios.php";
require_once "../models/perfiles.php";
require_once "../models/proveedor.php";

session_start();

if (isset($_POST["action"]) && $_POST["action"] === "login") {
    $login_controlador = new LoginControlador();
    $login_controlador->ingresar();
}

class LoginControlador {

    public function ingresar() {
        $usuario = new Usuario();
        $perfil = new Perfil();
        $proveedor = new Proveedor();

        $nombre_usuario_ingresado = trim($_POST["nombre_usuario"] ?? '');
        $password_ingresada = trim($_POST["password"] ?? '');

        if (empty($nombre_usuario_ingresado) || empty($password_ingresada)) {
            header("Location: ../index.php?page=login&message=Todos los campos son obligatorios&status=danger");
            exit();
        }

        $usuario->setUsuarios_nombre_usuario($nombre_usuario_ingresado);
        $resultado = $usuario->validar_usuario();

        if (empty($resultado)) {
            header("Location: ../index.php?page=login&message=Usuario o Contraseña incorrecto&status=danger");
            exit();
        }

        $row = $resultado[0];

        if (!password_verify($password_ingresada, $row['usuarios_password'])) {
            header("Location: ../index.php?page=login&message=Usuario o Contraseña incorrecto&status=danger");
            exit();
        }

        $_SESSION['usuarios_nombre_usuario'] = $row['usuarios_nombre_usuario'];
        $_SESSION['id_usuarios'] = $row['id_usuarios'];
        $_SESSION['usuarios_email'] = $row['usuarios_email'];

        $resultado_perfiles = $perfil->traer_perfil($row['rela_perfiles']);
        if (empty($resultado_perfiles)) {
            header("Location: ../index.php?page=login&message=Error de perfil de usuario&status=danger");
            exit();
        }
        $row_perfil = $resultado_perfiles[0];
        $_SESSION['id_perfiles'] = $row_perfil['id_perfiles'];
        $_SESSION['perfiles_nombre'] = $row_perfil['perfiles_nombre'];

        if (password_verify($nombre_usuario_ingresado, $row['usuarios_password'])) {
            $_SESSION['es_nuevo_usuario'] = true;
            header("Location: ../index.php?page=cambiar_password&message=Usted es un usuario nuevo, cambie su password&status=danger");
            exit();
        }

        $id_proveedor = $proveedor->obtenerPorUsuario($row['id_usuarios']);
        if ($id_proveedor) {
            $_SESSION['id_proveedores'] = $id_proveedor;
        }

        switch ($_SESSION['perfiles_nombre']) {
            case 'Administrador':
                $redirect = "index.php?page=administrador_perfil";
                break;
            case 'Administrador de hospedaje':
            case 'Encargado de transporte':
            case 'Guia':
                $redirect = "index.php?page=proveedores_perfil";
                break;
            case 'Cliente':
            default:
                $redirect = "index.php?page=pantalla_hoteles";
                break;
        }

        $redirect .= (strpos($redirect, '?') === false ? '?' : '&') . "message=" . urlencode("Bienvenido, " . $row['usuarios_nombre_usuario']) . "&status=success";

        header("Location: ../$redirect");
        exit();
    }
}
