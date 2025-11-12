<?php
require_once('conexion.php');
require_once('auditoria.php');

class Ciudad {
    private $id_ciudad;
    private $nombre;
    private $rela_provincia;
    private $activo;

    public function __construct($id_ciudad = '', $nombre = '', $rela_provincia = '') {
        $this->id_ciudad = $id_ciudad;
        $this->nombre = $nombre;
        $this->rela_provincia = $rela_provincia;
        $this->activo = 1;
    }

    public function traer_ciudades() {
        $conexion = new Conexion();
        $query = "SELECT c.id_ciudad, c.nombre, p.nombre AS provincia
                  FROM ciudades c
                  LEFT JOIN provincias p ON c.rela_provincia = p.id_provincia
                  WHERE c.activo = 1
                  ORDER BY c.nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_ciudad($id_ciudad) {
        $conexion = new Conexion();
        $id_ciudad = (int)$id_ciudad;
        $query = "SELECT * FROM ciudades WHERE id_ciudad = $id_ciudad AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre = $mysqli->real_escape_string($this->nombre);
        $provincia = (int)$this->rela_provincia;

        $query = "INSERT INTO ciudades (nombre, rela_provincia, activo) VALUES ('$nombre', $provincia, 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Alta de ciudad',
                "Se creó la ciudad: {$this->nombre} (Provincia ID: $provincia)"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $nombre = $mysqli->real_escape_string($this->nombre);
        $provincia = (int)$this->rela_provincia;

        $query = "UPDATE ciudades SET nombre='$nombre', rela_provincia=$provincia WHERE id_ciudad=".(int)$this->id_ciudad;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Actualización de ciudad',
                "Se actualizó la ciudad (ID: {$this->id_ciudad}) a nombre: '{$this->nombre}', provincia ID: $provincia"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE ciudades SET activo = 0 WHERE id_ciudad=".(int)$this->id_ciudad;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de ciudad',
                "Se eliminó lógicamente la ciudad (ID: {$this->id_ciudad}, nombre: {$this->nombre})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function traer_por_provincia($id_provincia) {
        $conexion = new Conexion();
        $id_provincia = (int)$id_provincia;
        return $conexion->consultar("SELECT id_ciudad, nombre FROM ciudades WHERE rela_provincia = $id_provincia AND activo = 1 ORDER BY nombre ASC");
    }


    public function getId_ciudad() { return $this->id_ciudad; }
    public function setId_ciudad($id) { $this->id_ciudad = $id; return $this; }
    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }
    public function getRela_provincia() { return $this->rela_provincia; }
    public function setRela_provincia($provincia) { $this->rela_provincia = $provincia; return $this; }
}
?>
