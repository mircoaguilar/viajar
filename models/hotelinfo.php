<?php
require_once('conexion.php');

class HotelInfo {
    private $id_info;
    private $rela_hotel;
    private $direccion;
    private $descripcion;
    private $servicios;
    private $politicas_cancelacion;
    private $reglas;
    private $fotos;

    private $db;

    public function __construct() {
        $this->db = new Conexion();
    }

    public function guardar() {
        $fotos_json = !empty($this->fotos) ? $this->fotos : json_encode([]);
        $query = "INSERT INTO hoteles_info 
            (rela_hotel, direccion, descripcion, servicios, politicas_cancelacion, reglas, fotos)
            VALUES (
                '{$this->rela_hotel}',
                '{$this->direccion}',
                '{$this->descripcion}',
                '{$this->servicios}',
                '{$this->politicas_cancelacion}',
                '{$this->reglas}',
                '{$fotos_json}'
            )";
        return $this->db->insertar($query);
    }

    public function actualizar() {
        $fotos_json = $this->fotos !== null ? $this->fotos : null;
        $query = "UPDATE hoteles_info SET
            direccion='{$this->direccion}',
            descripcion='{$this->descripcion}',
            servicios='{$this->servicios}',
            politicas_cancelacion='{$this->politicas_cancelacion}',
            reglas='{$this->reglas}',
            fotos='{$fotos_json}'
            WHERE rela_hotel='{$this->rela_hotel}'";
        return $this->db->actualizar($query);
    }

    public function traer_por_hotel($id_hotel) {
        return $this->db->consultar("
            SELECT * FROM hoteles_info 
            WHERE rela_hotel = '{$id_hotel}'
            LIMIT 1
        ");
    }

    public function setId_info($id) { $this->id_info = $id; return $this; }
    public function setRela_hotel($id) { $this->rela_hotel = $id; return $this; }
    public function setDireccion($direccion) { $this->direccion = $direccion; return $this; }
    public function setDescripcion($desc) { $this->descripcion = $desc; return $this; }
    public function setServicios($serv) { $this->servicios = $serv; return $this; }
    public function setPoliticas_cancelacion($pol) { $this->politicas_cancelacion = $pol; return $this; }
    public function setReglas($reg) { $this->reglas = $reg; return $this; }
    public function setFotos($fotos) { $this->fotos = $fotos; return $this; }
}
?>
