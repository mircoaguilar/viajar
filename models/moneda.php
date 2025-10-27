<?php
require_once('conexion.php');

class Moneda {
    private $id_moneda;
    private $nombre;
    private $simbolo;
    private $activo;

    public function __construct($id_moneda='', $nombre='', $simbolo='', $activo=1) {
        $this->id_moneda = $id_moneda;
        $this->nombre = $nombre;
        $this->simbolo = $simbolo;
        $this->activo = $activo;
    }

    public function traer_monedas() {
        $conexion = new Conexion();
        $query = "SELECT * FROM monedas WHERE activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_moneda($id_moneda) {
        $conexion = new Conexion();
        $query = "SELECT * FROM monedas WHERE id_moneda = " . (int)$id_moneda . " AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $nombre_esc = $conexion->getConexion()->real_escape_string($this->nombre);
        $simbolo_esc = $conexion->getConexion()->real_escape_string($this->simbolo);
        $query = "INSERT INTO monedas (nombre, simbolo, activo) VALUES ('$nombre_esc', '$simbolo_esc', 1)";
        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $nombre_esc = $conexion->getConexion()->real_escape_string($this->nombre);
        $simbolo_esc = $conexion->getConexion()->real_escape_string($this->simbolo);
        $query = "UPDATE monedas SET nombre='$nombre_esc', simbolo='$simbolo_esc' WHERE id_moneda=" . (int)$this->id_moneda;
        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE monedas SET activo=0 WHERE id_moneda=" . (int)$this->id_moneda;
        return $conexion->actualizar($query);
    }

    // Getters y setters
    public function getId_moneda() { return $this->id_moneda; }
    public function setId_moneda($id_moneda) { $this->id_moneda = $id_moneda; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getSimbolo() { return $this->simbolo; }
    public function setSimbolo($simbolo) { $this->simbolo = $simbolo; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
