<?php
require_once('conexion.php');
require_once('auditoria.php');

class Tipo_proveedor {
    private $id_tipo_proveedor;
    private $nombre;
    private $descripcion;
    private $activo;

    public function __construct($id_tipo_proveedor = '', $nombre = '', $descripcion = '') {
        $this->id_tipo_proveedor = $id_tipo_proveedor;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->activo = 1;
    }

    public function traer_tipos_proveedores() {
        $conexion = new Conexion();
        $query = "SELECT * FROM tipo_proveedores WHERE activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_tipo_proveedor($id_tipo_proveedor) {
        $conexion = new Conexion();
        $id_tipo_proveedor = (int)$id_tipo_proveedor;
        $query = "SELECT id_tipo_proveedor, nombre, descripcion 
                  FROM tipo_proveedores 
                  WHERE id_tipo_proveedor = $id_tipo_proveedor AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);
        $descripcion_esc = $mysqli->real_escape_string($this->descripcion);

        $query = "INSERT INTO tipo_proveedores (nombre, descripcion, activo) VALUES ('$nombre_esc', '$descripcion_esc', 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de tipo de proveedor',
                "Se creó el tipo de proveedor: {$this->nombre}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre_esc = $mysqli->real_escape_string($this->nombre);
        $descripcion_esc = $mysqli->real_escape_string($this->descripcion);

        $query = "UPDATE tipo_proveedores 
                  SET nombre='$nombre_esc', descripcion='$descripcion_esc' 
                  WHERE id_tipo_proveedor=" . (int)$this->id_tipo_proveedor;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de tipo de proveedor',
                "Se actualizó el tipo de proveedor (ID: {$this->id_tipo_proveedor}) a nombre: {$this->nombre}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE tipo_proveedores SET activo=0 WHERE id_tipo_proveedor=" . (int)$this->id_tipo_proveedor;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de tipo de proveedor',
                "Se eliminó lógicamente el tipo de proveedor (ID: {$this->id_tipo_proveedor})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function getId_tipo_proveedor() { return $this->id_tipo_proveedor; }
    public function setId_tipo_proveedor($id) { $this->id_tipo_proveedor = $id; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
?>
