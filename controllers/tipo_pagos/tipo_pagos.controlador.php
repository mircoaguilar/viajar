<?php

require_once(__DIR__ . '/../../models/tipo_pagos.php');

if (isset($_POST["action"])) {
    $tipo_pago_controlador = new TipoPagoControlador();

    switch ($_POST["action"]) {
        case 'guardar':
            $tipo_pago_controlador->guardar();
            break;
        case 'actualizar':
            $tipo_pago_controlador->actualizar();
            break;
        case 'eliminar':
            $tipo_pago_controlador->eliminar();
            break;
        default:
            header("Location: ../../index.php?page=tipo_pagos&message=Acción no válida&status=danger");
            exit;
    }
}

class TipoPagoControlador {

    // Guarda un nuevo tipo de pago
    public function guardar() {
        if (empty($_POST['tipo_pago_descripcion'])) {
            header("Location: ../../index.php?page=tipo_pagos&message=La descripción del tipo de pago es obligatoria&status=danger");
            exit;
        }

        $tipo_pago = new Tipo_pago();
        $tipo_pago->setTipo_pago_descripcion($_POST['tipo_pago_descripcion']);

        $id_nuevo = $tipo_pago->guardar();
        $mensaje = $id_nuevo ? 'Tipo de pago guardado correctamente' : 'Error al guardar el tipo de pago';
        $status = $id_nuevo ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipo_pagos&message=$mensaje&status=$status");
        exit;
    }

    // Actualiza un tipo de pago existente
    public function actualizar() {
        if (empty($_POST['id_tipo_pago']) || empty($_POST['tipo_pago_descripcion'])) {
            $id_tipo_pago_redir = htmlspecialchars($_POST['id_tipo_pago'] ?? '');
            header("Location: ../../index.php?page=tipo_pagos&id=".$id_tipo_pago_redir."&message=Datos incompletos para actualizar el tipo de pago&status=danger");
            exit;
        }

        $tipo_pago = new Tipo_pago();
        $tipo_pago->setId_tipo_pago($_POST['id_tipo_pago']);
        $tipo_pago->setTipo_pago_descripcion($_POST['tipo_pago_descripcion']);

        $ok = $tipo_pago->actualizar();
        $mensaje = $ok ? 'Tipo de pago actualizado correctamente' : 'Error al actualizar el tipo de pago';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipo_pagos&id=".htmlspecialchars($_POST['id_tipo_pago'])."&message=$mensaje&status=$status");
        exit;
    }

    // Elimina un tipo de pago de forma lógica
    public function eliminar() {
        if (empty($_POST['id_tipo_pago_eliminar'])) {
            header("Location: ../../index.php?page=tipo_pagos&message=ID de tipo de pago no especificado para eliminar&status=danger");
            exit;
        }

        $tipo_pago = new Tipo_pago();
        $tipo_pago->setId_tipo_pago($_POST['id_tipo_pago_eliminar']);

        $ok = $tipo_pago->eliminar_logico();
        $mensaje = $ok ? 'Tipo de pago eliminado' : 'Error al eliminar el tipo de pago';
        $status = $ok ? 'success' : 'danger';
        header("Location: ../../index.php?page=tipo_pagos&message=$mensaje&status=$status");
        exit;
    }
}
?>
