<?php
require_once('conexion.php');

class Reserva {
    private $id_reservas;
    private $rela_usuarios;
    private $total;
    private $reservas_estado;
    private $fecha_creacion;
    private $activo;

    public function __construct(
        $id_reservas = '',
        $rela_usuarios = '',
        $total = 0,
        $reservas_estado = 'pendiente'
    ) {
        $this->id_reservas = $id_reservas;
        $this->rela_usuarios = $rela_usuarios;
        $this->total = $total;
        $this->reservas_estado = $reservas_estado;
        $this->activo = 1;
    }

    public function crear_reserva($id_usuario, $total, $estado) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            INSERT INTO reservas (fecha_creacion, total, reservas_estado, rela_usuarios) 
            VALUES (NOW(), ?, ?, ?)
        ");
        $stmt->bind_param("dsi", $total, $estado, $id_usuario);
        $stmt->execute();
        $id_reserva = $stmt->insert_id;
        $stmt->close();

        return $id_reserva;
    }

    public function crear_detalle($id_reserva, $tipo_servicio, $cantidad, $precio_unitario, $subtotal) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            INSERT INTO detalle_reservas 
            (rela_reservas, tipo_servicio, cantidad, precio_unitario, subtotal) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isidd", $id_reserva, $tipo_servicio, $cantidad, $precio_unitario, $subtotal);
        $stmt->execute();
        $id_detalle = $stmt->insert_id; 
        $stmt->close();

        return $id_detalle;
    }

    public function crear_detalle_hotel($id_detalle_reserva, $id_habitacion, $check_in, $check_out, $noches) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            INSERT INTO detalle_reserva_hotel (
                rela_detalle_reserva, rela_habitacion, check_in, check_out, noches, estado
            ) VALUES (?, ?, ?, ?, ?, 'pendiente')
        ");

        $stmt->bind_param("iissi",
            $id_detalle_reserva,
            $id_habitacion,
            $check_in,
            $check_out,
            $noches
        );

        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();

        return $id;
    }

    public function crear_detalle_tour($id_detalle_reserva, $id_tour, $fecha_tour) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            INSERT INTO detalle_reserva_tour (
                rela_detalle_reserva, rela_tour, fecha, estado
            ) VALUES (?, ?, ?, 'pendiente')
        ");

        $stmt->bind_param("iis",
            $id_detalle_reserva,
            $id_tour,
            $fecha_tour
        );

        $stmt->execute();
        $id_detalle_tour = $stmt->insert_id;
        $stmt->close();

        return $id_detalle_tour;
    }

    public function crear_detalle_transporte($id_detalle_reserva, $id_viaje, $asientosArray, $fecha_servicio, $precio_unitario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        if (empty($asientosArray) || !is_array($asientosArray)) {
            error_log("crear_detalle_transporte: No se recibieron asientos o no es un array válido.");
            return false;
        }

        $stmt = $mysqli->prepare("
            INSERT INTO detalle_reserva_transporte 
            (rela_detalle_reserva, id_viaje, piso, numero_asiento, fila, columna, fecha_servicio, precio_unitario, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')
        ");

        if (!$stmt) {
            error_log("Error preparando statement: " . $mysqli->error);
            return false;
        }

        foreach ($asientosArray as $a) {

            $piso    = isset($a['piso']) ? (int)$a['piso'] : null;
            $numero  = isset($a['numero']) ? (int)$a['numero'] : null;
            $fila    = isset($a['fila']) ? (int)$a['fila'] : null;
            $columna = isset($a['columna']) ? (int)$a['columna'] : null;

            if ($piso === null || $numero === null) {
                error_log("crear_detalle_transporte: asiento incompleto: " . print_r($a, true));
                continue;
            }

            $stmt->bind_param(
                "iiiiissd",
                $id_detalle_reserva,
                $id_viaje,
                $piso,
                $numero,
                $fila,
                $columna,
                $fecha_servicio,
                $precio_unitario
            );

            $ok = $stmt->execute();
            if (!$ok) {
                error_log("Error al insertar asiento en detalle_reserva_transporte: " . $stmt->error);
            }
        }

        $stmt->close();
        return true;
    }

    public function traerPorUsuario($userId) {
        $conexion = new Conexion();
        $userId = (int)$userId;
        $query = "SELECT * FROM reservas WHERE rela_usuarios = $userId AND activo = 1 ORDER BY fecha_creacion DESC";
        return $conexion->consultar($query);
    }

    public function traerDetallesPorId($id_reserva) {
        $conexion = new Conexion();
        $id_reserva = (int)$id_reserva;
        $query = "SELECT * FROM detalle_reservas WHERE rela_reservas = $id_reserva";
        return $conexion->consultar($query);
    }

    public function traerDetalleHotel($id_detalle_reserva) {
        $conexion = new Conexion();
        $id_detalle_reserva = (int)$id_detalle_reserva;
        $query = "SELECT * FROM detalle_reserva_hotel WHERE rela_detalle_reserva = $id_detalle_reserva LIMIT 1";
        $resultado = $conexion->consultar($query);
        return !empty($resultado) ? $resultado[0] : [];
    }

    public function traer_detalle_hotel_por_id($id_detalle_hotel) {
        $conexion = new Conexion();
        $id_detalle_hotel = (int)$id_detalle_hotel;

        $query = "
            SELECT drh.*, hh.rela_hotel
            FROM detalle_reserva_hotel drh
            INNER JOIN hotel_habitaciones hh
                ON hh.id_hotel_habitacion = drh.rela_habitacion
            WHERE drh.id_detalle_hotel = $id_detalle_hotel
            LIMIT 1
        ";

        $resultado = $conexion->consultar($query);

        return !empty($resultado) ? $resultado[0] : [];
    }

    public function traerDetalleTransporte($id_detalle_reserva) {
        $conexion = new Conexion();
        $id_detalle_reserva = (int)$id_detalle_reserva;

        $query = "
            SELECT 
                drt.*, 
                v.origen, 
                v.destino, 
                v.hora_salida, 
                v.hora_llegada
            FROM detalle_reserva_transporte drt
            INNER JOIN viaje v ON v.id_viaje = drt.id_viaje
            WHERE drt.rela_detalle_reserva = $id_detalle_reserva
        ";

        return $conexion->consultar($query);
    }

    public function traer_por_hotel($id_hotel) {
        $conexion = new Conexion();
        $id_hotel = (int)$id_hotel;

        $query = "
            SELECT 
                r.id_reservas,
                r.rela_usuarios,
                r.total,
                r.reservas_estado,
                r.fecha_creacion,

                dr.id_detalle_reserva,
                dr.tipo_servicio,
                dr.subtotal AS importe_total,

                drh.id_detalle_hotel,             
                drh.check_in AS fecha_inicio,
                drh.check_out AS fecha_fin,
                drh.estado AS detalle_hotel_estado,  

                th.nombre AS habitacion_nombre,

                p.personas_nombre AS cliente_nombre,
                p.personas_apellido AS cliente_apellido,
                CONCAT(p.personas_nombre, ' ', p.personas_apellido) AS cliente

            FROM reservas r
            INNER JOIN detalle_reservas dr 
                ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_hotel drh 
                ON drh.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN hotel_habitaciones hh 
                ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN tipos_habitacion th 
                ON th.id_tipo_habitacion = hh.rela_tipo_habitacion

            INNER JOIN usuarios u 
                ON u.id_usuarios = r.rela_usuarios
            INNER JOIN personas p 
                ON p.id_personas = u.rela_personas

            WHERE dr.tipo_servicio = 'hotel'
            AND hh.rela_hotel = $id_hotel

            ORDER BY r.fecha_creacion DESC
        ";

        return $conexion->consultar($query);
    }

    public function traerPorId($id_reserva) {
        $conexion = new Conexion();
        $id_reserva = (int)$id_reserva;
        $query = "SELECT * FROM reservas WHERE id_reservas = $id_reserva LIMIT 1";
        $resultado = $conexion->consultar($query);
        return !empty($resultado) ? $resultado[0] : [];
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        if (!$this->id_reservas) {
            throw new Exception("No se puede actualizar: ID de reserva no definido.");
        }

        $stmt = $mysqli->prepare("
            UPDATE reservas 
            SET total = ?, reservas_estado = ? 
            WHERE id_reservas = ?
        ");
        $stmt->bind_param("dsi", $this->total, $this->reservas_estado, $this->id_reservas);
        $resultado = $stmt->execute();
        $stmt->close();

        return $resultado;
    }

    public function ver_reserva_completa($id_reserva) {
        $conexion = new Conexion();
        $id = (int)$id_reserva;

        $sqlReserva = "
            SELECT *
            FROM reservas
            WHERE id_reservas = $id
            LIMIT 1
        ";
        $reserva = $conexion->consultar($sqlReserva);

        if (empty($reserva)) return null;
        $reserva = $reserva[0];

        $sqlDetalles = "
            SELECT *
            FROM detalle_reservas
            WHERE rela_reservas = $id
        ";
        $detalles = $conexion->consultar($sqlDetalles);
        $reserva['detalles'] = $detalles;

        foreach ($detalles as $d) {

            if ($d['tipo_servicio'] === 'hotel') {

                $id_det = (int)$d['id_detalle_reserva'];

                $sqlHotel = "
                    SELECT drh.*, 
                        t.nombre AS tipo_habitacion, 
                        hh.descripcion AS habitacion_descripcion
                    FROM detalle_reserva_hotel drh
                    INNER JOIN hotel_habitaciones hh 
                        ON hh.id_hotel_habitacion = drh.rela_habitacion
                    INNER JOIN tipos_habitacion t 
                        ON t.id_tipo_habitacion = hh.rela_tipo_habitacion
                    WHERE drh.rela_detalle_reserva = $id_det
                    LIMIT 1
                ";

                $hotel = $conexion->consultar($sqlHotel);
                $reserva['hotel'] = $hotel[0] ?? null;
            }

            if ($d['tipo_servicio'] === 'transporte') {

                $id_det = (int)$d['id_detalle_reserva'];

                $sqlTransp = "
                    SELECT 
                        drt.*,

                        v.id_viajes,
                        v.viaje_fecha,
                        v.hora_salida,
                        v.hora_llegada,

                        r.rela_ciudad_origen AS id_origen,
                        r.rela_ciudad_destino AS id_destino,

                        c1.nombre AS origen,
                        c2.nombre AS destino,

                        t.transporte_matricula AS matricula,
                        t.transporte_capacidad AS capacidad,
                        t.nombre_servicio,
                        tt.descripcion AS tipo_transporte

                    FROM detalle_reserva_transporte drt

                    INNER JOIN viajes v
                        ON v.id_viajes = drt.id_viaje

                    INNER JOIN transporte_rutas r
                        ON r.id_ruta = v.rela_transporte_rutas

                    INNER JOIN transporte t
                        ON t.id_transporte = r.rela_transporte

                    INNER JOIN tipo_transporte tt
                        ON tt.id_tipo_transporte = t.rela_tipo_transporte

                    LEFT JOIN ciudades c1 
                        ON c1.id_ciudad = r.rela_ciudad_origen

                    LEFT JOIN ciudades c2 
                        ON c2.id_ciudad = r.rela_ciudad_destino

                    WHERE drt.rela_detalle_reserva = $id_det
                ";

                $reserva['transporte'] = $conexion->consultar($sqlTransp);
            }

            if ($d['tipo_servicio'] === 'tour') {

                $id_det = (int)$d['id_detalle_reserva'];

                $sqlTour = "
                    SELECT drt.*, t.nombre_tour AS tour_nombre
                    FROM detalle_reserva_tour drt
                    INNER JOIN tours t ON t.id_tour = drt.rela_tour
                    WHERE drt.rela_detalle_reserva = $id_det
                    LIMIT 1
                ";

                $tour = $conexion->consultar($sqlTour);
                $reserva['tour'] = $tour[0] ?? null;
            }
        }
        return $reserva;
    }

    public function cancelar_detalle_hotel($id_detalle) {
        $conexion = new Conexion();
        $query = "UPDATE detalle_reserva_hotel 
                SET estado = 'cancelada' 
                WHERE id_detalle_hotel = $id_detalle";
        return $conexion->actualizar($query);
    }

    public function traer_por_transporte($id_transporte) {
        $conexion = new Conexion();
        $id_transporte = (int)$id_transporte;

        $query = "
            SELECT 
                r.id_reservas,
                r.reservas_estado,
                r.total AS total_reserva,
                r.fecha_creacion,

                CONCAT(p.personas_nombre, ' ', p.personas_apellido) AS cliente,
                p.personas_nombre AS cliente_nombre,
                p.personas_apellido AS cliente_apellido,

                dr.id_detalle_reserva,
                dr.tipo_servicio,
                dr.subtotal AS importe_total,

                drt.id_detalle_transporte,
                drt.piso,
                drt.numero_asiento,
                drt.fila,
                drt.columna,
                drt.fecha_servicio,
                drt.precio_unitario,
                drt.estado AS detalle_transporte_estado,

                v.id_viajes,
                CONCAT(ci_origen.nombre, ' → ', ci_destino.nombre) AS viaje_info,
                v.hora_salida,
                v.hora_llegada,

                t.id_transporte,
                t.nombre_servicio,
                t.transporte_matricula

            FROM reservas r

            INNER JOIN detalle_reservas dr 
                ON dr.rela_reservas = r.id_reservas

            INNER JOIN detalle_reserva_transporte drt 
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva

            INNER JOIN viajes v
                ON v.id_viajes = drt.id_viaje

            INNER JOIN transporte_rutas tr
                ON tr.id_ruta = v.rela_transporte_rutas

            INNER JOIN ciudades ci_origen
                ON ci_origen.id_ciudad = tr.rela_ciudad_origen

            INNER JOIN ciudades ci_destino
                ON ci_destino.id_ciudad = tr.rela_ciudad_destino

            INNER JOIN transporte t
                ON t.id_transporte = tr.rela_transporte

            INNER JOIN usuarios u
                ON u.id_usuarios = r.rela_usuarios

            INNER JOIN personas p
                ON p.id_personas = u.rela_personas

            WHERE dr.tipo_servicio = 'transporte'
            AND t.id_transporte = $id_transporte
            ORDER BY r.fecha_creacion DESC, drt.piso ASC, drt.numero_asiento ASC
        ";

        return $conexion->consultar($query);
    }

    public function traer_detalle_transporte_por_id($id_detalle){
        $conexion = new Conexion();
        $id = (int)$id_detalle;

        $query = "
            SELECT drt.*, v.*
            FROM detalle_reserva_transporte drt
            INNER JOIN viajes v ON v.id_viajes = drt.id_viaje
            WHERE drt.id_detalle_transporte = $id
            LIMIT 1
        ";

        $res = $conexion->consultar($query);
        return !empty($res) ? $res[0] : null;
    }

    public function cancelar_detalle_transporte($id_detalle){
        $conexion = new Conexion();
        $id = (int)$id_detalle;

        $query = "
            UPDATE detalle_reserva_transporte 
            SET estado = 'cancelada'
            WHERE id_detalle_transporte = $id
        ";

        return $conexion->actualizar($query);
    }

    public function traer_por_tour($id_tour) {
        $conexion = new Conexion();
        $id_tour = (int)$id_tour;

        $query = "
            SELECT 
                r.id_reservas,
                r.rela_usuarios,
                r.total,
                r.reservas_estado,
                r.fecha_creacion,

                CONCAT(p.personas_nombre, ' ', p.personas_apellido) AS cliente,

                dr.id_detalle_reserva,
                dr.tipo_servicio,
                dr.cantidad,
                dr.precio_unitario,
                dr.subtotal,

                drt.id_detalle_tour,
                drt.fecha AS fecha_tour,
                drt.estado AS detalle_tour_estado,

                t.id_tour,
                t.nombre_tour

            FROM reservas r
            INNER JOIN detalle_reservas dr ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_tour drt ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN tours t ON t.id_tour = drt.rela_tour
            INNER JOIN usuarios u ON u.id_usuarios = r.rela_usuarios
            INNER JOIN personas p ON p.id_personas = u.rela_personas
            WHERE dr.tipo_servicio = 'tour'
            AND drt.rela_tour = $id_tour
            ORDER BY r.fecha_creacion DESC
        ";

        return $conexion->consultar($query);
    }

    public function ver_reserva_tour($id_reserva) {
        $conexion = new Conexion();
        $id_reserva = (int)$id_reserva;

        $query = "
            SELECT 
                r.id_reservas,
                r.rela_usuarios,
                r.total,
                r.reservas_estado,
                r.fecha_creacion,
                CONCAT(p.personas_nombre, ' ', p.personas_apellido) AS cliente,
                dr.id_detalle_reserva,
                dr.tipo_servicio,
                dr.cantidad,
                dr.precio_unitario,
                dr.subtotal AS importe_total,
                drt.id_detalle_tour,
                drt.rela_tour,
                drt.fecha AS fecha_tour,
                t.nombre_tour
            FROM reservas r
            INNER JOIN detalle_reservas dr ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_tour drt ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN tours t ON t.id_tour = drt.rela_tour
            INNER JOIN usuarios u ON u.id_usuarios = r.rela_usuarios
            INNER JOIN personas p ON p.id_personas = u.rela_personas
            WHERE r.id_reservas = $id_reserva
        ";

        return $conexion->consultar($query);
    }

    public function traer_detalle_tour_por_id($id_detalle){
        $conexion = new Conexion();
        $id = (int)$id_detalle;

        $query = "
            SELECT drt.*, t.nombre_tour AS tour_nombre
            FROM detalle_reserva_tour drt
            INNER JOIN tours t ON t.id_tour = drt.rela_tour
            WHERE drt.id_detalle_tour = $id
            LIMIT 1
        ";

        $res = $conexion->consultar($query);
        return !empty($res) ? $res[0] : null;
    }

    public function cancelar_detalle_tour($id_detalle){
        $conexion = new Conexion();
        $id = (int)$id_detalle;

        $query = "
            UPDATE detalle_reserva_tour
            SET estado = 'cancelada'
            WHERE id_detalle_tour = $id
        ";

        return $conexion->actualizar($query);
    }

    public function confirmar_detalle_hotel($id_detalle_reserva) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            UPDATE detalle_reserva_hotel
            SET estado = 'confirmada'
            WHERE rela_detalle_reserva = ?
        ");

        $stmt->bind_param("i", $id_detalle_reserva);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function confirmar_detalle_tour($id_detalle_reserva) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            UPDATE detalle_reserva_tour
            SET estado = 'confirmada'
            WHERE rela_detalle_reserva = ?
        ");

        $stmt->bind_param("i", $id_detalle_reserva);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    public function descontar_stock_hotel($id_habitacion, $dia, $cantidad) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            UPDATE hotel_habitaciones_stock
            SET cantidad_disponible = cantidad_disponible - ?
            WHERE rela_habitacion = ?
            AND fecha = ?
            AND activo = 1
            AND cantidad_disponible >= ?
        ");

        $stmt->bind_param("iisi", $cantidad, $id_habitacion, $dia, $cantidad);
        $stmt->execute();

        $afectadas = $stmt->affected_rows; 
        $stmt->close();

        return $afectadas > 0;
    }

    public function descontar_stock_tour($id_stock_tour, $cantidad) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $sql = "UPDATE stock_tour 
                SET cupos_disponibles = cupos_disponibles - ?, 
                    cupos_reservados = cupos_reservados + ?,
                    updated_at = NOW()
                WHERE id_stock_tour = ? 
                AND cupos_disponibles >= ?";
        
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iiii", $cantidad, $cantidad, $id_stock_tour, $cantidad);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    public function traerStockTour($id_detalle_reserva) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $sql = "SELECT st.id_stock_tour
                FROM detalle_reserva_tour dt
                JOIN stock_tour st 
                ON st.rela_tour = dt.rela_tour
                AND st.fecha = dt.fecha
                WHERE dt.rela_detalle_reserva = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_detalle_reserva);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public function getId_reservas() { return $this->id_reservas; }
    public function setId_reservas($id) { $this->id_reservas = $id; return $this; }

    public function getRela_usuarios() { return $this->rela_usuarios; }
    public function setRela_usuarios($id) { $this->rela_usuarios = $id; return $this; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; return $this; }

    public function getReservas_estado() { return $this->reservas_estado; }
    public function setReservas_estado($estado) { $this->reservas_estado = $estado; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
?>
