<?php

require_once(__DIR__ . '/../../models/tipo_documentos.php');

if (isset($_POST['action'])) {
    $controlador = new TipoDocumentoControlador();

    switch ($_POST['action']) {
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
            header("Location: ../../index.php?page=tipos_documentos&message=Acción no válida&status=danger");
            exit;
    }
}

class TipoDocumentoControlador {

    public function guardar() {
        if (empty($_POST['nombre'])) {
            header("Location: ../../index.php?page=tipos_documentos&message=El nombre es obligatorio&status=danger");
            exit;
        }

        $tipo_documento = new TipoDocumento();
        $tipo_documento->setNombre($_POST['nombre']);

        $ok = $tipo_documento->guardar();
        $mensaje = $ok ? 'Tipo de documento guardado correctamente' : 'Error al guardar tipo de documento';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_documentos&message=$mensaje&status=$status");
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_tipo_documento']) || empty($_POST['nombre'])) {
            header("Location: ../../index.php?page=tipos_documentos&message=Datos incompletos&status=danger");
            exit;
        }

        $tipo_documento = new TipoDocumento();
        $tipo_documento->setId_tipo_documento($_POST['id_tipo_documento']);
        $tipo_documento->setNombre($_POST['nombre']);

        $ok = $tipo_documento->actualizar();
        $mensaje = $ok ? 'Tipo de documento actualizado correctamente' : 'Error al actualizar tipo de documento';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_documentos&id=".$_POST['id_tipo_documento']."&message=$mensaje&status=$status");
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_tipo_documento_eliminar'])) {
            header("Location: ../../index.php?page=tipos_documentos&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $tipo_documento = new TipoDocumento();
        $tipo_documento->setId_tipo_documento($_POST['id_tipo_documento_eliminar']);

        $ok = $tipo_documento->eliminar_logico();
        $mensaje = $ok ? 'Tipo de documento eliminado correctamente' : 'Error al eliminar tipo de documento';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_documentos&message=$mensaje&status=$status");
        exit;
    }
}
?>
