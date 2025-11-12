<?php
session_start(); 

require_once(__DIR__ . '/../../models/tipo_contactos.php');

if (isset($_POST["action"])) {
    $tipo_contacto_controlador = new TipoContactoControlador();

    switch ($_POST["action"]) {
        case 'guardar':
            $tipo_contacto_controlador->guardar();
            break;
        case 'actualizar':
            $tipo_contacto_controlador->actualizar();
            break;
        case 'eliminar':
            $tipo_contacto_controlador->eliminar();
            break;
        default:
            header("Location: ../../index.php?page=tipo_contactos&message=Acción no válida&status=danger");
            exit;
    }
}

class TipoContactoControlador {

    public function guardar() {
        if (empty($_POST['tipo_contacto_descripcion'])) {
            header("Location: ../../index.php?page=tipo_contactos&message=La descripción del tipo de contacto es obligatoria&status=danger");
            exit;
        }

        $tipo_contacto = new Tipo_contacto();
        $tipo_contacto->setTipo_contacto_descripcion($_POST['tipo_contacto_descripcion']);

        $id_nuevo = $tipo_contacto->guardar();

        $mensaje = $id_nuevo ? 'Tipo de contacto guardado correctamente' : 'Error al guardar el tipo de contacto';
        $status = $id_nuevo ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipo_contactos&message=$mensaje&status=$status");
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_tipo_contacto']) || empty($_POST['tipo_contacto_descripcion'])) {
            $id_tipo_contacto_redir = htmlspecialchars($_POST['id_tipo_contacto'] ?? '');
            header("Location: ../../index.php?page=tipo_contactos&id=".$id_tipo_contacto_redir."&message=Datos incompletos para actualizar el tipo de contacto&status=danger");
            exit;
        }

        $tipo_contacto = new Tipo_contacto();
        $tipo_contacto->setId_tipo_contacto($_POST['id_tipo_contacto']); 
        $tipo_contacto->setTipo_contacto_descripcion($_POST['tipo_contacto_descripcion']);

        $ok = $tipo_contacto->actualizar();
        $mensaje = $ok ? 'Tipo de contacto actualizado correctamente' : 'Error al actualizar el tipo de contacto';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipo_contactos&id=".htmlspecialchars($_POST['id_tipo_contacto'])."&message=$mensaje&status=$status");
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_tipo_contacto_eliminar'])) {
            header("Location: ../../index.php?page=tipo_contactos&message=ID de tipo de contacto no especificado para eliminar&status=danger");
            exit;
        }

        $tipo_contacto = new Tipo_contacto();
        $tipo_contacto->setId_tipo_contacto($_POST['id_tipo_contacto_eliminar']);

        $ok = $tipo_contacto->eliminar_logico();
        $mensaje = $ok ? 'Tipo de contacto eliminado' : 'Error al eliminar el tipo de contacto';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipo_contactos&message=$mensaje&status=$status");
        exit;
    }
}
?>
