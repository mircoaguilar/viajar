<?php
require_once('conexion.php');

class Admin {
    private $id_admin;
    private $nombre_admin;
    private $email_admin;
    private $activo;

    public function __construct(
        $id_admin = '',
        $nombre_admin = '',
        $email_admin = '',
        $activo = 1
    ) {
        $this->id_admin = $id_admin;
        $this->nombre_admin = $nombre_admin;
        $this->email_admin = $email_admin;
        $this->activo = $activo;
    }

    // Contar usuarios activos
    public function contarUsuarios() {
        $conexion = new Conexion();
        $query = "SELECT COUNT(*) AS total FROM usuarios WHERE activo = 1";
        $res = $conexion->consultar($query);
        return $res[0]['total'] ?? 0;
    }

    // Contar reservas
    public function contarReservas() {
        $conexion = new Conexion();
        $query = "SELECT COUNT(*) AS total FROM reservas";
        $res = $conexion->consultar($query);
        return $res[0]['total'] ?? 0;
    }

    // Obtener ingresos totales confirmados
    public function obtenerIngresosTotales() {
        $conexion = new Conexion();
        $query = "SELECT COALESCE(SUM(total),0) AS total FROM reservas WHERE activo = 1";
        $res = $conexion->consultar($query);
        return $res[0]['total'] ?? 0;
    }

    // Listar últimos usuarios
    public function traerUltimosUsuarios($limite = 5) {
        $conexion = new Conexion();
        $limite = (int)$limite;
        $query = "
            SELECT 
                u.id_usuarios, 
                u.usuarios_nombre_usuario AS nombre,
                u.usuarios_email AS email,
                u.usuarios_fecha_alta,
                p.perfiles_nombre AS perfil
            FROM usuarios u
            LEFT JOIN perfiles p ON u.rela_perfiles = p.id_perfiles
            WHERE u.activo = 1
            ORDER BY u.usuarios_fecha_alta DESC
            LIMIT $limite
        ";
        return $conexion->consultar($query);
    }



    // Listar últimas reservas
    public function traerUltimasReservas($limite = 5) {
        $conexion = new Conexion();
        $limite = (int)$limite;

        $query = "
            SELECT 
                r.id_reservas AS id_reserva,
                r.fecha_creacion AS fecha_reserva,
                r.reservas_estado,
                u.usuarios_nombre_usuario AS usuario,
                GROUP_CONCAT(dr.tipo_servicio SEPARATOR ', ') AS servicio,
                r.total AS precio
            FROM reservas r
            JOIN usuarios u ON r.rela_usuarios = u.id_usuarios
            LEFT JOIN detalle_reservas dr ON dr.rela_reservas = r.id_reservas
            WHERE r.activo = 1
            GROUP BY r.id_reservas
            ORDER BY r.fecha_creacion DESC
            LIMIT $limite
        ";

        return $conexion->consultar($query);
    }


    // Datos para gráfico
    public function obtenerReservasPorMes() {
        $conexion = new Conexion();
        $query = "
            SELECT 
                DATE_FORMAT(fecha_creacion, '%Y-%m') AS mes,
                COUNT(*) AS cantidad
            FROM reservas
            WHERE activo = 1
            GROUP BY mes
            ORDER BY mes ASC
        ";
        $res = $conexion->consultar($query);

        foreach ($res as &$r) {
            $r['mes_nombre'] = date('M Y', strtotime($r['mes'] . '-01')); 
        }
        return $res;
    }

    // Listar hoteles pendientes de aprobación
    public function listarHotelesPendientes() {
        $conexion = new Conexion();
        $query = "
            SELECT 
                h.id_hotel,
                h.hotel_nombre AS nombre,
                hi.direccion AS ubicacion,
                hi.descripcion,
                h.fecha_alta AS fecha,
                p.razon_social AS proveedor
            FROM hotel h
            LEFT JOIN hoteles_info hi ON hi.rela_hotel = h.id_hotel
            LEFT JOIN proveedores p ON h.rela_proveedor = p.id_proveedores
            WHERE h.activo = 0
            ORDER BY h.fecha_alta DESC;
        ";
        return $conexion->consultar($query);
    }

    // Listar transportes pendientes de aprobación
    public function listarTransportesPendientes() {
        $conexion = new Conexion();
        $query = "
            SELECT 
                t.id_transporte,
                t.nombre_servicio AS nombre,
                t.transporte_matricula AS matricula,
                t.transporte_capacidad AS capacidad,
                tt.descripcion AS tipo, 
                t.descripcion,
                t.imagen_principal,
                p.razon_social AS proveedor,
                t.fecha_alta AS fecha
            FROM transporte t
            LEFT JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            LEFT JOIN tipo_transporte tt ON t.rela_tipo_transporte = tt.id_tipo_transporte
            WHERE t.activo = 0
            ORDER BY t.fecha_alta DESC
        ";
        return $conexion->consultar($query);
    }

    // Listar tours pendientes de aprobación
    public function listarToursPendientes() {
        $conexion = new Conexion();
        $query = "
            SELECT 
                tr.id_tour,
                tr.nombre_tour AS nombre,
                tr.descripcion,
                tr.duracion_horas AS duracion,
                tr.precio_por_persona AS precio,
                tr.lugar_encuentro,
                tr.direccion,
                tr.imagen_principal,
                tr.created_at AS fecha,
                p.razon_social AS proveedor
            FROM tours tr
            LEFT JOIN proveedores p ON tr.rela_proveedor = p.id_proveedores
            WHERE tr.activo = 0
            ORDER BY tr.created_at DESC
        ";
        return $conexion->consultar($query);
    }



    public function cambiarEstadoServicio($tipo, $id, $accion) {
        $conexion = new Conexion();
        $tablas = [
            'hotel' => 'hotel',
            'transporte' => 'transporte',
            'tour' => 'tours'
        ];
        if (!isset($tablas[$tipo])) return false;
        $tabla = $tablas[$tipo];
        $id_campo = "id_" . $tabla;
        $nuevo_estado = ($accion === 'aprobar') ? 1 : 0;
        $id = (int)$id;
        $query = "UPDATE $tabla SET activo = $nuevo_estado WHERE $id_campo = $id";
        return $conexion->actualizar($query);
    }


    // Getters & Setters
    public function getId_admin() { return $this->id_admin; }
    public function setId_admin($id) { $this->id_admin = $id; return $this; }

    public function getNombre_admin() { return $this->nombre_admin; }
    public function setNombre_admin($n) { $this->nombre_admin = $n; return $this; }

    public function getEmail_admin() { return $this->email_admin; }
    public function setEmail_admin($e) { $this->email_admin = $e; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($a) { $this->activo = $a; return $this; }
}
?>
