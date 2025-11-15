<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once(__DIR__ . '/../../models/hotel.php');
require_once(__DIR__ . '/../../models/hotelInfo.php');
require_once(__DIR__ . '/../../models/provincia.php'); 


class HotelesControlador {

    public function obtenerCiudadesPorProvincia($id_provincia) {
        $provinciaModel = new Provincia(); 
        return $provinciaModel->traer_ciudades_por_provincia($id_provincia);
    }

    public function obtenerDatosHotel($id_hotel) {
        $hotel = new Hotel();
        $hotelInfo = new HotelInfo();
        $provinciaModel = new Provincia();

        $hotelData = $hotel->traer_hotel($id_hotel);
        $hotelInfoData = $hotelInfo->traer_por_hotel($id_hotel);
        $provincias = $provinciaModel->traer_provincias();

        return [
            'hotelData' => $hotelData[0] ?? null,
            'hotelInfoData' => $hotelInfoData[0] ?? null,
            'provincias' => $provincias
        ];
    }

    public function guardar() {
        header('Content-Type: application/json; charset=utf-8');

        if (empty($_POST['hotel_nombre']) || empty($_POST['rela_ciudad'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Faltan datos obligatorios'
            ]);
            exit;
        }

        $imagen_principal_nombre = $this->subirImagen('imagen_principal');

        $hotel = new Hotel();
        $hotel->setHotel_nombre($_POST['hotel_nombre']);
        $hotel->setRela_proveedor($_SESSION['id_proveedores']);
        $hotel->setRela_ciudad($_POST['rela_ciudad']);
        $hotel->setRela_provincia($_POST['rela_provincia'] ?? null);
        $hotel->setActivo(0); 
        $hotel->setImagen_principal($imagen_principal_nombre);

        $id_hotel = $hotel->guardar();

        if (!$id_hotel) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al guardar el hotel'
            ]);
            exit;
        }

        $fotos_nombres = $this->subirFotos('fotos');

        $info = new HotelInfo();
        $info->setRela_hotel($id_hotel);
        $info->setDireccion($_POST['direccion'] ?? '');
        $info->setDescripcion($_POST['descripcion'] ?? '');
        $info->setServicios($_POST['servicios'] ?? '');
        $info->setPoliticas_cancelacion($_POST['politicas_cancelacion'] ?? '');
        $info->setReglas($_POST['reglas'] ?? '');
        $info->setFotos(!empty($fotos_nombres) ? json_encode($fotos_nombres) : null);
        $info->guardar();

        echo json_encode([
            'status' => 'success',
            'message' => 'Tu hotel fue enviado para revisión. Te notificaremos cuando sea aprobado.',
            'id_hotel' => $id_hotel
        ]);
        exit;
    }

     public function actualizar() {
        if (empty($_POST['id_hotel']) || empty($_POST['hotel_nombre']) || empty($_POST['rela_ciudad'])) {
            $id = htmlspecialchars($_POST['id_hotel'] ?? '');
            header("Location: ../../index.php?page=hoteles_editar&id=$id&message=Datos obligatorios incompletos&status=danger");
            exit;
        }

        $hotel = new Hotel();
        $hotel->setId_hotel($_POST['id_hotel']);
        $hotel->setHotel_nombre($_POST['hotel_nombre']);
        $hotel->setRela_ciudad($_POST['rela_ciudad']);
        $hotel->setRela_provincia($_POST['rela_provincia'] ?? null);
        $hotel->setActivo(1);

        if (isset($_FILES['imagen_principal']) && $_FILES['imagen_principal']['error'] === UPLOAD_ERR_OK) {
            $hotel->setImagen_principal($this->subirImagen('imagen_principal'));
        }

        $hotel_actualizado = $hotel->actualizar();

        $info = new HotelInfo();
        $info->setRela_hotel($_POST['id_hotel']);
        $info->setDireccion($_POST['direccion'] ?? '');
        $info->setDescripcion($_POST['descripcion'] ?? '');
        $info->setServicios($_POST['servicios'] ?? '');
        $info->setPoliticas_cancelacion($_POST['politicas_cancelacion'] ?? '');
        $info->setReglas($_POST['reglas'] ?? '');

        $hotelInfoData = $info->traer_por_hotel($_POST['id_hotel']);
        $fotos = [];
        if (!empty($hotelInfoData[0]['fotos'])) {
            $fotos = json_decode($hotelInfoData[0]['fotos'], true);
            if (!is_array($fotos)) {
                $fotos = [];
            }
        }

        $fotos_nombres = $this->subirFotos('fotos');
        if (!empty($fotos_nombres)) {
            $fotos = array_merge($fotos, $fotos_nombres);
        }

        if (!empty($_POST['borrar_fotos'])) {
            $fotos_a_borrar = $_POST['borrar_fotos'];
            foreach ($fotos_a_borrar as $foto) {
                $this->eliminarFoto($foto);
            }
            $fotos = array_diff($fotos, $fotos_a_borrar);
        }

        $info->setFotos(!empty($fotos) ? json_encode($fotos) : null);

        $info_actualizado = $info->actualizar();

        if ($hotel_actualizado && $info_actualizado) {
            header("Location: /viajar/index.php?page=hoteles_mis_hoteles&message=Hotel actualizado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=hoteles_editar&id=" . htmlspecialchars($_POST['id_hotel']) . "&message=Error al actualizar&status=danger");
        }
        exit;
    }

    private function eliminarFoto($foto) {
        $ruta = __DIR__ . "/../../assets/images/" . $foto;
        if (file_exists($ruta)) {
            unlink($ruta); 
        }
    }

    public function eliminar() {
        if (empty($_POST['id_hotel_eliminar'])) {
            header("Location: ../../index.php?page=proveedores_perfil&message=ID no especificado para eliminar&status=danger");
            exit;
        }

        $hotel = new Hotel();
        $hotel->setId_hotel($_POST['id_hotel_eliminar']);
        $hotel->setActivo(0);
        $eliminado = $hotel->actualizar();

        $info = new HotelInfo();
        $info->setRela_hotel($_POST['id_hotel_eliminar']);
        $info->actualizar();

        if ($eliminado) {
            header("Location: ../../index.php?page=proveedores_perfil&message=Hotel eliminado correctamente&status=success");
        } else {
            header("Location: ../../index.php?page=proveedores_perfil&message=Error al eliminar&status=danger");
        }
        exit;
    }

    private function subirImagen($campo) {
        $imagen_nombre = null;
        if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
            $directorio_destino = __DIR__ . "/../../assets/images/";
            $nombre_archivo = uniqid() . '_' . basename($_FILES[$campo]['name']);
            $ruta_destino = $directorio_destino . $nombre_archivo;
            if (move_uploaded_file($_FILES[$campo]['tmp_name'], $ruta_destino)) {
                $imagen_nombre = $nombre_archivo;
            }
        }
        return $imagen_nombre;
    }

    private function subirFotos($campo) {
        $fotos_nombres = [];
        if (isset($_FILES[$campo]) && is_array($_FILES[$campo]['name'])) {
            $directorio_destino = __DIR__ . "/../../assets/images/";
            foreach ($_FILES[$campo]['name'] as $key => $nombre) {
                if ($_FILES[$campo]['error'][$key] === UPLOAD_ERR_OK) {
                    $nombre_archivo = uniqid() . '_' . basename($nombre);
                    $ruta_destino = $directorio_destino . $nombre_archivo;
                    if (move_uploaded_file($_FILES[$campo]['tmp_name'][$key], $ruta_destino)) {
                        $fotos_nombres[] = $nombre_archivo;
                    }
                }
            }
        }
        return $fotos_nombres;
    }
}

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
