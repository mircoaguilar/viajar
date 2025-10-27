<?php
require_once('conexion.php');

class TipoDocumento {
    private $id_tipo_documento;
    private $nombre;
    private $activo;

    public function __construct($id_tipo_documento='', $nombre='', $activo=1) {
        $this->id_tipo_documento = $id_tipo_documento;
        $this->nombre = $nombre;
        $this->activo = $activo;
    }

    public function traer_tipos_documentos() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipos_documento WHERE activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_tipo_documento($id_tipo_documento) {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipos_documento WHERE id_tipo_documento = " . (int)$id_tipo_documento . " AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $nombre_esc = $conexion->getConexion()->real_escape_string($this->nombre);
        $query = "INSERT INTO tipos_documento (nombre, activo) VALUES ('$nombre_esc', 1)";
        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $nombre_esc = $conexion->getConexion()->real_escape_string($this->nombre);
        $query = "UPDATE tipos_documento SET nombre='$nombre_esc' WHERE id_tipo_documento=" . (int)$this->id_tipo_documento;
        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipos_documento SET activo=0 WHERE id_tipo_documento=" . (int)$this->id_tipo_documento;
        return $conexion->actualizar($query);
    }

    // Getters y setters
    public function getId_tipo_documento() { return $this->id_tipo_documento; }
    public function setId_tipo_documento($id_tipo_documento) { $this->id_tipo_documento = $id_tipo_documento; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
