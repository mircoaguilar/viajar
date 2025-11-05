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
            INSERT INTO detalle_reserva_hotel (rela_detalle_reserva, rela_habitacion, check_in, check_out, noches) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iissi", $id_detalle_reserva, $id_habitacion, $check_in, $check_out, $noches);
        $stmt->execute();
        $id_detalle_hotel = $stmt->insert_id;
        $stmt->close();

        return $id_detalle_hotel;
    }

    public function crear_detalle_tour($id_detalle_reserva, $id_tour, $fecha_tour) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("
            INSERT INTO detalle_reserva_tour (rela_detalle_reserva, rela_tour, fecha) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $id_detalle_reserva, $id_tour, $fecha_tour);
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
            (rela_detalle_reserva, id_viaje, piso, numero_asiento, fila, columna, fecha_servicio, precio_unitario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            error_log("Error preparando statement: " . $mysqli->error);
            return false;
        }

        foreach ($asientosArray as $a) {
            $piso = isset($a['piso']) ? (int)$a['piso'] : null;
            $numero = isset($a['numero']) ? (int)$a['numero'] : null;
            $fila = isset($a['fila']) ? (int)$a['fila'] : null;
            $columna = isset($a['columna']) ? (int)$a['columna'] : null;

            if ($piso === null || $numero === null) {
                error_log("crear_detalle_transporte: asiento incompleto: " . print_r($a, true));
                continue;
            }

            $stmt->bind_param("iiiiissd", 
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
                drh.check_in AS fecha_inicio,
                drh.check_out AS fecha_fin,
                th.nombre AS habitacion_nombre 
            FROM reservas r
            INNER JOIN detalle_reservas dr ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_hotel drh ON drh.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN hotel_habitaciones hh ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN tipos_habitacion th ON th.id_tipo_habitacion = hh.rela_tipo_habitacion
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
