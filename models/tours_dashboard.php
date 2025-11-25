<?php
require_once('tour.php'); 
require_once('reserva.php'); 

class TourDashboard {
    private $tour;
    private $reserva;

    public function __construct() {
        $this->tour = new Tour();
        $this->reserva = new Reserva();
    }

    public function contar_tours($id_usuario) {
        $tours = $this->tour->traer_tours_por_usuario($id_usuario);
        return count($tours);
    }

    public function tours_con_reservas($id_usuario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;

        $sql = "
            SELECT COUNT(DISTINCT t.id_tour) AS total
            FROM tours t
            INNER JOIN detalle_reserva_tour drt
                ON drt.rela_tour = t.id_tour
            INNER JOIN detalle_reservas dr
                ON dr.id_detalle_reserva = drt.rela_detalle_reserva
                AND dr.tipo_servicio = 'tour'
            INNER JOIN reservas r
                ON r.id_reservas = dr.rela_reservas
                AND r.activo = 1
                AND r.reservas_estado = 'confirmada'
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
                AND p.rela_usuario = $id_usuario
            WHERE t.activo = 1
        ";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function reservas_mes($id_usuario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;

        $sql = "
            SELECT COUNT(DISTINCT r.id_reservas) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr
                ON dr.rela_reservas = r.id_reservas
                AND dr.tipo_servicio = 'tour'
            INNER JOIN detalle_reserva_tour drt
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN tours t
                ON t.id_tour = drt.rela_tour
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
                AND p.rela_usuario = $id_usuario
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND MONTH(r.fecha_creacion) = MONTH(CURRENT_DATE())
            AND YEAR(r.fecha_creacion) = YEAR(CURRENT_DATE())
        ";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function ingresos_mes($id_usuario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;

        $sql = "
            SELECT SUM(dr.subtotal) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr
                ON dr.rela_reservas = r.id_reservas
                AND dr.tipo_servicio = 'tour'
            INNER JOIN detalle_reserva_tour drt
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN tours t
                ON t.id_tour = drt.rela_tour
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
                AND p.rela_usuario = $id_usuario
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND MONTH(r.fecha_creacion) = MONTH(CURRENT_DATE())
            AND YEAR(r.fecha_creacion) = YEAR(CURRENT_DATE())
        ";
        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function top_tours_mas_reservados($id_usuario, $limite = 5) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;

        $sql = "
            SELECT t.id_tour, t.nombre_tour, COUNT(r.id_reservas) AS total
            FROM detalle_reserva_tour drt
            INNER JOIN detalle_reservas dr
                ON dr.id_detalle_reserva = drt.rela_detalle_reserva
                AND dr.tipo_servicio = 'tour'
            INNER JOIN reservas r
                ON r.id_reservas = dr.rela_reservas
                AND r.activo = 1
                AND r.reservas_estado = 'confirmada'
            INNER JOIN tours t
                ON t.id_tour = drt.rela_tour
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
                AND p.rela_usuario = $id_usuario
            GROUP BY t.id_tour, t.nombre_tour
            ORDER BY total DESC
            LIMIT $limite
        ";
        $res = $mysqli->query($sql);
        $data = [];
        while($row = $res->fetch_assoc()){
            $data[] = $row;
        }
        return $data;
    }

    public function reservas_por_mes($id_usuario, $anio = null) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;
        $anio = $anio ?? date('Y');

        $sql = "
            SELECT MONTH(r.fecha_creacion) AS mes,
                COUNT(DISTINCT r.id_reservas) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr
                ON dr.rela_reservas = r.id_reservas
                AND dr.tipo_servicio = 'tour'
            INNER JOIN detalle_reserva_tour drt
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN tours t
                ON t.id_tour = drt.rela_tour
                AND t.activo = 1
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
                AND p.rela_usuario = $id_usuario
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
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

    public function reservas_por_tour($id_usuario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;

        $sql = "
            SELECT t.nombre_tour AS tour, COUNT(r.id_reservas) AS total
            FROM tours t
            LEFT JOIN detalle_reserva_tour drt
                ON drt.rela_tour = t.id_tour
            LEFT JOIN detalle_reservas dr
                ON dr.id_detalle_reserva = drt.rela_detalle_reserva
                AND dr.tipo_servicio = 'tour'
            LEFT JOIN reservas r
                ON r.id_reservas = dr.rela_reservas
                AND r.reservas_estado = 'confirmada'
                AND r.activo = 1
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
                AND p.rela_usuario = $id_usuario
            WHERE t.activo = 1
            AND t.estado_revision = 'aprobado'
            GROUP BY t.id_tour
            ORDER BY total DESC
        ";
        $res = $mysqli->query($sql);
        $data = [];
        while($row = $res->fetch_assoc()){
            $row['total'] = (int)$row['total'];
            $data[] = $row;
        }
        return $data;
    }
}
