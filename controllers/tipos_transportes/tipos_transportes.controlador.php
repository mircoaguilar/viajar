<?php
require_once(__DIR__ . '/../../models/tipo_transporte.php');

if (isset($_POST["action"])) {
    $controlador = new TipoTransporteControlador();
    switch ($_POST["action"]) {
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
            header("Location: ../../index.php?page=tipos_transportes&message=Acción no válida&status=danger");
            exit;
    }
}

class TipoTransporteControlador {

    public function guardar() {
        if (empty($_POST['descripcion'])) {
            header("Location: ../../index.php?page=tipos_transportes&message=La descripción es obligatoria&status=danger");
            exit;
        }

        $tipo = new TipoTransporte();
        $tipo->setDescripcion($_POST['descripcion']);

        $ok = $tipo->guardar();
        $mensaje = $ok ? 'Tipo de transporte guardado correctamente' : 'Error al guardar tipo de transporte';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_transportes&message=$mensaje&status=$status");
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_tipo_transporte']) || empty($_POST['descripcion'])) {
            $id = htmlspecialchars($_POST['id_tipo_transporte'] ?? '');
            header("Location: ../../index.php?page=tipos_transportes&id=$id&message=Datos incompletos&status=danger");
            exit;
        }

        $tipo = new TipoTransporte();
        $tipo->setId_tipo_transporte($_POST['id_tipo_transporte']);
        $tipo->setDescripcion($_POST['descripcion']);

        $ok = $tipo->actualizar();
        $mensaje = $ok ? 'Tipo de transporte actualizado correctamente' : 'Error al actualizar tipo de transporte';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_transportes&id=".$_POST['id_tipo_transporte']."&message=$mensaje&status=$status");
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_tipo_transporte_eliminar'])) {
            header("Location: ../../index.php?page=tipos_transportes&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $tipo = new TipoTransporte();
        $tipo->setId_tipo_transporte($_POST['id_tipo_transporte_eliminar']);

        $ok = $tipo->eliminar_logico();
        $mensaje = $ok ? 'Tipo de transporte eliminado correctamente' : 'Error al eliminar tipo de transporte';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_transportes&message=$mensaje&status=$status");
        exit;
    }
}
?>
