<?php
require_once('conexion.php');
require_once('auditoria.php');

class Temporada {
    private $id_temporada;
    private $nombre;
    private $fecha_inicio;
    private $fecha_fin;
    private $activo;

    public function __construct($id_temporada = '', $nombre = '', $fecha_inicio = '', $fecha_fin = '', $activo = 1) {
        $this->id_temporada = $id_temporada;
        $this->nombre = $nombre;
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->activo = $activo;
    }

    public function traer_temporadas() {
        $conexion = new Conexion();
        $query = "SELECT * FROM temporadas WHERE activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_temporada($id_temporada) {
        $conexion = new Conexion();
        $query = "SELECT * FROM temporadas WHERE id_temporada = " . (int)$id_temporada . " AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);
        $fecha_inicio_esc = $mysqli->real_escape_string($this->fecha_inicio);
        $fecha_fin_esc = $mysqli->real_escape_string($this->fecha_fin);

        $query = "INSERT INTO temporadas (nombre, fecha_inicio, fecha_fin, activo) 
                  VALUES ('$nombre_esc', '$fecha_inicio_esc', '$fecha_fin_esc', 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de temporada',
                "Se creó la temporada: {$this->nombre} ({$this->fecha_inicio} a {$this->fecha_fin})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);
        $fecha_inicio_esc = $mysqli->real_escape_string($this->fecha_inicio);
        $fecha_fin_esc = $mysqli->real_escape_string($this->fecha_fin);

        $query = "UPDATE temporadas 
                  SET nombre='$nombre_esc', fecha_inicio='$fecha_inicio_esc', fecha_fin='$fecha_fin_esc' 
                  WHERE id_temporada=" . (int)$this->id_temporada;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de temporada',
                "Se actualizó la temporada (ID: {$this->id_temporada}) a: {$this->nombre} ({$this->fecha_inicio} a {$this->fecha_fin})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE temporadas SET activo=0 WHERE id_temporada=" . (int)$this->id_temporada;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de temporada',
                "Se eliminó lógicamente la temporada (ID: {$this->id_temporada})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function getId_temporada() { return $this->id_temporada; }
    public function setId_temporada($id_temporada) { $this->id_temporada = $id_temporada; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getFecha_inicio() { return $this->fecha_inicio; }
    public function setFecha_inicio($fecha_inicio) { $this->fecha_inicio = $fecha_inicio; return $this; }
    public function getFecha_fin() { return $this->fecha_fin; }
    public function setFecha_fin($fecha_fin) { $this->fecha_fin = $fecha_fin; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
