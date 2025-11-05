<?php
require_once('conexion.php');

class TransportePiso {
    private $id_piso;
    private $rela_transporte;
    private $numero_piso;
    private $filas;
    private $asientos_por_fila;

    public function __construct(
        $id_piso = '',
        $rela_transporte = '',
        $numero_piso = '',
        $filas = '',
        $asientos_por_fila = ''
    ) {
        $this->id_piso = $id_piso;
        $this->rela_transporte = $rela_transporte;
        $this->numero_piso = $numero_piso;
        $this->filas = $filas;
        $this->asientos_por_fila = $asientos_por_fila;
    }

    public function traer_pisos_por_transporte($id_transporte) {
        $conexion = new Conexion();
        $id_transporte = (int)$id_transporte;
        $query = "SELECT * FROM transporte_pisos WHERE rela_transporte = $id_transporte ORDER BY numero_piso ASC";
        return $conexion->consultar($query);
    }

    public function traer_piso($id_piso) {
        $conexion = new Conexion();
        $id_piso = (int)$id_piso;
        $query = "SELECT * FROM transporte_pisos WHERE id_piso = $id_piso LIMIT 1";
        $res = $conexion->consultar($query);
        return $res ? $res[0] : null;
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $rela_transporte = (int)$this->rela_transporte;
        $numero_piso = (int)$this->numero_piso;
        $filas = (int)$this->filas;
        $asientos_por_fila = (int)$this->asientos_por_fila;

        $query = "INSERT INTO transporte_pisos
                    (rela_transporte, numero_piso, filas, asientos_por_fila)
                  VALUES
                    ($rela_transporte, $numero_piso, $filas, $asientos_por_fila)";
        
        if ($mysqli->query($query)) {
            return $mysqli->insert_id;
        } else {
            return false;
        }
    }
    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_piso = (int)$this->id_piso;
        $numero_piso = (int)$this->numero_piso;
        $filas = (int)$this->filas;
        $asientos_por_fila = (int)$this->asientos_por_fila;

        $query = "UPDATE transporte_pisos SET
                    numero_piso = $numero_piso,
                    filas = $filas,
                    asientos_por_fila = $asientos_por_fila
                  WHERE id_piso = $id_piso";

        return $conexion->actualizar($query);
    }
    
    public function eliminar() {
        $conexion = new Conexion();
        $id_piso = (int)$this->id_piso;
        $query = "DELETE FROM transporte_pisos WHERE id_piso = $id_piso";
        return $conexion->actualizar($query);
    }

    public function getId_piso() { return $this->id_piso; }
    public function setId_piso($id) { $this->id_piso = $id; return $this; }

    public function getRela_transporte() { return $this->rela_transporte; }
    public function setRela_transporte($id) { $this->rela_transporte = $id; return $this; }

    public function getNumero_piso() { return $this->numero_piso; }
    public function setNumero_piso($num) { $this->numero_piso = $num; return $this; }

    public function getFilas() { return $this->filas; }
    public function setFilas($f) { $this->filas = $f; return $this; }

    public function getAsientos_por_fila() { return $this->asientos_por_fila; }
    public function setAsientos_por_fila($a) { $this->asientos_por_fila = $a; return $this; }
}
?>
