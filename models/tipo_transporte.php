<?php
require_once('conexion.php');

class TipoTransporte {
    private $id_tipo_transporte;
    private $descripcion;
    private $activo;

    public function __construct($id_tipo_transporte = '', $descripcion = '', $activo = 1) {
        $this->id_tipo_transporte = $id_tipo_transporte;
        $this->descripcion = $descripcion;
        $this->activo = $activo;
    }

    // Traer todos los tipos de transporte
    public function traer_tipos_transportes() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_transporte WHERE activo = 1 ORDER BY descripcion ASC";
        return $conexion->consultar($query);
    }

    // Traer un tipo de transporte por su ID
    public function traer_tipo_transporte($id_tipo_transporte) {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_transporte WHERE id_tipo_transporte = " . (int)$id_tipo_transporte . " AND activo = 1";
        return $conexion->consultar($query);
    }

    // Guardar un nuevo tipo de transporte
    public function guardar() {
        $conexion = new Conexion();
        $descripcion_escapada = $conexion->getConexion()->real_escape_string($this->descripcion);
        $query = "INSERT INTO tipo_transporte (descripcion, activo) VALUES ('$descripcion_escapada', 1)";
        return $conexion->insertar($query);
    }

    // Actualizar un tipo de transporte existente
    public function actualizar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion(); 
        $descripcion_escapada = $mysqli_connection->real_escape_string($this->descripcion);
        $query = "UPDATE tipo_transporte SET descripcion = '$descripcion_escapada' WHERE id_tipo_transporte = " . (int)$this->id_tipo_transporte;
        return $conexion->actualizar($query);
    }

    // Eliminar un tipo de transporte de forma lógica
    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipo_transporte SET activo = 0 WHERE id_tipo_transporte = " . (int)$this->id_tipo_transporte;
        return $conexion->actualizar($query);
    }

    // Métodos getter y setter
    public function getId_tipo_transporte() {
        return $this->id_tipo_transporte;
    }

    public function setId_tipo_transporte($id_tipo_transporte) {
        $this->id_tipo_transporte = $id_tipo_transporte;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
    }
}
