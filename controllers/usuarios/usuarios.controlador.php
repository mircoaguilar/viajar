<?php

require_once(__DIR__ . '/../../models/usuarios.php');

if (isset($_POST["action"])) {
    $usuario_controlador = new UsuarioControlador();

    switch ($_POST["action"]) {
        case "guardar":
            $usuario_controlador->guardar();
            break;
        case "actualizar":
            $usuario_controlador->actualizar();
            break;
        case "eliminar":
            $usuario_controlador->eliminar();
            break;
        case "cambiar_password":
            $usuario_controlador->cambiar_password();
            break;
    }
}

class UsuarioControlador {

    // Crear un nuevo usuario
    public function guardar(){
        if (empty($_POST['usuarios_nombre_usuario']) || empty($_POST['usuarios_email']) ||
            empty($_POST['rela_perfiles']) || empty($_POST['rela_personas'])) {
            header("Location: ../../index.php?message=Todos los datos son obligatorios&status=danger");
            return;
        }

        $usuarios = new Usuario();
        $usuarios->setUsuarios_nombre_usuario($_POST['usuarios_nombre_usuario']);
        $usuarios->setUsuarios_email($_POST['usuarios_email']);
        $usuarios->setUsuarios_password($_POST['usuarios_nombre_usuario']); 
        $usuarios->setRela_perfiles($_POST['rela_perfiles']);
        $usuarios->guardar();

        header("Location: ../../index.php?page=usuarios&message=Usuario guardado correctamente&status=success");
    }

    // Eliminar un usuario
    public function eliminar() {
        $usuario = new Usuario();
        $usuario->setId_usuarios($_POST['id_usuarios']);

        if ($usuario->eliminar()) {
            header("Location: ../../index.php?page=usuarios&message=Usuario eliminado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=usuarios&message=Error al eliminar usuario&status=danger");
        }
    }

    // Actualizar datos de usuario (y opcionalmente de persona asociada)
    public function actualizar() {
        if (empty($_POST['rela_personas'])) {
            header("Location: ../../index.php?page=usuarios&message=Error: La persona asociada es obligatoria&status=danger");
            return;
        }

        $usuario = new Usuario();
        $usuario->setId_usuarios($_POST['id_usuarios']);
        $usuario->setUsuarios_nombre_usuario($_POST['usuarios_nombre_usuario']);
        $usuario->setUsuarios_email($_POST['usuarios_email']);
        $usuario->setRela_perfiles($_POST['rela_perfiles']);
        $usuario->setRela_personas($_POST['rela_personas']);

        // Actualizar datos de la persona asociada si se enviaron
        if (isset($_POST['personas_nombre'], $_POST['personas_apellido'], $_POST['personas_dni'])) {
            require_once(__DIR__ . '/../../models/personas.php');
            $persona_model = new Persona();
            $persona_model->setId_personas($_POST['rela_personas']);
            $persona_model->setPersonas_nombre($_POST['personas_nombre']);
            $persona_model->setPersonas_apellido($_POST['personas_apellido']);
            $persona_model->setPersonas_dni($_POST['personas_dni']);
            $persona_model->actualizar();
        }

        if ($usuario->actualizar()) {
            header("Location: ../../index.php?page=usuarios&message=Usuario actualizado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=usuarios&message=Error al actualizar usuario&status=danger");
        }
    }

    // Listar usuarios
    public function listar_usuarios() {
        $usuario = new Usuario();
        return $usuario->traer_usuarios(); 
    }

    // Cambiar contraseña
    public function cambiar_password(){
        $id_usuario_form = $_POST['id_usuarios'] ?? null;
        $new_password = $_POST['usuarios_password'] ?? null;
        $confirm_password = $_POST['password_confirm'] ?? null;
        $token_recibido = $_POST['token'] ?? null;

        // Validar campos
        if (empty($new_password) || empty($confirm_password)) {
            $redirect_params = "message=Todos los campos son obligatorios&status=danger";
            if ($id_usuario_form) $redirect_params .= "&id_usuario=" . htmlspecialchars($id_usuario_form);
            if ($token_recibido) $redirect_params .= "&token=" . htmlspecialchars($token_recibido);
            header("Location: ../../index.php?page=cambiar_password&".$redirect_params);
            return;
        }

        if ($new_password != $confirm_password) {
            $redirect_params = "message=Las contraseñas no coinciden&status=danger";
            if ($id_usuario_form) $redirect_params .= "&id_usuario=" . htmlspecialchars($id_usuario_form);
            if ($token_recibido) $redirect_params .= "&token=" . htmlspecialchars($token_recibido);
            header("Location: ../../index.php?page=cambiar_password&".$redirect_params);
            return;
        }

        $usuario = new Usuario();
        $is_reset_flow = false;

        // Validar flujo de reset vía token
        if ($token_recibido && $id_usuario_form) {
            $is_reset_flow = true;
            if (!$usuario->validar_token_reset($id_usuario_form, $token_recibido)) {
                header("Location: ../../index.php?page=login&message=Enlace inválido o expirado&status=danger");
                exit;
            }
        } else {
            session_start();
            if (isset($_SESSION['id_usuarios'])) {
                $id_usuario_form = $_SESSION['id_usuarios'];
            } else {
                header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
                exit;
            }
        }

        // Actualizar contraseña
        $usuario->setId_usuarios($id_usuario_form);
        $usuario->setUsuarios_password($new_password);
        $usuario->cambiar_password();

        // Invalidar token si fue reset
        if ($is_reset_flow) {
            $usuario->invalidar_token_reset($id_usuario_form, $token_recibido);
        }

        // Cerrar sesión actual por seguridad
        session_start();
        session_unset();
        session_destroy();

        header("Location: ../../index.php?page=login&message=Contraseña cambiada correctamente&status=success");
        exit;
    }
}
