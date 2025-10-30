<?php

require_once(__DIR__ . '/../../models/perfiles.php');

if (isset($_POST["action"])) {
    $perfil_controlador = new PerfilControlador();

    switch ($_POST["action"]) {
        case 'guardar':
            $perfil_controlador->guardar();
            break;
        case 'actualizar':
            $perfil_controlador->actualizar();
            break;
        case 'eliminar':
            $perfil_controlador->eliminar();
            break;
        default:
            header("Location: ../../index.php?page=perfiles&message=Acción no válida&status=danger");
            break;
    }
}

class PerfilControlador {

    public function guardar() {
        if (empty($_POST['perfiles_nombre'])) {
            header("Location: ../../index.php?page=perfiles&message=El nombre del perfil es obligatorio&status=danger");
            exit;
        }

        $perfil = new Perfil();
        $perfil->setPerfiles_nombre($_POST['perfiles_nombre']);
        
        $id_nuevo = $perfil->guardar();

        if ($id_nuevo) {
            header("Location: ../../index.php?page=perfiles&message=Perfil guardado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=perfiles&message=Error al guardar el perfil&status=danger");
        }
        exit; 
    }

    public function actualizar() {
        if (empty($_POST['id_perfiles']) || empty($_POST['perfiles_nombre'])) {
            $id_perfil_redir = htmlspecialchars($_POST['id_perfiles'] ?? '');
            header("Location: ../../index.php?page=perfiles&id=".$id_perfil_redir."&message=Datos incompletos para actualizar el perfil&status=danger");
            exit;
        }

        $perfil = new Perfil();
        $perfil->setId_perfiles($_POST['id_perfiles']);
        $perfil->setPerfiles_nombre($_POST['perfiles_nombre']);

        if ($perfil->actualizar()) {
            header("Location: ../../index.php?page=perfiles&message=Perfil actualizado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=perfiles&id=".htmlspecialchars($_POST['id_perfiles']). "&message=Error al actualizar el perfil&status=danger");
        }
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_perfiles_eliminar'])) {
            header("Location: ../../index.php?page=perfiles&message=ID de perfil no especificado para eliminar&status=danger");
            exit;
        }

        $perfil = new Perfil();
        $perfil->setId_perfiles($_POST['id_perfiles_eliminar']);

        if ($perfil->eliminar_logico()) {
            header("Location: ../../index.php?page=perfiles&message=Perfil eliminado&status=success");
        } else {
            header("Location: ../../index.php?page=perfiles&message=Error al eliminar el perfil&status=danger");
        }
        exit;
    }
}
?>