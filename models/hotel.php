<?php
require_once('conexion.php');
require_once('proveedor.php');

class Hotel {
    private $id_hotel;
    private $hotel_nombre;
    private $fecha_alta;
    private $imagen_principal;
    private $rela_provincia;
    private $rela_ciudad;
    private $rela_proveedor;
    private $activo;
    private $estado_revision;
    private $motivo_rechazo;
    private $fecha_revision;
    private $revisado_por;

    public function __construct($id_hotel = '', $hotel_nombre = '', $imagen_principal = '', $rela_provincia = '', $rela_ciudad = '', $rela_proveedor = '') {
        $this->id_hotel = $id_hotel;
        $this->hotel_nombre = $hotel_nombre;
        $this->imagen_principal = $imagen_principal;
        $this->rela_provincia = $rela_provincia;
        $this->rela_ciudad = $rela_ciudad;
        $this->rela_proveedor = $rela_proveedor;
        $this->activo = 1;
        $this->estado_revision = 'pendiente';
        $this->motivo_rechazo = null;
        $this->fecha_revision = null;
        $this->revisado_por = null;
    }

    public function traer_hoteles() {
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

    public function traer_hotel($id_hotel) {
        $conexion = new Conexion();
        $id_hotel = (int)$id_hotel;
        $query = "SELECT * FROM hotel WHERE id_hotel = $id_hotel AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $nombre_escapado = $mysqli->real_escape_string($this->hotel_nombre);
        $imagen_escapada = $mysqli->real_escape_string($this->imagen_principal);
        $provincia = (int)$this->rela_provincia;
        $ciudad = (int)$this->rela_ciudad;

        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($_SESSION['id_usuarios']);
        if (!$proveedor) {
            throw new Exception("No se encontró proveedor asociado al usuario.");
        }
        $rela_proveedor = (int)$proveedor['id_proveedores'];

        $query = "INSERT INTO hotel 
                    (hotel_nombre, imagen_principal, rela_provincia, rela_ciudad, rela_proveedor, 
                     activo, estado_revision, fecha_alta)
                  VALUES 
                    ('$nombre_escapado', '$imagen_escapada', $provincia, $ciudad, $rela_proveedor, 
                     1, 'pendiente', NOW())";

        $id_hotel = $conexion->insertar($query);

        if (!$id_hotel) return false;

        $this->id_hotel = $id_hotel;
        return $id_hotel;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $nombre_escapado = $mysqli->real_escape_string($this->hotel_nombre);
        $imagen_escapada = $this->imagen_principal ? ", imagen_principal='" . $mysqli->real_escape_string($this->imagen_principal) . "'" : "";
        $provincia = (int)$this->rela_provincia;
        $ciudad = (int)$this->rela_ciudad;

        $query = "UPDATE hotel SET 
                    hotel_nombre='$nombre_escapado',
                    rela_provincia=$provincia,
                    rela_ciudad=$ciudad
                    $imagen_escapada
                  WHERE id_hotel=" . (int)$this->id_hotel;

        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE hotel SET activo = 0 WHERE id_hotel=" . (int)$this->id_hotel;
        return $conexion->actualizar($query);
    }

    public function traer_hoteles_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $id_usuario = (int)$id_usuario;
        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
        if (!$proveedor) return [];

        $id_proveedor = (int)$proveedor['id_proveedores'];
        $query = "
            SELECT h.id_hotel, 
                   h.hotel_nombre, 
                   h.imagen_principal, 
                   i.descripcion, 
                   c.nombre AS ciudad_nombre,
                   p.nombre AS provincia_nombre,
                   h.estado_revision,
                   h.motivo_rechazo
            FROM hotel h
            LEFT JOIN hoteles_info i ON i.rela_hotel = h.id_hotel
            INNER JOIN ciudades c ON h.rela_ciudad = c.id_ciudad
            INNER JOIN provincias p ON h.rela_provincia = p.id_provincia
            WHERE h.rela_proveedor = $id_proveedor
            ORDER BY h.fecha_alta DESC
        ";
        return $conexion->consultar($query);
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
        $resultado = $conexion->consultar($query);
        return ($resultado[0]['cuenta'] > 0);
    }

    public function buscar($destino, $desde, $hasta) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $destino = $mysqli->real_escape_string($destino);

        $query = "
            SELECT h.id_hotel, h.hotel_nombre, h.imagen_principal, i.descripcion, h.rela_ciudad
            FROM hotel h
            LEFT JOIN hoteles_info i ON i.rela_hotel = h.id_hotel
            INNER JOIN ciudades c ON h.rela_ciudad = c.id_ciudad
            WHERE h.activo=1 AND h.estado_revision='aprobado'
        ";

        if ($destino) {
            $query .= " AND c.nombre LIKE '%$destino%'";
        }

        $query .= " ORDER BY h.fecha_alta DESC";

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
