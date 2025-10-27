<?php

require_once('conexion.php'); 

class Tipo_contacto {
    private $id_tipo_contacto;
    private $tipo_contacto_descripcion;
    private $activo;

    public function __construct($id_tipo_contacto = '', $tipo_contacto_descripcion = '') {
        $this->id_tipo_contacto = $id_tipo_contacto;
        $this->tipo_contacto_descripcion = $tipo_contacto_descripcion;
        $this->activo = 1; 
    }

    public function traer_tipos_contactos() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_contacto WHERE activo = 1 ORDER BY tipo_contacto_descripcion ASC";
        return $conexion->consultar($query);
    }

    public function traer_tipo_contacto($id_tipo_contacto) {
        $conexion = new Conexion();
        $id_tipo_contacto = (int)$id_tipo_contacto;
        $query = "SELECT id_tipo_contacto, tipo_contacto_descripcion FROM tipo_contacto WHERE id_tipo_contacto = $id_tipo_contacto AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion(); 
        $descripcion_escapada = $mysqli_connection->real_escape_string($this->tipo_contacto_descripcion);
        $query = "INSERT INTO tipo_contacto (tipo_contacto_descripcion, activo) VALUES ('$descripcion_escapada', 1)";
        return $conexion->insertar($query); 
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion(); 
        $descripcion_escapada = $mysqli_connection->real_escape_string($this->tipo_contacto_descripcion);
        $query = "UPDATE tipo_contacto SET tipo_contacto_descripcion = '" . $descripcion_escapada . "' WHERE id_tipo_contacto = " . (int)$this->id_tipo_contacto;
        return $conexion->actualizar($query); 
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipo_contacto SET activo = 0 WHERE id_tipo_contacto = " . (int)$this->id_tipo_contacto;
        return $conexion->actualizar($query); 
    }


    public function getId_tipo_contacto() {
        return $this->id_tipo_contacto;
    }

    public function setId_tipo_contacto($id_tipo_contacto) {
        $this->id_tipo_contacto = $id_tipo_contacto;
        return $this;
    }

    public function getTipo_contacto_descripcion() {
        return $this->tipo_contacto_descripcion;
    }

    public function setTipo_contacto_descripcion($tipo_contacto_descripcion) {
        $this->tipo_contacto_descripcion = $tipo_contacto_descripcion;
        return $this;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
        return $this;
    }
}
?>