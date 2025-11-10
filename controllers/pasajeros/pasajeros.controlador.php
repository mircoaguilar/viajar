<?php
session_start();
require_once(__DIR__ . '/../../models/pasajeros.php');

class PasajeroControlador
{
    public function guardar()
    {
        if (!isset($_SESSION['id_usuarios'])) {
            echo json_encode(["status" => "error", "mensaje" => "Debe iniciar sesión para registrar un pasajero."]);
            return;
        }

        $campos = ['nombre', 'apellido', 'rela_nacionalidad', 'rela_tipo_documento', 'numero_documento', 'sexo', 'fecha_nacimiento'];
        foreach ($campos as $campo) {
            if (empty($_POST[$campo])) {
                echo json_encode(["status" => "error", "mensaje" => "Todos los campos son obligatorios."]);
                return;
            }
        }

        $pasajero = new Pasajero();
        $pasajero->setRela_usuario($_SESSION['id_usuarios']);
        $pasajero->setNombre($_POST['nombre']);
        $pasajero->setApellido($_POST['apellido']);
        $pasajero->setRela_nacionalidad($_POST['rela_nacionalidad']);
        $pasajero->setRela_tipo_documento($_POST['rela_tipo_documento']);
        $pasajero->setNumero_documento($_POST['numero_documento']);
        $pasajero->setSexo($_POST['sexo']);
        $pasajero->setFecha_nacimiento($_POST['fecha_nacimiento']);

        if ($pasajero->guardar()) {
            echo json_encode(["status" => "success", "mensaje" => "Pasajero guardado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "Error al guardar pasajero."]);
        }
    }

    public function actualizar()
    {
        if (!isset($_SESSION['id_usuarios'])) {
            echo json_encode(["status" => "error", "mensaje" => "Debe iniciar sesión para actualizar."]);
            return;
        }

        $campos = ['id_pasajeros', 'nombre', 'apellido', 'rela_nacionalidad', 'rela_tipo_documento', 'numero_documento', 'sexo', 'fecha_nacimiento'];
        foreach ($campos as $campo) {
            if (empty($_POST[$campo])) {
                echo json_encode(["status" => "error", "mensaje" => "Todos los campos son obligatorios."]);
                return;
            }
        }

        $pasajero = new Pasajero();
        $pasajero->setId_pasajeros($_POST['id_pasajeros']);
        $pasajero->setRela_usuario($_SESSION['id_usuarios']);
        $pasajero->setNombre($_POST['nombre']);
        $pasajero->setApellido($_POST['apellido']);
        $pasajero->setRela_nacionalidad($_POST['rela_nacionalidad']);
        $pasajero->setRela_tipo_documento($_POST['rela_tipo_documento']);
        $pasajero->setNumero_documento($_POST['numero_documento']);
        $pasajero->setSexo($_POST['sexo']);
        $pasajero->setFecha_nacimiento($_POST['fecha_nacimiento']);

        if ($pasajero->actualizar()) {
            echo json_encode(["status" => "success", "mensaje" => "Pasajero actualizado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "Error al actualizar pasajero."]);
        }
    }

    public function eliminar()
    {
        if (empty($_POST['id_pasajeros'])) {
            echo json_encode(["status" => "error", "mensaje" => "ID de pasajero no especificado."]);
            return;
        }

        $pasajero = new Pasajero();
        $pasajero->setId_pasajeros($_POST['id_pasajeros']);

        if ($pasajero->eliminar()) {
            echo json_encode(["status" => "success", "mensaje" => "Pasajero eliminado correctamente."]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "Error al eliminar pasajero."]);
        }
    }

    public function obtener()
    {
        if (empty($_GET['id_pasajeros'])) {
            echo json_encode(["status" => "error", "mensaje" => "ID de pasajero no especificado."]);
            return;
        }

        $pasajero = new Pasajero();
        $resultado = $pasajero->obtener_por_id($_GET['id_pasajeros']);

        if ($resultado) {
            echo json_encode(["status" => "success", "data" => $resultado]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "No se encontró el pasajero."]);
        }
    }
}

if (isset($_POST["action"])) {
    $controlador = new PasajeroControlador();

    switch ($_POST["action"]) {
        case "guardar":
            $controlador->guardar();
            break;
        case "actualizar":
            $controlador->actualizar();
            break;
        case "eliminar":
            $controlador->eliminar();
            break;
    }
}

if (isset($_GET["action"]) && $_GET["action"] === "obtener") {
    $controlador = new PasajeroControlador();
    $controlador->obtener();
}
