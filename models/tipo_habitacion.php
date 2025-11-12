<?php
require_once('conexion.php');
require_once('auditoria.php');

class TipoHabitacion {
    private $id_tipo_habitacion;
    private $nombre;
    private $descripcion;
    private $capacidad;
    private $activo;

    public function __construct($id_tipo_habitacion = '', $nombre = '', $descripcion = '', $capacidad = 1) {
        $this->id_tipo_habitacion = $id_tipo_habitacion;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->capacidad = $capacidad;
        $this->activo = 1;
    }

    public function traer_tipos_habitaciones() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipos_habitacion WHERE activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_tipo_habitacion($id_tipo_habitacion) {
        $conexion = new Conexion();
        $id_tipo_habitacion = (int)$id_tipo_habitacion;
        $query = "SELECT id_tipo_habitacion, nombre, descripcion, capacidad 
                  FROM tipos_habitacion 
                  WHERE id_tipo_habitacion = $id_tipo_habitacion AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);
        $descripcion_esc = $mysqli->real_escape_string($this->descripcion);
        $capacidad_int = (int)$this->capacidad;

        $query = "INSERT INTO tipos_habitacion (nombre, descripcion, capacidad, activo) 
                  VALUES ('$nombre_esc', '$descripcion_esc', $capacidad_int, 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de tipo de habitación',
                "Se creó la habitación: {$this->nombre}, capacidad: {$this->capacidad}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);
        $descripcion_esc = $mysqli->real_escape_string($this->descripcion);
        $capacidad_int = (int)$this->capacidad;

        $query = "UPDATE tipos_habitacion 
                  SET nombre='$nombre_esc', descripcion='$descripcion_esc', capacidad=$capacidad_int 
                  WHERE id_tipo_habitacion=" . (int)$this->id_tipo_habitacion;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de tipo de habitación',
                "Se actualizó la habitación (ID: {$this->id_tipo_habitacion}) a nombre: {$this->nombre}, capacidad: {$this->capacidad}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipos_habitacion SET activo = 0 WHERE id_tipo_habitacion=" . (int)$this->id_tipo_habitacion;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de tipo de habitación',
                "Se eliminó lógicamente la habitación (ID: {$this->id_tipo_habitacion})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function getId_tipo_habitacion() { return $this->id_tipo_habitacion; }
    public function setId_tipo_habitacion($id) { $this->id_tipo_habitacion = $id; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
    public function getCapacidad() { return $this->capacidad; }
    public function setCapacidad($capacidad) { $this->capacidad = $capacidad; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
?>
