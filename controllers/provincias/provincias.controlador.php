<?php
require_once(__DIR__ . '/../../models/Provincia.php');

if (isset($_POST["action"])) {
    $controlador = new ProvinciaControlador();
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
            header("Location: ../../index.php?page=provincias&message=Acción no válida&status=danger");
            exit;
    }
}

class ProvinciaControlador {

    // Guardar nueva provincia
    public function guardar() {
        if (empty($_POST['nombre'])) {
            header("Location: ../../index.php?page=provincias&message=El nombre es obligatorio&status=danger");
            exit;
        }

        $provincia = new Provincia();
        $provincia->setNombre($_POST['nombre']);

        $id_nuevo = $provincia->guardar();
        $mensaje = $id_nuevo ? 'Provincia guardada correctamente' : 'Error al guardar provincia';
        $status = $id_nuevo ? 'success' : 'danger';
        header("Location: ../../index.php?page=provincias&message=$mensaje&status=$status");
        exit;
    }

    // Actualizar provincia existente
    public function actualizar() {
        if (empty($_POST['id_provincia']) || empty($_POST['nombre'])) {
            header("Location: ../../index.php?page=provincias&message=Datos incompletos&status=danger");
            exit;
        }

        $provincia = new Provincia();
        $provincia->setId_provincia($_POST['id_provincia']);
        $provincia->setNombre($_POST['nombre']);

        $ok = $provincia->actualizar();
        $mensaje = $ok ? 'Provincia actualizada correctamente' : 'Error al actualizar provincia';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=provincias&message=$mensaje&status=$status");
        exit;
    }

    // Eliminación lógica de provincia
    public function eliminar() {
        if (empty($_POST['id_provincia_eliminar'])) {
            header("Location: ../../index.php?page=provincias&message=ID no especificado&status=danger");
            exit;
        }

        $provincia = new Provincia();
        $provincia->setId_provincia($_POST['id_provincia_eliminar']);
        $ok = $provincia->eliminar_logico();

        $mensaje = $ok ? 'Provincia eliminada correctamente' : 'Error al eliminar provincia';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=provincias&message=$mensaje&status=$status");
        exit;
    }
}
?>
