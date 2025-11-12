<?php
require_once('conexion.php');
require_once('auditoria.php');

class TipoTransporte {
    private $id_tipo_transporte;
    private $descripcion;
    private $activo;

    public function __construct($id_tipo_transporte = '', $descripcion = '', $activo = 1) {
        $this->id_tipo_transporte = $id_tipo_transporte;
        $this->descripcion = $descripcion;
        $this->activo = $activo;
    }

    public function traer_tipos_transportes() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_transporte WHERE activo = 1 ORDER BY descripcion ASC";
        return $conexion->consultar($query);
    }

    public function traer_tipo_transporte($id_tipo_transporte) {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_transporte WHERE id_tipo_transporte = " . (int)$id_tipo_transporte . " AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $descripcion_esc = $mysqli->real_escape_string($this->descripcion);

        $query = "INSERT INTO tipo_transporte (descripcion, activo) VALUES ('$descripcion_esc', 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de tipo de transporte',
                "Se creó el tipo de transporte: {$this->descripcion}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $descripcion_esc = $mysqli->real_escape_string($this->descripcion);

        $query = "UPDATE tipo_transporte SET descripcion='$descripcion_esc' WHERE id_tipo_transporte=" . (int)$this->id_tipo_transporte;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de tipo de transporte',
                "Se actualizó el tipo de transporte (ID: {$this->id_tipo_transporte}) a: {$this->descripcion}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipo_transporte SET activo=0 WHERE id_tipo_transporte=" . (int)$this->id_tipo_transporte;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de tipo de transporte',
                "Se eliminó lógicamente el tipo de transporte (ID: {$this->id_tipo_transporte})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

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
