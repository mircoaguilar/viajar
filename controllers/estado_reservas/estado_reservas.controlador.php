<?php
require_once(__DIR__ . '/../../models/estado_reserva.php');

// Si llega una acción por POST, la procesamos
if (isset($_POST['action'])) {
    $controlador = new EstadoReservaControlador();

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
            header("Location: ../../index.php?page=estado_reserva&message=Acción no válida&status=danger");
            exit;
    }
}

class EstadoReservaControlador {

    // Guardar un nuevo estado de reserva
    public function guardar() {
        if (empty($_POST['nombre_estado'])) {
            header("Location: ../../index.php?page=estado_reserva&message=El nombre del estado es obligatorio&status=danger");
            exit;
        }

        $estado = new EstadoReserva();
        $estado->setNombre_estado($_POST['nombre_estado']);

        if ($estado->guardar()) {
            header("Location: ../../index.php?page=estado_reserva&message=Estado guardado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=estado_reserva&message=Error al guardar estado&status=danger");
        }
        exit;
    }

    // Actualizar un estado existente
    public function actualizar() {
        if (empty($_POST['id_estado_reserva']) || empty($_POST['nombre_estado'])) {
            header("Location: ../../index.php?page=estado_reserva&message=Datos incompletos&status=danger");
            exit;
        }

        $estado = new EstadoReserva();
        $estado->setId_estado_reserva($_POST['id_estado_reserva']);
        $estado->setNombre_estado($_POST['nombre_estado']);

        if ($estado->actualizar()) {
            header("Location: ../../index.php?page=estado_reserva&message=Estado actualizado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=estado_reserva&id=".$_POST['id_estado_reserva']."&message=Error al actualizar&status=danger");
        }
        exit;
    }

    // Eliminar un estado (eliminación lógica)
    public function eliminar() {
        if (empty($_POST['id_estado_eliminar'])) {
            header("Location: ../../index.php?page=estado_reserva&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $estado = new EstadoReserva();
        $estado->setId_estado_reserva($_POST['id_estado_eliminar']);

        if ($estado->eliminar_logico()) {
            header("Location: ../../index.php?page=estado_reserva&message=Estado eliminado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=estado_reserva&message=Error al eliminar estado&status=danger");
        }
        exit;
    }
}
