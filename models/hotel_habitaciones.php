<?php
require_once('conexion.php');

class Hotel_Habitaciones {
    private $id_hotel_habitacion;
    private $rela_hotel;
    private $rela_tipo_habitacion;
    private $capacidad_maxima;
    private $precio_base_noche;
    private $descripcion;
    private $fotos;
    private $activo;
    private $fecha_creacion;

    public function __construct(
        $id_hotel_habitacion = '',
        $rela_hotel = '',
        $rela_tipo_habitacion = '',
        $capacidad_maxima = '',
        $precio_base_noche = '',
        $descripcion = '',
        $fotos = '[]'
    ) {
        $this->id_hotel_habitacion = $id_hotel_habitacion;
        $this->rela_hotel = $rela_hotel;
        $this->rela_tipo_habitacion = $rela_tipo_habitacion;
        $this->capacidad_maxima = $capacidad_maxima;
        $this->precio_base_noche = $precio_base_noche;
        $this->descripcion = $descripcion;
        $this->fotos = $fotos;
        $this->activo = 1;
    }

    public function traer_por_hotel($id_hotel) {
        $conexion = new Conexion();
        $id_hotel = (int)$id_hotel;

        $query = "SELECT hh.*, th.nombre AS tipo_nombre
                  FROM hotel_habitaciones hh
                  INNER JOIN tipos_habitacion th 
                        ON hh.rela_tipo_habitacion = th.id_tipo_habitacion
                  WHERE hh.rela_hotel = $id_hotel AND hh.activo = 1
                  ORDER BY hh.fecha_creacion ASC";

        $habitaciones = $conexion->consultar($query);

        foreach ($habitaciones as &$hab) {
            $hab['fotos'] = !empty($hab['fotos']) ? json_decode($hab['fotos'], true) : [];
        }

        return $habitaciones;
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $rela_hotel = (int)$this->rela_hotel;
        $tipo = (int)$this->rela_tipo_habitacion;
        $capacidad = (int)$this->capacidad_maxima;
        $precio = (float)$this->precio_base_noche;
        $descripcion = $mysqli->real_escape_string($this->descripcion);
        $fotos = $mysqli->real_escape_string($this->fotos); 

        $query = "INSERT INTO hotel_habitaciones 
                    (rela_hotel, rela_tipo_habitacion, capacidad_maxima, precio_base_noche, descripcion, fotos, activo, fecha_creacion)
                  VALUES
                    ($rela_hotel, $tipo, $capacidad, $precio, '$descripcion', '$fotos', 1, NOW())";

        return $conexion->insertar($query);
    }


    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $tipo = (int)$this->rela_tipo_habitacion;
        $capacidad = (int)$this->capacidad_maxima;
        $precio = (float)$this->precio_base_noche;
        $descripcion = $mysqli->real_escape_string($this->descripcion);
        $fotos = $mysqli->real_escape_string($this->fotos);

        $query = "UPDATE hotel_habitaciones SET 
                    rela_tipo_habitacion=$tipo,
                    capacidad_maxima=$capacidad,
                    precio_base_noche=$precio,
                    descripcion='$descripcion',
                    fotos='$fotos'
                  WHERE id_hotel_habitacion=" . (int)$this->id_hotel_habitacion;

        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE hotel_habitaciones 
                  SET activo = 0 
                  WHERE id_hotel_habitacion=" . (int)$this->id_hotel_habitacion;
        return $conexion->actualizar($query);
    }

    public function traer_por_id($id_habitacion) {
        $conexion = new Conexion();
        $id_habitacion = (int)$id_habitacion;

        $query = "SELECT hh.*, th.nombre AS tipo
                FROM hotel_habitaciones hh
                INNER JOIN tipos_habitacion th 
                        ON hh.rela_tipo_habitacion = th.id_tipo_habitacion
                WHERE hh.id_hotel_habitacion = $id_habitacion
                LIMIT 1";

        $habitaciones = $conexion->consultar($query);

        if (!empty($habitaciones)) {
            $hab = $habitaciones[0];
            $hab['fotos'] = !empty($hab['fotos']) ? json_decode($hab['fotos'], true) : [];
            return $hab;
        }

        return null;
    }

    public function disponibleEnFechas($id_habitacion, $checkin, $checkout) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id_habitacion = (int)$id_habitacion;
        $checkin = $mysqli->real_escape_string($checkin);
        $checkout = $mysqli->real_escape_string($checkout);
        $query = "
            SELECT SUM(cantidad_disponible) AS total_disponible
            FROM hotel_habitaciones_stock
            WHERE rela_habitacion = $id_habitacion
            AND fecha BETWEEN '$checkin' AND '$checkout'
            AND activo = 1
        ";
        $result = $conexion->consultar($query);
        if ($result[0]['total_disponible'] <= 0) {
            return false;
        }
        return true;
    }

    public function cambiar_estado($nuevo_estado = null) {
        $conexion = new Conexion();
        if ($nuevo_estado === null) {
            $nuevo_estado = (int)$this->activo;
        } else {
            $nuevo_estado = (int)$nuevo_estado;
        }
        $id = (int)$this->id_hotel_habitacion;

        $query = "UPDATE hotel_habitaciones 
                SET activo = $nuevo_estado
                WHERE id_hotel_habitacion = $id";

        return $conexion->actualizar($query);
    }


    public function getFotosArray() {
        return !empty($this->fotos) ? json_decode($this->fotos, true) : [];
    }

    public function setFotosArray(array $fotos) {
        $this->fotos = json_encode($fotos, JSON_UNESCAPED_SLASHES);
        return $this;
    }

    public function getId_hotel_habitacion() { return $this->id_hotel_habitacion; }
    public function setId_hotel_habitacion($id) { $this->id_hotel_habitacion = $id; return $this; }

    public function getRela_hotel() { return $this->rela_hotel; }
    public function setRela_hotel($id) { $this->rela_hotel = $id; return $this; }

    public function getRela_tipo_habitacion() { return $this->rela_tipo_habitacion; }
    public function setRela_tipo_habitacion($tipo) { $this->rela_tipo_habitacion = $tipo; return $this; }

    public function getCapacidad_maxima() { return $this->capacidad_maxima; }
    public function setCapacidad_maxima($cap) { $this->capacidad_maxima = $cap; return $this; }

    public function getPrecio_base_noche() { return $this->precio_base_noche; }
    public function setPrecio_base_noche($precio) { $this->precio_base_noche = $precio; return $this; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($desc) { $this->descripcion = $desc; return $this; }

    public function getFotos() { return $this->fotos; }
    public function setFotos($fotos) { $this->fotos = $fotos; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
