<?php
require_once('conexion.php');

class EstadoReserva {
    private $id_estado_reserva;
    private $nombre_estado;
    private $activo;

    public function __construct($id_estado_reserva='', $nombre_estado='', $activo=1) {
        $this->id_estado_reserva = $id_estado_reserva;
        $this->nombre_estado = $nombre_estado;
        $this->activo = $activo;
    }

    public function traer_estados() {
        $conexion = new Conexion();
        $query = "SELECT * FROM estados_reserva WHERE activo = 1 ORDER BY nombre_estado ASC";
        return $conexion->consultar($query);
    }

    public function traer_estado($id_estado_reserva) {
        $conexion = new Conexion();
        $query = "SELECT * FROM estados_reserva WHERE id_estado_reserva = " . (int)$id_estado_reserva . " AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $nombre_esc = $conexion->getConexion()->real_escape_string($this->nombre_estado);
        $query = "INSERT INTO estados_reserva (nombre_estado, activo) VALUES ('$nombre_esc', 1)";
        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $nombre_esc = $conexion->getConexion()->real_escape_string($this->nombre_estado);
        $query = "UPDATE estados_reserva SET nombre_estado='$nombre_esc' WHERE id_estado_reserva=" . (int)$this->id_estado_reserva;
        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE estados_reserva SET activo=0 WHERE id_estado_reserva=" . (int)$this->id_estado_reserva;
        return $conexion->actualizar($query);
    }

    public function getId_estado_reserva() { return $this->id_estado_reserva; }
    public function setId_estado_reserva($id_estado_reserva) { $this->id_estado_reserva = $id_estado_reserva; return $this; }
    public function getNombre_estado() { return $this->nombre_estado; }
    public function setNombre_estado($nombre_estado) { $this->nombre_estado = $nombre_estado; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
