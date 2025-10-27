<?php
require_once(__DIR__ . '/../../models/moneda.php');

// Enrutamiento de acciones
if (isset($_POST['action'])) {
    $controlador = new MonedaControlador();
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
            header("Location: ../../index.php?page=monedas&message=Acción no válida&status=danger");
            exit;
    }
}

class MonedaControlador {

    // Guarda una nueva moneda
    public function guardar() {
        if (empty($_POST['nombre']) || empty($_POST['simbolo'])) {
            header("Location: ../../index.php?page=monedas&message=Todos los campos son obligatorios&status=danger");
            exit;
        }

        $moneda = new Moneda();
        $moneda->setNombre($_POST['nombre']);
        $moneda->setSimbolo($_POST['simbolo']);

        if ($moneda->guardar()) {
            header("Location: ../../index.php?page=monedas&message=Moneda guardada correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=monedas&message=Error al guardar moneda&status=danger");
        }
        exit;
    }

    // Actualiza una moneda existente
    public function actualizar() {
        if (empty($_POST['id_moneda']) || empty($_POST['nombre']) || empty($_POST['simbolo'])) {
            header("Location: ../../index.php?page=monedas&message=Datos incompletos&status=danger");
            exit;
        }

        $moneda = new Moneda();
        $moneda->setId_moneda($_POST['id_moneda']);
        $moneda->setNombre($_POST['nombre']);
        $moneda->setSimbolo($_POST['simbolo']);

        if ($moneda->actualizar()) {
            header("Location: ../../index.php?page=monedas&message=Moneda actualizada correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=monedas&id=" . $_POST['id_moneda'] . "&message=Error al actualizar&status=danger");
        }
        exit;
    }

    // Elimina una moneda (lógico)
    public function eliminar() {
        if (empty($_POST['id_moneda_eliminar'])) {
            header("Location: ../../index.php?page=monedas&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $moneda = new Moneda();
        $moneda->setId_moneda($_POST['id_moneda_eliminar']);

        if ($moneda->eliminar_logico()) {
            header("Location: ../../index.php?page=monedas&message=Moneda eliminada correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=monedas&message=Error al eliminar moneda&status=danger");
        }
        exit;
    }
}
