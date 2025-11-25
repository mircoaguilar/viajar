<?php
require_once('conexion.php');

class HotelDashboard {
    public function contar_hoteles() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $sql = "SELECT COUNT(*) AS total FROM hotel WHERE activo = 1";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

   public function contar_habitaciones() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_usuario = (int)$_SESSION['id_usuarios'];

        $sql = "
            SELECT COUNT(hh.id_hotel_habitacion) AS total
            FROM hotel_habitaciones hh
            INNER JOIN hotel h 
                ON h.id_hotel = hh.rela_hotel
            INNER JOIN proveedores p 
                ON p.id_proveedores = h.rela_proveedor
            WHERE hh.activo = 1
            AND h.activo = 1
            AND p.activo = 1
            AND p.rela_usuario = $id_usuario
        ";

        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();

        return $row['total'] ?? 0;
    }


    public function contar_reservas() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $sql = "SELECT COUNT(*) AS total FROM reserva WHERE activo = 1";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function contar_reservas_pendientes() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $sql = "SELECT COUNT(*) AS total FROM reserva WHERE estado = 'Pendiente' AND activo = 1";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function contar_reservas_confirmadas() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$_SESSION['id_usuarios'];

        $sql = "
            SELECT COUNT(*) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr 
                ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_hotel drh 
                ON drh.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN hotel_habitaciones hh 
                ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN hotel h 
                ON h.id_hotel = hh.rela_hotel
            INNER JOIN proveedores p 
                ON p.id_proveedores = h.rela_proveedor
            INNER JOIN usuarios u
                ON u.id_usuarios = p.rela_usuario
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'hotel'
            AND u.id_usuarios = $id_usuario
            AND MONTH(r.fecha_creacion) = MONTH(CURRENT_DATE())
            AND YEAR(r.fecha_creacion) = YEAR(CURRENT_DATE())
        ";

        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();

        return $row['total'] ?? 0;
    }

    public function contar_reservas_canceladas() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $sql = "SELECT COUNT(*) AS total FROM reserva WHERE estado = 'Cancelada' AND activo = 1";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function total_ingresos() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_proveedor = (int)$_SESSION['id_usuarios']; 

        $sql = "
            SELECT SUM(dr.precio_unitario) AS total
            FROM detalle_reservas dr
            INNER JOIN detalle_reserva_hotel drh ON drh.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN hotel_habitaciones hh ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN hotel h ON h.id_hotel = hh.rela_hotel
            INNER JOIN reservas r ON r.id_reservas = dr.rela_reservas
            WHERE dr.tipo_servicio = 'hotel'
            AND r.activo = 1
            AND h.rela_proveedor = $id_proveedor
        ";

        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();

        return $row['total'] ?? 0;
    }



    public function ingresos_por_mes($anio) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $anio = (int)$anio;

        $sql = "
            SELECT MONTH(fecha) AS mes, SUM(monto_total) AS total
            FROM ventas
            WHERE YEAR(fecha) = $anio AND activo = 1
            GROUP BY MONTH(fecha)
            ORDER BY mes ASC
        ";

        $res = $mysqli->query($sql);
        $datos = [];

        while ($row = $res->fetch_assoc()) {
            $datos[] = $row;
        }

        return $datos;
    }

    public function reservas_por_mes($anio) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_usuario = (int)$_SESSION['id_usuarios'];

        $sql = "
            SELECT 
                MONTH(r.fecha_creacion) AS mes,
                COUNT(*) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr
                ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_hotel drh
                ON drh.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN hotel_habitaciones hh
                ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN hotel h
                ON h.id_hotel = hh.rela_hotel
            INNER JOIN proveedores p
                ON p.id_proveedores = h.rela_proveedor
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'hotel'
            AND p.rela_usuario = $id_usuario
            AND YEAR(r.fecha_creacion) = $anio
            GROUP BY MONTH(r.fecha_creacion)
            ORDER BY mes ASC
        ";

        $res = $mysqli->query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }


    public function ocupacion_por_hotel() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_usuario = (int)($_SESSION['id_usuarios'] ?? 0);

        $sql = "
            SELECT 
                h.id_hotel,
                h.hotel_nombre,
                COUNT(drh.id_detalle_hotel) AS total
            FROM hotel h
            INNER JOIN proveedores p
                ON p.id_proveedores = h.rela_proveedor
                AND p.rela_usuario = {$id_usuario}
            LEFT JOIN hotel_habitaciones hh
                ON hh.rela_hotel = h.id_hotel
            LEFT JOIN detalle_reserva_hotel drh
                ON drh.rela_habitacion = hh.id_hotel_habitacion
            LEFT JOIN detalle_reservas dr
                ON dr.id_detalle_reserva = drh.rela_detalle_reserva
                AND dr.tipo_servicio = 'hotel'
            LEFT JOIN reservas r
                ON r.id_reservas = dr.rela_reservas
                AND r.reservas_estado = 'confirmada'
                AND r.activo = 1
            WHERE
                h.activo = 1
                AND h.estado_revision = 'aprobado'
            GROUP BY
                h.id_hotel, h.hotel_nombre
            ORDER BY
                total DESC
        ";

        $res = $mysqli->query($sql);

        $data = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $row['total'] = (int)($row['total'] ?? 0);
                $data[] = $row;
            }
        }

        return $data;
    }


    public function top_habitaciones_mas_reservadas(){
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_usuario = (int)$_SESSION['id_usuarios'];

        $sql = "
            SELECT 
                h.hotel_nombre AS hotel_nombre,
                CONCAT(t.nombre, ' (ID ', hh.id_hotel_habitacion, ')') AS habitacion_nombre,
                COUNT(*) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr 
                    ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_hotel drh
                    ON drh.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN hotel_habitaciones hh
                    ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN tipos_habitacion t
                    ON t.id_tipo_habitacion = hh.rela_tipo_habitacion
            INNER JOIN hotel h
                    ON h.id_hotel = hh.rela_hotel
            INNER JOIN proveedores p
                    ON p.id_proveedores = h.rela_proveedor
            INNER JOIN usuarios u
                    ON u.id_usuarios = p.rela_usuario
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'hotel'
            AND u.id_usuarios = $id_usuario
            GROUP BY hh.id_hotel_habitacion
            ORDER BY total DESC
            LIMIT 5
        ";

        $res = $mysqli->query($sql);
        $data = [];

        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function reservas_pendientes_sin_revision() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $sql = "SELECT * FROM reserva WHERE estado = 'Pendiente' AND activo = 1";
        return $mysqli->query($sql);
    }

    public function reservas_con_checkin_hoy() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $hoy = date('Y-m-d');
        $sql = "SELECT * FROM reserva WHERE checkin = '$hoy' AND activo = 1";
        return $mysqli->query($sql);
    }

    public function reservas_con_checkout_hoy() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $hoy = date('Y-m-d');
        $sql = "SELECT * FROM reserva WHERE checkout = '$hoy' AND activo = 1";
        return $mysqli->query($sql);
    }

    public function ingresosDelMes() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$_SESSION['id_usuarios'];
        $sql = "
            SELECT 
                SUM(dr.subtotal) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr 
                ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_hotel drh
                ON drh.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN hotel_habitaciones hh
                ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN hotel h
                ON h.id_hotel = hh.rela_hotel
            INNER JOIN proveedores p
                ON p.id_proveedores = h.rela_proveedor
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'hotel'
            AND p.rela_usuario = $id_usuario
            AND MONTH(r.fecha_creacion) = MONTH(CURRENT_DATE())
            AND YEAR(r.fecha_creacion) = YEAR(CURRENT_DATE())
        ";

        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();

        return $row['total'] ?? 0;
    }

    public function ocupacion_actual() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_usuario = (int)$_SESSION['id_usuarios'];

        $sql = "
            SELECT COUNT(*) AS ocupadas
            FROM detalle_reserva_hotel drh
            INNER JOIN detalle_reservas dr 
                ON dr.id_detalle_reserva = drh.rela_detalle_reserva
            INNER JOIN reservas r 
                ON r.id_reservas = dr.rela_reservas
            INNER JOIN hotel_habitaciones hh 
                ON hh.id_hotel_habitacion = drh.rela_habitacion
            INNER JOIN hotel h 
                ON h.id_hotel = hh.rela_hotel
            INNER JOIN proveedores p
                ON p.id_proveedores = h.rela_proveedor
            WHERE r.reservas_estado = 'confirmada'
            AND r.activo = 1
            AND dr.tipo_servicio = 'hotel'
            AND p.rela_usuario = $id_usuario
            AND drh.check_in <= CURRENT_DATE()
            AND drh.check_out > CURRENT_DATE()
        ";

        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();

        return $row['ocupadas'] ?? 0;
    }

}
