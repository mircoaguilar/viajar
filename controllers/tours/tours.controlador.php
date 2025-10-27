<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../../models/Tour.php');
require_once(__DIR__ . '/../../models/proveedor.php');

header('Content-Type: application/json');

class ToursControlador {

    // Listar tours (para vistas normales, no AJAX)
    public function listar_tours() {
        if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'], [14, 13])) { // Guía o Encargado General
            header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
            exit;
        }

        $id_usuario = $_SESSION['id_usuarios'];

        // Aseguramos tener id_proveedores
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

    // Guardar tour (AJAX)
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        // Validar campos obligatorios
        $nombre = trim($_POST['nombre_tour'] ?? '');
        $duracion = trim($_POST['duracion_horas'] ?? '');
        $precio = floatval($_POST['precio_por_persona'] ?? 0);

        if (!$nombre || !$duracion || !$precio) {
            echo json_encode(["status"=>"error","mensaje"=>"Faltan datos obligatorios"]);
            exit;
        }

        // Normalizar tiempo a HH:MM:SS
        if (!str_contains($duracion, ':')) $duracion = '00:' . $duracion;
        if (substr_count($duracion, ':') === 1) $duracion .= ':00';

        $hora_encuentro = trim($_POST['hora_encuentro'] ?? '');
        if ($hora_encuentro) {
            if (!str_contains($hora_encuentro, ':')) $hora_encuentro = '00:' . $hora_encuentro;
            if (substr_count($hora_encuentro, ':') === 1) $hora_encuentro .= ':00';
        } else {
            $hora_encuentro = null;
        }

        // Normalizar fechas
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_inicio = $fecha_inicio ?: null;

        $fecha_fin = $_POST['fecha_fin'] ?? null;
        $fecha_fin = $fecha_fin ?: null;

        $tour = new Tour(
            null,
            $nombre,
            $_POST['descripcion'] ?? null,
            $duracion,
            $precio,
            intval($_POST['cupo_maximo'] ?? 0),
            $fecha_inicio,
            $fecha_fin,
            $hora_encuentro,
            $_POST['lugar_encuentro'] ?? null,
            null,
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

        if ($id_nuevo) {
            echo json_encode(["status"=>"ok","id_tour"=>$id_nuevo]);
        } else {
            echo json_encode(["status"=>"error","mensaje"=>"Error al guardar en la base de datos"]);
        }
        exit;
    }


    // Editar tour (AJAX)
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_tour'] ?? null;
            if (!$id) {
                echo json_encode(["status" => "error", "mensaje" => "ID inválido"]);
                exit;
            }

            $tour = new Tour(
                $id,
                $_POST['nombre_tour'],
                $_POST['descripcion'] ?? '',
                $_POST['duracion_horas'],
                $_POST['precio_por_persona'],
                $_POST['cupo_maximo'] ?? 0,
                $_POST['fecha_inicio'] ?? null,
                $_POST['fecha_fin'] ?? null,
                $_POST['hora_encuentro'] ?? null,
                $_POST['lugar_encuentro'] ?? '',
                null,
                $_SESSION['id_proveedores'] ?? null
            );

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
    }

    // Eliminar tour (AJAX)
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
