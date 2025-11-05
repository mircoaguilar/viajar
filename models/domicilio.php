<?php

require_once('conexion.php');

class Domicilio {
    private $id_domicilio;
    private $domicilio_descripcion;
    private $rela_personas;

    public function __construct($domicilio_descripcion = '', $rela_personas = '', $id_domicilio = '') {
        $this->domicilio_descripcion = $domicilio_descripcion;
        $this->rela_personas = $rela_personas;
        $this->id_domicilio = $id_domicilio;
    }

    public function guardar() {
        $conexion = new Conexion();
        $query = "INSERT INTO domicilio (domicilio_descripcion, rela_personas) VALUES (
            '" . $this->domicilio_descripcion . "',
            " . $this->rela_personas . "
        )";
        return $conexion->insertar($query); 
    }

}
?>