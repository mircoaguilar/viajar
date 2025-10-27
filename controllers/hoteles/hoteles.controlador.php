<?php
session_start();
require_once(__DIR__ . '/../../models/hotel.php');
require_once(__DIR__ . '/../../models/hotelInfo.php');

class HotelesControlador {

    // Guardar hotel y su información
    public function guardar() {
    header('Content-Type: application/json; charset=utf-8');

    if (empty($_POST['hotel_nombre']) || empty($_POST['rela_ciudad'])) {
        echo json_encode([
            'status' => 'error',
            'mensaje' => 'Faltan datos obligatorios'
        ]);
        exit;
    }

    // Imagen principal
    $imagen_principal_nombre = null;
    if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
        $directorio_destino = __DIR__ . "/../../assets/images/";
        $nombre_archivo = uniqid() . '_' . basename($_FILES['imagen_principal']['name']);
        $ruta_destino = $directorio_destino . $nombre_archivo;
        if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_destino)) {
            $imagen_principal_nombre = $nombre_archivo;
        }
    }

    $hotel = new Hotel();
    $hotel->setHotel_nombre($_POST['hotel_nombre']);
    $hotel->setRela_proveedor($_SESSION['id_proveedores']);
    $hotel->setRela_ciudad($_POST['rela_ciudad']);
    $hotel->setRela_provincia($_POST['rela_provincia'] ?? null);
    $hotel->setActivo(1);
    $hotel->setImagen_principal($imagen_principal_nombre);

    $id_hotel = $hotel->guardar();

    if ($id_hotel) {
        // Fotos adicionales
        $fotos_nombres = [];
        if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['name'])) {
            $directorio_destino = __DIR__ . "/../../assets/images/";
            foreach ($_FILES['fotos']['name'] as $key => $nombre) {
                if ($_FILES['fotos']['error'][$key] === UPLOAD_ERR_OK) {
                    $nombre_archivo = uniqid() . '_' . basename($nombre);
                    $ruta_destino = $directorio_destino . $nombre_archivo;
                    if (move_uploaded_file($_FILES['fotos']['tmp_name'][$key], $ruta_destino)) {
                        $fotos_nombres[] = $nombre_archivo;
                    }
                }
            }
        }

        // Info del hotel
        $info = new HotelInfo();
        $info->setRela_hotel($id_hotel);
        $info->setDireccion($_POST['direccion'] ?? '');
        $info->setDescripcion($_POST['descripcion'] ?? '');
        $info->setServicios($_POST['servicios'] ?? '');
        $info->setPoliticas_cancelacion($_POST['politicas_cancelacion'] ?? '');
        $info->setReglas($_POST['reglas'] ?? '');
        $info->setActivo(1);
        $info->setFotos(json_encode($fotos_nombres));
        $info->guardar();

        // ✅ Devuelve JSON para el AJAX
        echo json_encode([
            'status' => 'ok',
            'mensaje' => 'Hotel guardado correctamente',
            'id_hotel' => $id_hotel
        ]);
        exit;
    } else {
        echo json_encode([
            'status' => 'error',
            'mensaje' => 'Error al guardar el hotel'
        ]);
        exit;
    }
}


    // Actualizar hotel y su información
    public function actualizar() {
        if (empty($_POST['id_hotel']) || empty($_POST['hotel_nombre']) || empty($_POST['rela_ciudad'])) {
            $id = htmlspecialchars($_POST['id_hotel'] ?? '');
            header("Location: ../../index.php?page=hoteles_editar&id=$id&message=Datos obligatorios incompletos&status=danger");
            exit;
        }

        // Datos básicos del hotel
        $hotel = new Hotel();
        $hotel->setId_hotel($_POST['id_hotel']);
        $hotel->setHotel_nombre($_POST['hotel_nombre']);
        $hotel->setRela_ciudad($_POST['rela_ciudad']);
        $hotel->setRela_provincia($_POST['rela_provincia'] ?? null);
        $hotel->setActivo(1);

        // Imagen principal nueva (si se sube otra)
        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
            $directorio_destino = __DIR__ . "/../../assets/images/";
            $nombre_archivo = uniqid() . '_' . basename($_FILES['imagen_principal']['name']);
            $ruta_destino = $directorio_destino . $nombre_archivo;
            if (move_uploaded_file($_FILES['imagen_principal']['tmp_name'], $ruta_destino)) {
                $hotel->setImagen_principal($nombre_archivo);
            }
        }

        $hotel_actualizado = $hotel->actualizar();

        // Info
        $info = new HotelInfo();
        $info->setRela_hotel($_POST['id_hotel']);
        $info->setDireccion($_POST['direccion'] ?? '');
        $info->setDescripcion($_POST['descripcion'] ?? '');
        $info->setServicios($_POST['servicios'] ?? '');
        $info->setPoliticas_cancelacion($_POST['politicas_cancelacion'] ?? '');
        $info->setReglas($_POST['reglas'] ?? '');

        // Fotos nuevas
        $fotos_nombres = [];
        if (isset($_FILES['fotos']) && is_array($_FILES['fotos']['name'])) {
            $directorio_destino = __DIR__ . "/../../assets/images/";
            foreach ($_FILES['fotos']['name'] as $key => $nombre) {
                if ($_FILES['fotos']['error'][$key] === UPLOAD_ERR_OK) {
                    $nombre_archivo = uniqid() . '_' . basename($nombre);
                    $ruta_destino = $directorio_destino . $nombre_archivo;
                    if (move_uploaded_file($_FILES['fotos']['tmp_name'][$key], $ruta_destino)) {
                        $fotos_nombres[] = $nombre_archivo;
                    }
                }
            }
        }

        if (!empty($fotos_nombres)) {
            $info->setFotos(json_encode($fotos_nombres));
        }

        $info_actualizado = $info->actualizar();

        if ($hotel_actualizado && $info_actualizado) {
            header("Location: ../../index.php?page=proveedores_perfil&message=Hotel actualizado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=hoteles_editar&id=".htmlspecialchars($_POST['id_hotel'])."&message=Error al actualizar&status=danger");
        }
        exit;
    }

    // Eliminar hotel (borrado lógico)
    public function eliminar() {
        if (empty($_POST['id_hotel_eliminar'])) {
            header("Location: ../../index.php?page=proveedores_perfil&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        // Marcar hotel como inactivo
        $hotel = new Hotel();
        $hotel->setId_hotel($_POST['id_hotel_eliminar']);
        $hotel->setActivo(0);
        $eliminado = $hotel->actualizar();

        // Marcar también la info como inactiva
        $info = new HotelInfo();
        $info->setRela_hotel($_POST['id_hotel_eliminar']);
        $info->setActivo(0);
        $info->actualizar();

        if ($eliminado) {
            header("Location: ../../index.php?page=proveedores_perfil&message=Hotel eliminado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=proveedores_perfil&message=Error al eliminar&status=danger");
        }
        exit;
    }
}

// Enrutamiento de acciones
if (isset($_POST['action'])) {
    $controlador = new HotelesControlador();
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
            header("Location: ../../index.php?page=proveedores_perfil&message=Acción no válida&status=danger");
            exit;
    }
}
