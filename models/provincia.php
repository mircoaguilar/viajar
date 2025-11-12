<?php
require_once('conexion.php');
require_once('auditoria.php');

class Provincia {
    private $id_provincia;
    private $nombre;
    private $activo;

    public function __construct($id_provincia = '', $nombre = '') {
        $this->id_provincia = $id_provincia;
        $this->nombre = $nombre;
        $this->activo = 1;
    }

    public function traer_provincias() {
        $conexion = new Conexion();
        $query = "SELECT * FROM provincias WHERE activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_provincia($id_provincia) {
        $conexion = new Conexion();
        $id_provincia = (int)$id_provincia;
        $query = "SELECT id_provincia, nombre FROM provincias WHERE id_provincia = $id_provincia AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion();
        $nombre_escapado = $mysqli_connection->real_escape_string($this->nombre);

        $query = "INSERT INTO provincias (nombre, activo) VALUES ('$nombre_escapado', 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de provincia',
                "Se creó la provincia: {$this->nombre}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion();
        $nombre_escapado = $mysqli_connection->real_escape_string($this->nombre);

        $query = "UPDATE provincias SET nombre = '$nombre_escapado' WHERE id_provincia = ".(int)$this->id_provincia;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de provincia',
                "Se actualizó la provincia (ID: {$this->id_provincia}) a '{$this->nombre}'"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE provincias SET activo = 0 WHERE id_provincia = ".(int)$this->id_provincia;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de provincia',
                "Se eliminó lógicamente la provincia (ID: {$this->id_provincia})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function traer_ciudades_por_provincia($id_provincia) {
        $conexion = new Conexion();
        $id_provincia = (int)$id_provincia;
        $query = "SELECT * FROM ciudades WHERE rela_provincia = $id_provincia AND activo = 1 ORDER BY nombre ASC";
        return $conexion->consultar($query);
    }



    /**
     * Get the value of id_provincia
     */ 
    public function getId_provincia()
    {
        return $this->id_provincia;
    }

    /**
     * Set the value of id_provincia
     *
     * @return  self
     */ 
    public function setId_provincia($id_provincia)
    {
        $this->id_provincia = $id_provincia;

        return $this;
    }

    /**
     * Get the value of nombre
     */ 
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */ 
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }
}
?>
