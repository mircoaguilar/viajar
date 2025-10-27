<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once(__DIR__ . '/../../models/transporte.php');
require_once(__DIR__ . '/../../models/proveedor.php');

header('Content-Type: application/json');

class TransportesControlador {

    // Listar transportes (para vistas normales, no AJAX)
    public function listar_transportes() {
        if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 3) {
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

        $transporteModel = new Transporte();
        $transportes = $transporteModel->traer_transportes_por_usuario($id_usuario);

        require('views/paginas/transportes_mis_transportes.php');
    }

    // Guardar transporte (AJAX)
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['transporte_matricula']) || empty($_POST['transporte_capacidad']) || empty($_POST['rela_tipo_transporte'])) {
                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Faltan datos obligatorios"
                ]);
                exit;
            }

            $transporte = new Transporte(
                null,
                $_POST['transporte_matricula'],
                $_POST['transporte_capacidad'],
                $_POST['rela_tipo_transporte'],
                $_POST['nombre_servicio'] ?? '',
                $_POST['descripcion'] ?? '',
                null,
                $_SESSION['id_proveedores'] ?? null
            );

            // Imagen principal
            if (!empty($_FILES['imagen_principal']['name'])) {
                $nombreArchivo = time() . "_" . basename($_FILES['imagen_principal']['name']);
                $rutaDestino = __DIR__ . '/../../assets/images/' . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $rutaDestino)) {
                    $transporte->setImagen_principal($nombreArchivo);
                }
            }

            $id_nuevo = $transporte->guardar();

            if ($id_nuevo) {
                echo json_encode([
                    "status" => "ok",
                    "id_transporte" => $id_nuevo
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "mensaje" => "Error al guardar en la base de datos"
                ]);
            }
            exit;
        }
    }

    // Editar transporte (AJAX)
    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_transporte'] ?? null;
            if (!$id) {
                echo json_encode(["status" => "error", "mensaje" => "ID inválido"]);
                exit;
            }

            $transporte = new Transporte(
                $id,
                $_POST['transporte_matricula'],
                $_POST['transporte_capacidad'],
                $_POST['rela_tipo_transporte'],
                $_POST['nombre_servicio'] ?? '',
                $_POST['descripcion'] ?? '',
                null,
                $_SESSION['id_proveedores'] ?? null
            );

            if (!empty($_FILES['imagen_principal']['name'])) {
                $nombreArchivo = time() . "_" . basename($_FILES['imagen_principal']['name']);
                $rutaDestino = __DIR__ . '/../../assets/images/' . $nombreArchivo;
                if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $rutaDestino)) {
                    $transporte->setImagen_principal($nombreArchivo);
                }
            }

            $resultado = $transporte->actualizar();

            echo json_encode([
                "status" => $resultado ? "ok" : "error",
                "mensaje" => $resultado ? "Transporte actualizado" : "Error al actualizar"
            ]);
            exit;
        }
    }

    // Eliminar transporte (AJAX)
    public function eliminar() {
        $id = $_POST['id_transporte'] ?? null;
        if (!$id) {
            echo json_encode(["status" => "error", "mensaje" => "ID inválido"]);
            exit;
        }

        $transporte = new Transporte();
        $transporte->setId_transporte($id);
        $resultado = $transporte->eliminar_logico();

        echo json_encode([
            "status" => $resultado ? "ok" : "error",
            "mensaje" => $resultado ? "Transporte eliminado" : "Error al eliminar"
        ]);
        exit;
    }
}

// Router
if (isset($_POST['action'])) {
    $controlador = new TransportesControlador();

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
