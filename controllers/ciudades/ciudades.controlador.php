<?php
require_once(__DIR__ . '/../../models/ciudad.php');

if (isset($_POST["action"])) {
    $controlador = new CiudadesControlador();

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
            header("Location: ../../index.php?page=ciudades&message=Acción no válida&status=danger");
            break;
    }
}

class CiudadesControlador {

    public function guardar() {
        if (empty($_POST['nombre']) || empty($_POST['rela_provincia'])) {
            header("Location: ../../index.php?page=ciudades&message=Datos obligatorios incompletos&status=danger");
            exit;
        }

        $ciudad = new Ciudad();
        $ciudad->setNombre($_POST['nombre']);
        $ciudad->setRela_provincia($_POST['rela_provincia']);

        $id_nuevo = $ciudad->guardar();

        if ($id_nuevo) {
            header("Location: ../../index.php?page=ciudades&message=Ciudad guardada correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=ciudades&message=Error al guardar&status=danger");
        }
        exit;
    }

    public function actualizar() {
        if (empty($_POST['id_ciudad']) || empty($_POST['nombre']) || empty($_POST['rela_provincia'])) {
            $id = htmlspecialchars($_POST['id_ciudad'] ?? '');
            header("Location: ../../index.php?page=ciudades&id=$id&message=Datos obligatorios incompletos&status=danger");
            exit;
        }

        $ciudad = new Ciudad();
        $ciudad->setId_ciudad($_POST['id_ciudad']);
        $ciudad->setNombre($_POST['nombre']);
        $ciudad->setRela_provincia($_POST['rela_provincia']);

        if ($ciudad->actualizar()) {
            header("Location: ../../index.php?page=ciudades&message=Ciudad actualizada&status=success");
        } else {
            header("Location: ../../index.php?page=ciudades&id=".htmlspecialchars($_POST['id_ciudad'])."&message=Error al actualizar&status=danger");
        }
        exit;
    }

    public function eliminar() {
        if (empty($_POST['id_ciudad_eliminar'])) {
            header("Location: ../../index.php?page=ciudades&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $ciudad = new Ciudad();
        $ciudad->setId_ciudad($_POST['id_ciudad_eliminar']);

        if ($ciudad->eliminar_logico()) {
            header("Location: ../../index.php?page=ciudades&message=Ciudad eliminada&status=success");
        } else {
            header("Location: ../../index.php?page=ciudades&message=Error al eliminar&status=danger");
        }
        exit;
    }
}
?>
