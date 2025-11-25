<?php
require_once('transporte.php');
require_once('viaje.php');

class TransporteDashboard {
    private $transporte;
    private $viaje;

    public function __construct() {
        $this->transporte = new Transporte();
        $this->viaje = new Viaje();
    }

    public function contar_transportes($id_usuario) {
        $transportes = $this->transporte->traer_transportes_por_usuario($id_usuario);
        return count($transportes);
    }

    public function contar_rutas($id_usuario) {
        $rutas = $this->transporte->traer_rutas_por_usuario($id_usuario);
        return count($rutas);
    }

    public function contar_viajes_proximos($id_usuario, $limite = 10) {
        $viajes = $this->viaje->traer_viajes_proximos_por_usuario($id_usuario, $limite);
        return count($viajes);
    }

    public function contar_reservas_confirmadas_mes($id_usuario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;

        $sql = "
            SELECT COUNT(DISTINCT r.id_reservas) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr
                ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_transporte drt
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN viajes v
                ON v.id_viajes = drt.id_viaje
            INNER JOIN transporte_rutas tr
                ON tr.id_ruta = v.rela_transporte_rutas
            INNER JOIN transporte t
                ON t.id_transporte = tr.rela_transporte
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'transporte'
            AND p.rela_usuario = $id_usuario
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
            INNER JOIN detalle_reserva_transporte drt
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN viajes v
                ON v.id_viajes = drt.id_viaje
            INNER JOIN transporte_rutas tr
                ON tr.id_ruta = v.rela_transporte_rutas
            INNER JOIN transporte t
                ON t.id_transporte = tr.rela_transporte
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'transporte'
            AND p.rela_usuario = $id_usuario
            AND MONTH(r.fecha_creacion) = MONTH(CURRENT_DATE())
            AND YEAR(r.fecha_creacion) = YEAR(CURRENT_DATE())
        ";

        $res = $mysqli->query($sql);
        $row = $res->fetch_assoc();
        return $row['total'] ?? 0;
    }

    public function top_viajes_mas_reservados($id_usuario, $limite = 5) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;
        $sql = "
            SELECT 
                t.nombre_servicio AS transporte_nombre,
                tr.trayecto AS ruta_trayecto,
                COUNT(*) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr
                ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_transporte drt
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN viajes v
                ON v.id_viajes = drt.id_viaje
            INNER JOIN transporte_rutas tr
                ON tr.id_ruta = v.rela_transporte_rutas
            INNER JOIN transporte t
                ON t.id_transporte = tr.rela_transporte
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'transporte'
            AND p.rela_usuario = $id_usuario
            GROUP BY v.id_viajes
            ORDER BY total DESC
            LIMIT $limite
        ";
        $res = $mysqli->query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) {
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
            SELECT 
                MONTH(r.fecha_creacion) AS mes,
                COUNT(DISTINCT r.id_reservas) AS total
            FROM reservas r
            INNER JOIN detalle_reservas dr
                ON dr.rela_reservas = r.id_reservas
            INNER JOIN detalle_reserva_transporte drt
                ON drt.rela_detalle_reserva = dr.id_detalle_reserva
            INNER JOIN viajes v
                ON v.id_viajes = drt.id_viaje
            INNER JOIN transporte_rutas tr
                ON tr.id_ruta = v.rela_transporte_rutas
            INNER JOIN transporte t
                ON t.id_transporte = tr.rela_transporte
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
            WHERE r.activo = 1
            AND r.reservas_estado = 'confirmada'
            AND dr.tipo_servicio = 'transporte'
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

    public function ocupacion_por_transporte($id_usuario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_usuario = (int)$id_usuario;

        $sql = "
            SELECT 
                t.id_transporte,
                t.nombre_servicio AS transporte_nombre,
                COUNT(drt.id_detalle_transporte) AS total_ocupados
            FROM transporte t
            INNER JOIN proveedores p
                ON p.id_proveedores = t.rela_proveedor
                AND p.rela_usuario = {$id_usuario}
            LEFT JOIN transporte_rutas tr
                ON tr.rela_transporte = t.id_transporte
            LEFT JOIN viajes v
                ON v.rela_transporte_rutas = tr.id_ruta
            LEFT JOIN detalle_reserva_transporte drt
                ON drt.id_viaje = v.id_viajes
            LEFT JOIN detalle_reservas dr
                ON dr.id_detalle_reserva = drt.rela_detalle_reserva
                AND dr.tipo_servicio = 'transporte'
            LEFT JOIN reservas r
                ON r.id_reservas = dr.rela_reservas
                AND r.reservas_estado = 'confirmada'
                AND r.activo = 1
            WHERE t.activo = 1
            GROUP BY t.id_transporte, t.nombre_servicio
            ORDER BY total_ocupados DESC
        ";

        $res = $mysqli->query($sql);
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $row['total_ocupados'] = (int)($row['total_ocupados'] ?? 0);
            $data[] = $row;
        }
        return $data;
    }
}
