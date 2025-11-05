<?php


require_once('conexion.php'); 

class Tipo_pago {
    private $id_tipo_pago;
    private $tipo_pago_descripcion;
    private $activo; 

    public function __construct($id_tipo_pago = '', $tipo_pago_descripcion = '') {
        $this->id_tipo_pago = $id_tipo_pago;
        $this->tipo_pago_descripcion = $tipo_pago_descripcion;
        $this->activo = 1;
    }

    public function traer_tipos_pagos() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_pago WHERE activo = 1 ORDER BY tipo_pago_descripcion ASC";
        return $conexion->consultar($query);
    }

    public function traer_tipo_pago($id_tipo_pago) {
        $conexion = new Conexion();
        $id_tipo_pago = (int)$id_tipo_pago;
        $query = "SELECT id_tipo_pago, tipo_pago_descripcion FROM tipo_pago WHERE id_tipo_pago = $id_tipo_pago AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion(); 
        $descripcion_escapada = $mysqli_connection->real_escape_string($this->tipo_pago_descripcion);
        $query = "INSERT INTO tipo_pago (tipo_pago_descripcion, activo) VALUES ('$descripcion_escapada', 1)";
        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion();
        $descripcion_escapada = $mysqli_connection->real_escape_string($this->tipo_pago_descripcion);
        $query = "UPDATE tipo_pago SET tipo_pago_descripcion = '" . $descripcion_escapada . "' WHERE id_tipo_pago = " . (int)$this->id_tipo_pago;
        return $conexion->actualizar($query);

    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipo_pago SET activo = 0 WHERE id_tipo_pago = " . (int)$this->id_tipo_pago;
        return $conexion->actualizar($query); 
    }


    public function getId_tipo_pago() {
        return $this->id_tipo_pago;
    }

    public function setId_tipo_pago($id_tipo_pago) {
        $this->id_tipo_pago = $id_tipo_pago;
        return $this;
    }

    public function getTipo_pago_descripcion() {
        return $this->tipo_pago_descripcion;
    }

    public function setTipo_pago_descripcion($tipo_pago_descripcion) {
        $this->tipo_pago_descripcion = $tipo_pago_descripcion;
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