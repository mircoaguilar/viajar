<?php
require_once(__DIR__ . '/../../models/tipo_proveedor.php');

if (isset($_POST["action"])) {
    $controlador = new TiposProveedoresControlador();
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
            header("Location: ../../index.php?page=tipos_proveedores&message=Acción no válida&status=danger");
            exit;
    }
}

class TiposProveedoresControlador {

    public function guardar() {
        if (empty($_POST['nombre'])) {
            header("Location: ../../index.php?page=tipos_proveedores&message=El nombre es obligatorio&status=danger");
            exit;
        }

        $tipo = new Tipo_proveedor();
        $tipo->setNombre($_POST['nombre']);
        $tipo->setDescripcion($_POST['descripcion'] ?? '');

        $ok = $tipo->guardar();
        $mensaje = $ok ? 'Tipo de proveedor guardado correctamente' : 'Error al guardar tipo de proveedor';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_proveedores&message=$mensaje&status=$status");
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_tipo_proveedor']) || empty($_POST['nombre'])) {
            $id = htmlspecialchars($_POST['id_tipo_proveedor'] ?? '');
            header("Location: ../../index.php?page=tipos_proveedores&id=$id&message=Datos incompletos&status=danger");
            exit;
        }

        $tipo = new Tipo_proveedor();
        $tipo->setId_tipo_proveedor($_POST['id_tipo_proveedor']);
        $tipo->setNombre($_POST['nombre']);
        $tipo->setDescripcion($_POST['descripcion'] ?? '');

        $ok = $tipo->actualizar();
        $mensaje = $ok ? 'Tipo de proveedor actualizado correctamente' : 'Error al actualizar tipo de proveedor';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_proveedores&id=".$_POST['id_tipo_proveedor']."&message=$mensaje&status=$status");
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_tipo_proveedor_eliminar'])) {
            header("Location: ../../index.php?page=tipos_proveedores&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $tipo = new Tipo_proveedor();
        $tipo->setId_tipo_proveedor($_POST['id_tipo_proveedor_eliminar']);

        $ok = $tipo->eliminar_logico();
        $mensaje = $ok ? 'Tipo de proveedor eliminado correctamente' : 'Error al eliminar tipo de proveedor';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_proveedores&message=$mensaje&status=$status");
        exit;
    }
}
?>
