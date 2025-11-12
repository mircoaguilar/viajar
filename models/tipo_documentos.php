<?php
require_once('conexion.php');
require_once('auditoria.php');

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
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);

        $query = "INSERT INTO tipos_documento (nombre, activo) VALUES ('$nombre_esc', 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de tipo de documento',
                "Se creó el tipo de documento: {$this->nombre}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);

        $query = "UPDATE tipos_documento SET nombre='$nombre_esc' WHERE id_tipo_documento=" . (int)$this->id_tipo_documento;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de tipo de documento',
                "Se actualizó el tipo de documento (ID: {$this->id_tipo_documento}) a: {$this->nombre}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipos_documento SET activo=0 WHERE id_tipo_documento=" . (int)$this->id_tipo_documento;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de tipo de documento',
                "Se eliminó lógicamente el tipo de documento (ID: {$this->id_tipo_documento})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function getId_tipo_documento() { return $this->id_tipo_documento; }
    public function setId_tipo_documento($id_tipo_documento) { $this->id_tipo_documento = $id_tipo_documento; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
