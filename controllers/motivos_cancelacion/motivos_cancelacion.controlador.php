<?php
require_once(__DIR__ . '/../../models/motivo_cancelacion.php');

if (isset($_POST['action'])) {
    $controlador = new MotivoCancelacionControlador();
    switch($_POST['action']) {
        case 'guardar':
            $controlador->guardar();
            break;
        case 'actualizar':
            $controlador->actualizar();
            break;
        case 'eliminar':
            $controlador->eliminar();
            break;
        default:
            header("Location: ../../index.php?page=motivos_cancelacion&message=Acción no válida&status=danger");
            exit;
    }
}

class MotivoCancelacionControlador {

    public function guardar() {
        if (empty($_POST['descripcion'])) {
            header("Location: ../../index.php?page=motivos_cancelacion&message=La descripción es obligatoria&status=danger");
            exit;
        }

        $motivo = new MotivoCancelacion();
        $motivo->setDescripcion($_POST['descripcion']);

        if ($motivo->guardar()) {
            header("Location: ../../index.php?page=motivos_cancelacion&message=Motivo guardado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=motivos_cancelacion&message=Error al guardar motivo&status=danger");
        }
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_motivo_cancelacion']) || empty($_POST['descripcion'])) {
            header("Location: ../../index.php?page=motivos_cancelacion&message=Datos incompletos&status=danger");
            exit;
        }

        $motivo = new MotivoCancelacion();
        $motivo->setId_motivo_cancelacion($_POST['id_motivo_cancelacion']);
        $motivo->setDescripcion($_POST['descripcion']);

        if ($motivo->actualizar()) {
            header("Location: ../../index.php?page=motivos_cancelacion&message=Motivo actualizado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=motivos_cancelacion&id=".$_POST['id_motivo_cancelacion']."&message=Error al actualizar&status=danger");
        }
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_motivo_cancelacion'])) {
            header("Location: ../../index.php?page=motivos_cancelacion&message=ID de motivo no proporcionado&status=danger");
            exit;
        }

        $motivo = new MotivoCancelacion();
        $motivo->setId_motivo_cancelacion($_POST['id_motivo_cancelacion']);

        if ($motivo->eliminar_logico()) {
            header("Location: ../../index.php?page=motivos_cancelacion&message=Motivo eliminado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=motivos_cancelacion&id=".$_POST['id_motivo_cancelacion']."&message=Error al eliminar&status=danger");
        }
        exit;
    }
}
