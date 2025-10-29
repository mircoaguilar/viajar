<?php
session_start();
require_once(__DIR__ . '/../../models/Tour.php');
require_once(__DIR__ . '/../../models/proveedor.php');

header('Content-Type: application/json');

class ToursControlador {

    // Listar tours del proveedor logueado
    public function listar_tours() {
        if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'], [13, 14])) {
            header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
            exit;
        }

        $id_usuario = $_SESSION['id_usuarios'];

        if (!isset($_SESSION['id_proveedores'])) {
            $proveedorModel = new Proveedor();
            $proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
            if ($proveedor) {
                $_SESSION['id_proveedores'] = $proveedor['id_proveedores'];
            } else {
                header('Location: index.php?page=proveedores_perfil&message=No se encontró proveedor asociado&status=danger');
                exit;
            }
        }

        $tourModel = new Tour();
        $tours = $tourModel->traer_tours_por_usuario($id_usuario);

        require('views/paginas/tours_mis_tours.php');
    }

    // Guardar tour 
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $nombre = trim($_POST['nombre_tour'] ?? '');
        $duracion = trim($_POST['duracion_horas'] ?? '');
        $precio = floatval($_POST['precio_por_persona'] ?? 0);

        if (!$nombre || !$duracion || !$precio) {
            echo json_encode(["status"=>"error","mensaje"=>"Faltan datos obligatorios"]);
            exit;
        }

        if (!str_contains($duracion, ':')) $duracion = '00:' . $duracion;
        if (substr_count($duracion, ':') === 1) $duracion .= ':00';

        $hora_encuentro = trim($_POST['hora_encuentro'] ?? '');
        if ($hora_encuentro) {
            if (!str_contains($hora_encuentro, ':')) $hora_encuentro = '00:' . $hora_encuentro;
            if (substr_count($hora_encuentro, ':') === 1) $hora_encuentro .= ':00';
        } else {
            $hora_encuentro = null;
        }

        $direccion = trim($_POST['direccion'] ?? ''); 

        $tour = new Tour(
            null,
            $nombre,
            $_POST['descripcion'] ?? '',
            $duracion,
            $precio,
            $hora_encuentro,
            $_POST['lugar_encuentro'] ?? '',
            $direccion, 
            $_SESSION['id_proveedores'] ?? null
        );

        // Imagen principal
        if (!empty($_FILES['imagen_principal']['name'])) {
            $nombreArchivo = time() . "_" . basename($_FILES['imagen_principal']['name']);
            $rutaDestino = __DIR__ . '/../../assets/images/' . $nombreArchivo;
            if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $rutaDestino)) {
                $tour->setImagen_principal($nombreArchivo);
            }
        }

        $id_nuevo = $tour->guardar();

        echo json_encode($id_nuevo
            ? ["status" => "ok", "id_tour" => $id_nuevo]
            : ["status" => "error", "mensaje" => "Error al guardar en la base de datos"]
        );
        exit;
    }

    // Editar tour 
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = $_POST['id_tour'] ?? null;
        if (!$id) {
            echo json_encode(["status" => "error", "mensaje" => "ID inválido"]);
            exit;
        }

        $direccion = trim($_POST['direccion'] ?? ''); 

        $tour = new Tour(
            $id,
            $_POST['nombre_tour'] ?? '',
            $_POST['descripcion'] ?? '',
            $_POST['duracion_horas'] ?? '',
            floatval($_POST['precio_por_persona'] ?? 0),
            $_POST['hora_encuentro'] ?? '',
            $_POST['lugar_encuentro'] ?? '',
            $direccion, 
            $_SESSION['id_proveedores'] ?? null
        );

        // Imagen principal
        if (!empty($_FILES['imagen_principal']['name'])) {
            $nombreArchivo = time() . "_" . basename($_FILES['imagen_principal']['name']);
            $rutaDestino = __DIR__ . '/../../assets/images/' . $nombreArchivo;
            if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $rutaDestino)) {
                $tour->setImagen_principal($nombreArchivo);
            }
        }

        $resultado = $tour->actualizar();

        echo json_encode([
            "status" => $resultado ? "ok" : "error",
            "mensaje" => $resultado ? "Tour actualizado" : "Error al actualizar"
        ]);
        exit;
    }


    // Eliminar tour 
    public function eliminar() {
        $id = $_POST['id_tour'] ?? null;
        if (!$id) {
            echo json_encode(["status" => "error", "mensaje" => "ID inválido"]);
            exit;
        }

        $tour = new Tour();
        $tour->setId_tour($id);
        $resultado = $tour->eliminar_logico();

        echo json_encode([
            "status" => $resultado ? "ok" : "error",
            "mensaje" => $resultado ? "Tour eliminado" : "Error al eliminar"
        ]);
        exit;
    }
}

// Router
if (isset($_POST['action'])) {
    $controlador = new ToursControlador();

    switch ($_POST['action']) {
        case 'guardar':
            $controlador->guardar();
            break;
        case 'editar':
            $controlador->editar();
            break;
        case 'eliminar':
            $controlador->eliminar();
            break;
        default:
            echo json_encode(["status" => "error", "mensaje" => "Acción no válida"]);
    }
}
