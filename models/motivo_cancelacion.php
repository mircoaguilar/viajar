<?php
require_once('conexion.php');

class MotivoCancelacion {
    private $id_motivo_cancelacion;
    private $descripcion;
    private $activo;

    public function __construct($id_motivo_cancelacion='', $descripcion='', $activo=1) {
        $this->id_motivo_cancelacion = $id_motivo_cancelacion;
        $this->descripcion = $descripcion;
        $this->activo = $activo;
    }

    public function traer_motivos() {
        $conexion = new Conexion();
        $query = "SELECT * FROM motivos_cancelacion WHERE activo = 1 ORDER BY descripcion ASC";
        return $conexion->consultar($query);
    }

    public function traer_motivo($id_motivo_cancelacion) {
        $conexion = new Conexion();
        $query = "SELECT * FROM motivos_cancelacion WHERE id_motivo_cancelacion = " . (int)$id_motivo_cancelacion . " AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $descripcion_esc = $conexion->getConexion()->real_escape_string($this->descripcion);
        $query = "INSERT INTO motivos_cancelacion (descripcion, activo) VALUES ('$descripcion_esc', 1)";
        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $descripcion_esc = $conexion->getConexion()->real_escape_string($this->descripcion);
        $query = "UPDATE motivos_cancelacion SET descripcion='$descripcion_esc' WHERE id_motivo_cancelacion=" . (int)$this->id_motivo_cancelacion;
        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE motivos_cancelacion SET activo=0 WHERE id_motivo_cancelacion=" . (int)$this->id_motivo_cancelacion;
        return $conexion->actualizar($query);
    }

    public function getId_motivo_cancelacion() { return $this->id_motivo_cancelacion; }
    public function setId_motivo_cancelacion($id_motivo_cancelacion) { $this->id_motivo_cancelacion = $id_motivo_cancelacion; return $this; }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}