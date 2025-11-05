<?php
require_once('conexion.php');

class Contacto {
    private $contacto_descripcion;
    private $rela_personas;
    private $rela_tipo_contacto;

    public function __construct($contacto_descripcion = '', $rela_personas = '', $rela_tipo_contacto = '') {
        $this->contacto_descripcion = $contacto_descripcion;
        $this->rela_personas = $rela_personas;
        $this->rela_tipo_contacto = $rela_tipo_contacto;
    }

    public function guardar() {
        $conexion = new Conexion();
        $query = "INSERT INTO contacto (contacto_descripcion, rela_personas, rela_tipo_contacto) VALUES ('$this->contacto_descripcion', '$this->rela_personas', '$this->rela_tipo_contacto')";
        return $conexion->insertar($query);
    }
}