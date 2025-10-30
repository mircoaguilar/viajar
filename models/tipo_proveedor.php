<?php
require_once('conexion.php');

class Tipo_proveedor {
    private $id_tipo_proveedor;
    private $nombre;
    private $descripcion;
    private $activo;

    public function __construct($id_tipo_proveedor = '', $nombre = '', $descripcion = '') {
        $this->id_tipo_proveedor = $id_tipo_proveedor;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->activo = 1;
    }

    public function traer_tipos_proveedores() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_proveedores WHERE activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_tipo_proveedor($id_tipo_proveedor) {
        $conexion = new Conexion();
        $id_tipo_proveedor = (int)$id_tipo_proveedor;
        $query = "SELECT id_tipo_proveedor, nombre, descripcion FROM tipo_proveedores WHERE id_tipo_proveedor = $id_tipo_proveedor AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion();
        $nombre_escapado = $mysqli_connection->real_escape_string($this->nombre);
        $descripcion_escapada = $mysqli_connection->real_escape_string($this->descripcion);
        $query = "INSERT INTO tipo_proveedores (nombre, descripcion, activo) VALUES ('$nombre_escapado', '$descripcion_escapada', 1)";
        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion();
        $nombre_escapado = $mysqli_connection->real_escape_string($this->nombre);
        $descripcion_escapada = $mysqli_connection->real_escape_string($this->descripcion);
        $query = "UPDATE tipo_proveedores SET nombre='$nombre_escapado', descripcion='$descripcion_escapada' WHERE id_tipo_proveedor=" . (int)$this->id_tipo_proveedor;
        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipo_proveedores SET activo = 0 WHERE id_tipo_proveedor = " . (int)$this->id_tipo_proveedor;
        return $conexion->actualizar($query);
    }

    public function getId_tipo_proveedor() { return $this->id_tipo_proveedor; }
    public function setId_tipo_proveedor($id) { $this->id_tipo_proveedor = $id; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
?>
