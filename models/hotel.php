<?php
require_once('conexion.php');
require_once('proveedor.php');

class Hotel {
    private $id_hotel;
    private $hotel_nombre;
    private $imagen_principal;
    private $rela_provincia;
    private $rela_ciudad;
    private $rela_proveedor;
    private $estado_revision;
    private $motivo_rechazo;
    private $fecha_revision;
    private $revisado_por;
    private $activo;

    public function __construct() {
        $this->activo = 1;
    }

    public function traer_hotel($id_hotel) {
        $conexion = new Conexion();
        $id = (int)$id_hotel;
        return $conexion->consultar("SELECT * FROM hotel WHERE id_hotel = $id AND activo = 1");
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $nombre = $mysqli->real_escape_string($this->hotel_nombre);
        $img = $mysqli->real_escape_string($this->imagen_principal);
        $prov = (int)$this->rela_provincia;
        $ciudad = (int)$this->rela_ciudad;

        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($_SESSION['id_usuarios']);

        if (!$proveedor) {
            throw new Exception("No hay proveedor asociado al usuario.");
        }

        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "
            INSERT INTO hotel
            (hotel_nombre, imagen_principal, rela_provincia, rela_ciudad, rela_proveedor, 
             estado_revision, activo, fecha_alta)
            VALUES
            ('$nombre', '$img', $prov, $ciudad, $id_proveedor, 'pendiente', 1, NOW())
        ";

        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $actual = $this->traer_hotel($this->id_hotel);
        if (!$actual) return false;

        $estado_actual = $actual[0]['estado_revision'];

        $nombre = $mysqli->real_escape_string($this->hotel_nombre);
        $prov = (int)$this->rela_provincia;
        $ciudad = (int)$this->rela_ciudad;

        $imagen_sql = "";
        if ($this->imagen_principal) {
            $imagen_sql = ", imagen_principal='" . $mysqli->real_escape_string($this->imagen_principal) . "'";
        }

        if ($estado_actual === 'rechazado') {
            $revision_sql = ",
                estado_revision='pendiente',
                motivo_rechazo=NULL,
                fecha_revision=NULL,
                revisado_por=NULL
            ";
        } else {
            $revision_sql = "";
        }

        $query = "
            UPDATE hotel SET
                hotel_nombre='$nombre',
                rela_provincia=$prov,
                rela_ciudad=$ciudad
                $imagen_sql
                $revision_sql
            WHERE id_hotel = " . (int)$this->id_hotel;

        return $conexion->actualizar($query);
    }

    public function traer_hoteles($id_usuario) {
        $conexion = new Conexion();
        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);

