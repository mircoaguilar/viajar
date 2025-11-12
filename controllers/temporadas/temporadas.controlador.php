<?php
session_start(); 
require_once(__DIR__ . '/../../models/Temporada.php');

if (isset($_POST['action'])) {
    $controlador = new TemporadaControlador();
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
            header("Location: ../../index.php?page=temporadas&message=Acción no válida&status=danger");
            exit;
    }
}

class TemporadaControlador {

    public function guardar() {
        if (empty($_POST['nombre']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin'])) {
            header("Location: ../../index.php?page=temporadas&message=Todos los campos son obligatorios&status=danger");
            exit;
        }

        $temporada = new Temporada();
        $temporada->setNombre($_POST['nombre']);
        $temporada->setFecha_inicio($_POST['fecha_inicio']);
        $temporada->setFecha_fin($_POST['fecha_fin']);

        $ok = $temporada->guardar();
        $mensaje = $ok ? 'Temporada guardada correctamente' : 'Error al guardar temporada';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=temporadas&message=$mensaje&status=$status");
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_temporada']) || empty($_POST['nombre']) || empty($_POST['fecha_inicio']) || empty($_POST['fecha_fin'])) {
            header("Location: ../../index.php?page=temporadas&message=Datos incompletos&status=danger");
            exit;
        }

        $temporada = new Temporada();
        $temporada->setId_temporada($_POST['id_temporada']);
        $temporada->setNombre($_POST['nombre']);
        $temporada->setFecha_inicio($_POST['fecha_inicio']);
        $temporada->setFecha_fin($_POST['fecha_fin']);

        $ok = $temporada->actualizar();
        $mensaje = $ok ? 'Temporada actualizada correctamente' : 'Error al actualizar temporada';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=temporadas&message=$mensaje&status=$status");
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_temporada_eliminar'])) {
            header("Location: ../../index.php?page=temporadas&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $temporada = new Temporada();
        $temporada->setId_temporada($_POST['id_temporada_eliminar']);

        $ok = $temporada->eliminar_logico();
        $mensaje = $ok ? 'Temporada eliminada correctamente' : 'Error al eliminar temporada';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=temporadas&message=$mensaje&status=$status");
        exit;
    }
}
?>
