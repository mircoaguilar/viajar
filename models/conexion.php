<?php
class Conexion {
    private $con;
    private $servidor;
    private $usuario;
    private $password;
    private $base_datos;
    private $puerto;

    public function __construct() {
        $this->servidor = 'localhost';
        $this->usuario = 'root';
        $this->password = '';
        $this->base_datos = 'viajar';
        $this->puerto = 3306;
    }

    public function conectar() {
        if (!$this->con) {
            $this->con = new mysqli($this->servidor, $this->usuario, $this->password, $this->base_datos, $this->puerto);
            if ($this->con->connect_error) {
                die("Error de conexión: " . $this->con->connect_error);
            }
            $this->con->set_charset("utf8"); 
        }
        return $this->con;
    }

    public function getConexion() {
        return $this->conectar();
    }

    public function consultar($query) {
        $resultado = $this->conectar()->query($query);
        $data = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $data[] = $row;
            }
            $resultado->free();
        }
        return $data;
    }

    public function insertar($query) {
        $con = $this->conectar();
        $exito = $con->query($query);
        if ($exito) return $con->insert_id ?: $con->affected_rows;
        return false;
    }

    public function eliminar($query) {
        return $this->conectar()->query($query);
    }


    public function actualizar($query) {
        return $this->conectar()->query($query);
    }

    public function error() {
        return $this->conectar()->error;
    }

    public function ultimo_error() {
        return $this->conectar()->error;
    }
}
