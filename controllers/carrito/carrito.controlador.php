<?php
session_start();

require_once(__DIR__ . '/../../models/carrito.php');
require_once(__DIR__ . '/../../models/carritoitem.php');

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuarios'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debes iniciar sesión']);
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
$action = $_POST['action'] ?? $_GET['action'] ?? null;

if (!$action) {
    echo json_encode(['status' => 'error', 'message' => 'Acción no especificada']);
    exit;
}

$carritoModel = new Carrito();
$itemModel = new CarritoItem();

switch ($action) {

    case 'agregar':
        $tipo_servicio = $_POST['tipo_servicio'] ?? null;
        $id_viaje = $_POST['id_viaje'] ?? null;
        $id_servicio = $_POST['id_servicio'] ?? $id_viaje; 
        $cantidad = (int)($_POST['cantidad'] ?? 1);
        $precio_unitario = (float)($_POST['precio_unitario'] ?? 0);

        if (!$tipo_servicio || !$id_servicio || $cantidad <= 0 || $precio_unitario <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios para agregar al carrito']);
            exit;
        }

        $carrito = $carritoModel->traer_carrito_activo($id_usuario);
        if (!$carrito) {
            $carritoModel->setId_usuario($id_usuario)->setActivo(1);
            $id_carrito = $carritoModel->guardar();
        } else {
            $id_carrito = $carrito['id_carrito'];
        }

        $subtotal = $cantidad * $precio_unitario;
        $fecha_inicio = null;
        $fecha_fin = null;
        $asientos = [];

        if ($tipo_servicio === 'hotel' && !empty($_POST['checkin']) && !empty($_POST['checkout'])) {
            $fecha_inicio = $_POST['checkin'];
            $fecha_fin = $_POST['checkout'];
            $noches = (new DateTime($fecha_fin))->diff(new DateTime($fecha_inicio))->days;
            $subtotal *= max($noches, 1);
        }

        if ($tipo_servicio === 'tour' && !empty($_POST['fecha_tour'])) {
            $fecha_inicio = $_POST['fecha_tour'];
            $fecha_fin = null;
        }

        if ($tipo_servicio === 'transporte') {
            $fecha_inicio = $_POST['fecha_servicio'] ?? null;
            $fecha_fin = null;
            $asientos_json = $_POST['asientos'] ?? '[]';
            $asientos = json_decode($asientos_json, true);

            if (!is_array($asientos)) {
                $asientos = [];
                error_log("Error al decodificar asientos en agregar transporte: " . print_r($asientos_json, true));
            }

            error_log("Agregando transporte al carrito: servicio={$id_servicio}, fecha={$fecha_inicio}, asientos=" . print_r($asientos, true));
        }

        $itemModel->setId_carrito($id_carrito)
                ->setTipo_servicio($tipo_servicio)
                ->setId_servicio($id_servicio)
                ->setCantidad($cantidad)
                ->setPrecio_unitario($precio_unitario)
                ->setSubtotal($subtotal)
                ->setFecha_inicio($fecha_inicio)
                ->setFecha_fin($fecha_fin);

        $resultado = $itemModel->guardar();

        if (!$resultado) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al guardar item',
                'sql_error' => $itemModel->getError()
            ]);
            exit;
        }

        if ($tipo_servicio === 'transporte') {
            require_once(__DIR__ . '/../../models/pasajero.php');

            $pasajeros = $_POST['pasajeros'] ?? [];
            if (is_array($pasajeros) && !empty($pasajeros)) {
                $pasajeroModel = new Pasajero();

                foreach ($pasajeros as $p) {
                    $fecha_nac = null;
                    if (!empty($p['fecha_nacimiento'])) {
                        $fecha_obj = DateTime::createFromFormat('d/m/Y', $p['fecha_nacimiento']);
                        $fecha_nac = $fecha_obj ? $fecha_obj->format('Y-m-d') : null;
                    }
                    $id_pasajero_existente = $p['id_pasajeros'] ?? null;
                    $pasajeroModel->setId_pasajeros($id_pasajero_existente);

                    $pasajeroModel->setRela_usuario($id_usuario)
                                ->setNombre($p['nombre'] ?? '')
                                ->setApellido($p['apellido'] ?? '')
                                ->setRela_nacionalidad($p['rela_nacionalidad'] ?? null)
                                ->setRela_tipo_documento($p['rela_tipo_documento'] ?? null)
                                ->setNumero_documento($p['numero_documento'] ?? '')
                                ->setSexo($p['sexo'] ?? '')
                                ->setFecha_nacimiento($fecha_nac)
                                ->guardar();
                }
                error_log("Pasajeros guardados correctamente para usuario $id_usuario");
            } else {
                error_log("No se recibieron pasajeros válidos en la petición");
            }

            if (!isset($_SESSION['carrito_extra'])) {
                $_SESSION['carrito_extra'] = [];
            }
            $_SESSION['carrito_extra'][$id_servicio] = [
                'asientos' => $asientos,
                'fecha_servicio' => $fecha_inicio
            ];

            error_log("Asientos almacenados en sesión para transporte {$id_servicio}: " . print_r($asientos, true));
        }

        echo json_encode(['status' => 'success', 'message' => 'Item agregado al carrito y pasajeros guardados']);
        break;


    case 'quitar':
        $id_item = (int)($_POST['id_item'] ?? 0);
        if (!$id_item) {
            echo json_encode(['status' => 'error', 'message' => 'ID de item requerido']);
            exit;
        }
        $itemModel->eliminar($id_item);
        echo json_encode(['status' => 'success', 'message' => 'Item eliminado']);
        break;

    case 'actualizar':
        $id_item = (int)($_POST['id_item'] ?? 0);
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $precio_unitario = (float)($_POST['precio_unitario'] ?? 0);

        if (!$id_item || $cantidad <= 0 || $precio_unitario <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
            exit;
        }

        $item = $itemModel->traer_por_id($id_item);
        if (!$item) {
            echo json_encode(['status' => 'error', 'message' => 'Item no encontrado']);
            exit;
        }

        $subtotal = $cantidad * $precio_unitario;
        if ($item['tipo_servicio'] === 'hotel' && !empty($item['fecha_inicio']) && !empty($item['fecha_fin'])) {
            $fecha_inicio = new DateTime($item['fecha_inicio']);
            $fecha_fin    = new DateTime($item['fecha_fin']);
            $noches = $fecha_fin->diff($fecha_inicio)->days;
            $subtotal *= max($noches, 1);
        }

        $itemModel->setCantidad($cantidad)
                  ->setPrecio_unitario($precio_unitario)
                  ->setSubtotal($subtotal)
                  ->actualizar($id_item);

        echo json_encode(['status' => 'success', 'message' => 'Cantidad actualizada']);
        break;

    case 'listar':
        $carrito = $carritoModel->traer_carrito_activo($id_usuario);
        if (!$carrito) {
            echo json_encode(['status' => 'success', 'items' => []]);
            exit;
        }

        $id_carrito = $carrito['id_carrito'];
        $items = $carritoModel->traer_items($id_carrito);

        if (!empty($_SESSION['carrito_extra'])) {
            foreach ($items as &$it) {
                if ($it['tipo_servicio'] === 'transporte' && isset($_SESSION['carrito_extra'][$it['id_servicio']])) {
                    $it['asientos'] = $_SESSION['carrito_extra'][$it['id_servicio']]['asientos'];
                    $it['fecha_servicio'] = $_SESSION['carrito_extra'][$it['id_servicio']]['fecha_servicio'];
                }
            }
        }

        echo json_encode(['status' => 'success', 'items' => $items]);
        break;

    case 'detalle':
        $id_item = (int)($_GET['id_item'] ?? 0);

        if (!$id_item) {
            echo json_encode(['status' => 'error', 'message' => 'ID de ítem requerido']);
            exit;
        }

        $item = $itemModel->traer_por_id($id_item);

        if (!$item) {
            echo json_encode(['status' => 'error', 'message' => 'Ítem no encontrado']);
            exit;
        }

        if ($item['tipo_servicio'] === 'transporte') {
            $id_viaje = (int)$item['id_servicio']; 
            $id_item_carrito = $id_item; 
            $id_usuario_actual = (int)($item['rela_usuario'] ?? 74); 

            if ($id_viaje > 0) { 
                $db = new Conexion();
                
                $sql = "
                    SELECT 
                        t.id_transporte, t.transporte_matricula, t.transporte_capacidad, t.nombre_servicio, t.descripcion, 
                        r.nombre AS ruta_nombre, r.descripcion AS ruta_descripcion, r.duracion AS ruta_duracion, 
                        r.precio_por_persona AS ruta_precio, v.viaje_fecha, v.hora_salida, v.hora_llegada
                    FROM viajes v
                    INNER JOIN transporte_rutas r ON r.id_ruta = v.rela_transporte_rutas
                    INNER JOIN transporte t ON t.id_transporte = r.rela_transporte
                    WHERE v.id_viajes = {$id_viaje} 
                    LIMIT 1
                ";

                $extra = $db->consultar($sql);

                if ($extra && count($extra) > 0) {
                    $item['transporte_matricula'] = $extra[0]['transporte_matricula'];
                    $item['transporte_capacidad'] = $extra[0]['transporte_capacidad'];
                    $item['nombre_servicio'] = $extra[0]['nombre_servicio'];
                    $item['descripcion'] = $extra[0]['descripcion'];
                    $item['ruta_nombre'] = $extra[0]['ruta_nombre'];
                    $item['ruta_descripcion'] = $extra[0]['ruta_descripcion'];
                    $item['ruta_duracion'] = $extra[0]['ruta_duracion'];
                    $item['ruta_precio'] = $extra[0]['ruta_precio'];
                    $item['viaje_fecha'] = $extra[0]['viaje_fecha'];
                    $item['hora_salida'] = $extra[0]['hora_salida'];
                    $item['hora_llegada'] = $extra[0]['hora_llegada'];
                }

                if ($id_usuario_actual > 0) {
                    $sql_pasajeros = "
                        SELECT 
                            id_pasajeros, nombre AS pasajero_nombre, apellido AS pasajero_apellido, numero_documento AS pasajero_documento
                        FROM pasajeros
                        WHERE rela_usuario = {$id_usuario_actual}
                    ";

                    $extra_pasajeros = $db->consultar($sql_pasajeros);
                    
                    if ($extra_pasajeros && count($extra_pasajeros) > 0) {
                        $item['pasajeros'] = $extra_pasajeros;  
                    } else {
                        $item['pasajeros'] = []; 
                    }

                    $sql_asientos = "
                        SELECT 
                            piso, numero_asiento 
                        FROM transporte_asientos_bloqueados 
                        WHERE id_viaje = {$id_viaje} 
                        AND id_usuario = {$id_usuario_actual}
                    ";

                    $extra_asientos = $db->consultar($sql_asientos);

                    if ($extra_asientos && count($extra_asientos) > 0) {
                        $item['asientos'] = $extra_asientos;  
                    } else {
                        $item['asientos'] = [];  
                    }

                    if (count($item['asientos']) > 0 && count($item['pasajeros']) > 0) {
                        foreach ($item['asientos'] as $index => $asiento) {
                            if (isset($item['pasajeros'][$index])) {
                                $item['asientos'][$index]['pasajero'] = $item['pasajeros'][$index];
                            }
                        }
                    }
                } else {
                    $item['pasajeros'] = [];  
                    $item['asientos'] = [];   
                }
            }
        }

            if ($item['tipo_servicio'] === 'hotel') {
                $id_habitacion = (int)$item['id_servicio']; 

                $sql = "
                    SELECT 
                        h.id_hotel,
                        h.hotel_nombre,
                        info.direccion,
                        hab.id_hotel_habitacion,
                        hab.descripcion AS habitacion_descripcion,
                        hab.capacidad_maxima,
                        hab.precio_base_noche,
                        tipo.nombre AS tipo_habitacion
                    FROM hotel_habitaciones hab
                    INNER JOIN hotel h ON h.id_hotel = hab.rela_hotel
                    INNER JOIN hoteles_info info ON info.rela_hotel = h.id_hotel
                    INNER JOIN tipos_habitacion tipo ON tipo.id_tipo_habitacion = hab.rela_tipo_habitacion
                    WHERE hab.id_hotel_habitacion = {$id_habitacion}
                    LIMIT 1
                ";

                $db = new Conexion();
                $extra = $db->consultar($sql);

                if ($extra && count($extra) > 0) {
                    $item['hotel_nombre']          = $extra[0]['hotel_nombre'];
                    $item['direccion']             = $extra[0]['direccion'];
                    $item['tipo_habitacion']       = $extra[0]['tipo_habitacion'];
                    $item['habitacion_descripcion']= $extra[0]['habitacion_descripcion'];
                    $item['capacidad_maxima']      = $extra[0]['capacidad_maxima'];
                }
            }

            if ($item['tipo_servicio'] === 'tour') {
                $id_tour = (int)$item['id_servicio'];     
                $fecha_tour = $item['fecha_inicio'];      

                $sql = "
                    SELECT 
                        t.id_tour,
                        t.nombre_tour,
                        t.descripcion,
                        t.duracion_horas,
                        t.precio_por_persona,
                        t.hora_encuentro,
                        t.lugar_encuentro,
                        t.imagen_principal
                    FROM tours t
                    WHERE t.id_tour = {$id_tour}
                    LIMIT 1
                ";

                $db = new Conexion();
                $extra = $db->consultar($sql);

                if ($extra && count($extra) > 0) {
                    $item['id_tour']            = $id_tour;
                    $item['nombre_tour']        = $extra[0]['nombre_tour'];
                    $item['descripcion']        = $extra[0]['descripcion'];
                    $item['duracion_horas']     = $extra[0]['duracion_horas'];
                    $item['precio_por_persona'] = $extra[0]['precio_por_persona'];
                    $item['hora_encuentro']     = $extra[0]['hora_encuentro'];
                    $item['lugar_encuentro']    = $extra[0]['lugar_encuentro'];
                    $item['imagen_principal']   = $extra[0]['imagen_principal'];
                    $item['fecha_tour']         = $fecha_tour;
                }
            }

            echo json_encode(['status' => 'success', 'item' => $item]);
            break;

    case 'crear_reserva':
        require_once(__DIR__ . '/../../models/reserva.php');
        require_once(__DIR__ . '/../../models/auditoria.php');
        require_once(__DIR__ . '/../../vendor/autoload.php'); 

        $carrito = $carritoModel->traer_carrito_activo($id_usuario);
        if (!$carrito) {
            echo json_encode(['status'=>'error','message'=>'No hay carrito activo']);
            exit;
        }

        $id_carrito = $carrito['id_carrito'];
        $items = $carritoModel->traer_items($id_carrito);
        $extras = $_SESSION['carrito_extra'] ?? [];

        if (empty($items)) {
            echo json_encode(['status'=>'error','message'=>'El carrito está vacío']);
            exit;
        }

        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $mysqli->begin_transaction();
        try {
            $reservaModel = new Reserva();
            $total = 0;
            foreach ($items as $it) {
                $total += $it['subtotal'];
            }

            $id_reserva = $reservaModel->crear_reserva($id_usuario, $total, 'pendiente');
            if (!$id_reserva) throw new Exception("Error al crear reserva principal");

            foreach ($items as $it) {
                $id_detalle = $reservaModel->crear_detalle(
                    $id_reserva,
                    $it['tipo_servicio'],
                    $it['cantidad'],
                    $it['precio_unitario'],
                    $it['subtotal']
                );

                if (!$id_detalle) {
                    throw new Exception("Error al crear detalle para servicio {$it['tipo_servicio']} (servicio={$it['id_servicio']})");
                }

                if ($it['tipo_servicio'] === 'hotel') {
                    $noches = 1;
                    try {
                        $fecha_inicio_dt = new DateTime($it['fecha_inicio']);
                        $fecha_fin_dt = new DateTime($it['fecha_fin']);
                        $noches = max(1, $fecha_fin_dt->diff($fecha_inicio_dt)->days);
                    } catch (Exception $ex) {
                        error_log("WARN crear_reserva: fechas hotel inválidas para detalle {$id_detalle}: " . $ex->getMessage());
                    }

                    $reservaModel->crear_detalle_hotel(
                        $id_detalle,
                        $it['id_servicio'],
                        $it['fecha_inicio'],
                        $it['fecha_fin'],
                        $noches
                    );
                } elseif ($it['tipo_servicio'] === 'tour') {
                    $reservaModel->crear_detalle_tour(
                        $id_detalle,
                        $it['id_servicio'],
                        $it['fecha_tour'] ?? null
                    );
                } elseif ($it['tipo_servicio'] === 'transporte') {

                    $extrasTransporte = $extras[$it['id_servicio']] ?? null;

                    if (!isset($extrasTransporte['asientos']) || !is_array($extrasTransporte['asientos']) || empty($extrasTransporte['asientos'])) {
                    } else {

                        require_once(__DIR__ . '/../../models/pasajero.php');
                        $pasajeroModel = new Pasajero();

                        $asientos_seleccionados = $extrasTransporte['asientos'];
                        $fecha_servicio = $extrasTransporte['fecha_servicio'] ?? null;

                        $pasajeros_disponibles = $pasajeroModel->obtener_por_usuario($id_usuario);

                        if (count($pasajeros_disponibles) < count($asientos_seleccionados)) {
                            throw new Exception("Faltan datos de pasajeros para los asientos seleccionados.");
                        }

                        foreach ($asientos_seleccionados as $index => $asiento_info) {

                            $id_pasajero_asignado = $pasajeros_disponibles[$index]['id_pasajeros'] ?? null;
                            if (!$id_pasajero_asignado) {
                                throw new Exception("Error al obtener ID de pasajero.");
                            }

                            $piso_asiento = $asiento_info['piso'] ?? null;
                            $numero_asiento_seguro = $asiento_info['numero'] ?? null;

                            if (empty($numero_asiento_seguro)) {
                                throw new Exception("Falta el número de asiento.");
                            }
                            $id_detalle_asiento = $reservaModel->crear_detalle_transporte_asiento(
                                $id_detalle,
                                $it['id_servicio'],
                                $piso_asiento,
                                $numero_asiento_seguro,
                                $fecha_servicio,
                                $it['precio_unitario'],
                                $id_pasajero_asignado
                            );

                            if (!$id_detalle_asiento) {
                                throw new Exception("Error al crear el detalle del asiento {$numero_asiento_seguro}.");
                            }

                            $reservaModel->bloquear_asiento_temporal(
                                $it['id_servicio'],
                                $id_usuario,
                                $piso_asiento,
                                $numero_asiento_seguro
                            );
                        }
                    }
                }
            } 
            $auditoria = new Auditoria(
                '',
                $id_usuario,
                'Reserva creada',
                "El usuario ID {$id_usuario} creó la reserva #{$id_reserva} por \${$total}"
            );

            if (method_exists($auditoria, 'guardar')) {
                $res_aud = $auditoria->guardar();
            $_SESSION['id_reserva'] = $id_reserva;
            $mysqli->commit();
            }

            echo json_encode(['status'=>'success','id_reserva'=>$id_reserva,'message'=>'Reserva creada']);
        } catch (Exception $e) {
            $mysqli->rollback();
            error_log("ERROR crear_reserva: Exception -> " . $e->getMessage());
            echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
        }
        break;



    default:
        echo json_encode(['status' => 'error', 'message' => 'Acción no reconocida']);
        break;

}
?>