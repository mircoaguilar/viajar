<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once(__DIR__ . '/../../models/transporte.php');
require_once(__DIR__ . '/../../models/proveedor.php');
require_once(__DIR__ . '/../../models/transporte_piso.php');
require_once(__DIR__ . '/../../models/tipo_transporte.php');

class TransportesControlador {

    public function listar_transportes() {
        if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 3) {
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

        $transporteModel = new Transporte();
        $transportes = $transporteModel->traer_transportes_por_usuario($id_usuario);

        require('views/paginas/transportes_mis_transportes.php');
    }

    public function obtenerDatosTransporte($id_transporte) {
        $transporteModel = new Transporte();
        $transporteData = $transporteModel->traer_transporte($id_transporte);

        if (!$transporteData) {
            return null;
        }

        $transporteData = $transporteData[0] ?? null;

        if (!$transporteData) {
            return null;
        }

        $pisoModel = new TransportePiso();
        $pisosData = $pisoModel->traer_pisos_por_transporte($id_transporte);

        $tipoModel = new TipoTransporte();
        $tipos = $tipoModel->traer_tipos_transportes();

        return [
            'transporteData' => $transporteData,
            'pisosData' => $pisosData,
            'tipos' => $tipos
        ];
    }

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

            if (!empty($_FILES['imagen_principal']['name'])) {
                $nombreArchivo = time() . "_" . basename($_FILES['imagen_principal']['name']);
                $rutaDestino = __DIR__ . '/../../assets/images/' . $nombreArchivo;

                if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $rutaDestino)) {
                    $transporte->setImagen_principal($nombreArchivo);
                }
            }

            $id_nuevo = $transporte->guardar();

            if ($id_nuevo) {
                if (!empty($_POST['pisos']) && is_array($_POST['pisos'])) {
                    $pisoModel = new TransportePiso();

                    foreach ($_POST['pisos'] as $numero => $pisoData) {
                        $filas = (int)($pisoData['filas'] ?? 0);
                        $asientos = (int)($pisoData['asientos_por_fila'] ?? 0);
                        $pisoModel->guardar($id_nuevo, $numero, $filas, $asientos);
                    }
                }

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

    public function editar() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $id = $_POST['id_transporte'] ?? null;
        if (!$id) {
            header("Location: index.php?page=mis_transportes&status=error&msg=id_invalido");
            exit;
        }

        $transporteModel = new Transporte();
        $dataActual = $transporteModel->traer_transporte($id);

        if (!$dataActual) {
            header("Location: index.php?page=mis_transportes&status=error&msg=no_encontrado");
            exit;
        }

        $dataActual = $dataActual[0];
        $capacidad = $dataActual['transporte_capacidad'];

        $transporte = new Transporte(
            $id,
            $_POST['transporte_matricula'],
            $capacidad,
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

        if ($resultado) {
            header("Location: index.php?page=transportes_mis_transportes&status=ok&msg=actualizado");
        } else {
            header("Location: index.php?page=transportes_mis_transportes&status=error&msg=falla_al_actualizar");
        }
        exit;
    }



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
