<?php
require_once(__DIR__ . '/../../models/tipo_habitacion.php');

if (isset($_POST["action"])) {
    $controlador = new TiposHabitacionesControlador();
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
            header("Location: ../../index.php?page=tipos_habitaciones&message=Acción no válida&status=danger");
            exit;
    }
}

class TiposHabitacionesControlador {

    // Guarda un nuevo tipo de habitación
    public function guardar() {
        if (empty($_POST['nombre']) || empty($_POST['capacidad'])) {
            header("Location: ../../index.php?page=tipos_habitaciones&message=Nombre y capacidad son obligatorios&status=danger");
            exit;
        }

        $tipo = new TipoHabitacion();
        $tipo->setNombre($_POST['nombre']);
        $tipo->setDescripcion($_POST['descripcion'] ?? '');
        $tipo->setCapacidad($_POST['capacidad']);

        $ok = $tipo->guardar();
        $mensaje = $ok ? 'Tipo de habitación guardado correctamente' : 'Error al guardar tipo de habitación';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_habitaciones&message=$mensaje&status=$status");
        exit;
    }

    // Actualiza un tipo de habitación existente
    public function actualizar() {
        if (empty($_POST['id_tipo_habitacion']) || empty($_POST['nombre']) || empty($_POST['capacidad'])) {
            $id = htmlspecialchars($_POST['id_tipo_habitacion'] ?? '');
            header("Location: ../../index.php?page=tipos_habitaciones&id=$id&message=Datos incompletos&status=danger");
            exit;
        }

        $tipo = new TipoHabitacion();
        $tipo->setId_tipo_habitacion($_POST['id_tipo_habitacion']);
        $tipo->setNombre($_POST['nombre']);
        $tipo->setDescripcion($_POST['descripcion'] ?? '');
        $tipo->setCapacidad($_POST['capacidad']);

        $ok = $tipo->actualizar();
        $mensaje = $ok ? 'Tipo de habitación actualizado correctamente' : 'Error al actualizar tipo de habitación';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_habitaciones&id=".$_POST['id_tipo_habitacion']."&message=$mensaje&status=$status");
        exit;
    }

    // Elimina un tipo de habitación de forma lógica
    public function eliminar() {
        if (empty($_POST['id_tipo_habitacion_eliminar'])) {
            header("Location: ../../index.php?page=tipos_habitaciones&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $tipo = new TipoHabitacion();
        $tipo->setId_tipo_habitacion($_POST['id_tipo_habitacion_eliminar']);

        $ok = $tipo->eliminar_logico();
        $mensaje = $ok ? 'Tipo de habitación eliminado correctamente' : 'Error al eliminar tipo de habitación';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipos_habitaciones&message=$mensaje&status=$status");
        exit;
    }
}
?>