        if (!$proveedor) {
            return [];
        }
        $id_proveedor = (int)$proveedor['id_proveedores'];
        $query = "
            SELECT h.id_hotel, h.hotel_nombre, h.imagen_principal, i.descripcion, 
                h.rela_ciudad, h.estado_revision, h.motivo_rechazo
            FROM hotel h
            LEFT JOIN hoteles_info i ON i.rela_hotel = h.id_hotel
            WHERE h.activo = 1
            AND h.estado_revision = 'aprobado'
            AND h.rela_proveedor = $id_proveedor
            ORDER BY h.fecha_alta DESC
        ";
        return $conexion->consultar($query);  
    }


    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE hotel SET activo = 0 WHERE id_hotel=" . (int)$this->id_hotel;
        return $conexion->actualizar($query);
    }

    public function verificar_propietario($id_hotel, $id_usuario) {
        $conexion = new Conexion();
        $id_hotel = (int)$id_hotel;
        $id_usuario = (int)$id_usuario;

        $query = "
            SELECT COUNT(*) AS cuenta
            FROM hotel h
            INNER JOIN proveedores p ON h.rela_proveedor = p.id_proveedores
            INNER JOIN usuarios u ON p.rela_usuario = u.id_usuarios
            WHERE h.id_hotel = $id_hotel
            AND u.id_usuarios = $id_usuario
        ";

        $res = $conexion->consultar($query);
        return $res[0]['cuenta'] > 0;
    }

    public function traer_hoteles_proveedor_completo($id_usuario) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);

        if (!$proveedor) return [];

        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "
            SELECT 
                h.id_hotel,
                h.hotel_nombre,
                h.imagen_principal,
                h.estado_revision,
                h.motivo_rechazo,
                h.fecha_alta,
                p.nombre AS provincia_nombre,
                c.nombre AS ciudad_nombre,

                (
                    SELECT COUNT(*) 
                    FROM hotel_habitaciones hh
                    WHERE hh.rela_hotel = h.id_hotel
                    AND hh.activo = 1
                ) AS total_habitaciones,

                (
                    SELECT COUNT(DISTINCT r.id_reservas)
                    FROM detalle_reserva_hotel drh
                    JOIN hotel_habitaciones hh ON hh.id_hotel_habitacion = drh.rela_habitacion
                    JOIN detalle_reservas dr ON dr.id_detalle_reserva = drh.rela_detalle_reserva
                    JOIN reservas r ON r.id_reservas = dr.rela_reservas
                    WHERE hh.rela_hotel = h.id_hotel
                    AND r.reservas_estado IN ('pendiente','confirmada')
                    AND r.activo = 1
                ) AS total_reservas_activas

            FROM hotel h
            INNER JOIN ciudades c ON c.id_ciudad = h.rela_ciudad
            INNER JOIN provincias p ON p.id_provincia = h.rela_provincia
            WHERE h.rela_proveedor = $id_proveedor
            ORDER BY h.fecha_alta DESC
        ";

        return $conexion->consultar($query);
    }

    public function traer_hoteles_aprobados() {
        $conexion = new Conexion();
        $query = "
            SELECT h.id_hotel, h.hotel_nombre, h.imagen_principal, i.descripcion, 
                   h.rela_ciudad, h.estado_revision, h.motivo_rechazo
            FROM hotel h
            LEFT JOIN hoteles_info i ON i.rela_hotel = h.id_hotel
            WHERE h.activo = 1
            AND h.estado_revision = 'aprobado'
            ORDER BY h.fecha_alta DESC
        ";
        return $conexion->consultar($query);  
    }

    public function getId_hotel() { return $this->id_hotel; }
    public function setId_hotel($id) { $this->id_hotel = $id; return $this; }

    public function getHotel_nombre() { return $this->hotel_nombre; }
    public function setHotel_nombre($nombre) { $this->hotel_nombre = $nombre; return $this; }

    public function getImagen_principal() { return $this->imagen_principal; }
    public function setImagen_principal($imagen) { $this->imagen_principal = $imagen; return $this; }

    public function getRela_provincia() { return $this->rela_provincia; }
    public function setRela_provincia($provincia) { $this->rela_provincia = $provincia; return $this; }

    public function getRela_ciudad() { return $this->rela_ciudad; }
    public function setRela_ciudad($ciudad) { $this->rela_ciudad = $ciudad; return $this; }

    public function getRela_proveedor() { return $this->rela_proveedor; }
    public function setRela_proveedor($proveedor) { $this->rela_proveedor = $proveedor; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }

    public function getEstado_revision() { return $this->estado_revision; }
    public function setEstado_revision($estado) { $this->estado_revision = $estado; return $this; }

    public function getMotivo_rechazo() { return $this->motivo_rechazo; }
    public function setMotivo_rechazo($motivo) { $this->motivo_rechazo = $motivo; return $this; }

    public function getFecha_revision() { return $this->fecha_revision; }
    public function setFecha_revision($fecha) { $this->fecha_revision = $fecha; return $this; }

    public function getRevisado_por() { return $this->revisado_por; }
    public function setRevisado_por($revisor) { $this->revisado_por = $revisor; return $this; }
}
?>
