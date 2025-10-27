<?php

require_once('conexion.php'); 

class Persona {
    private $id_personas;
    private $personas_nombre;
    private $personas_apellido;
    private $personas_dni;
    private $personas_fecha_nac;
    private $activo; 

    public function __construct($id_personas = '', $personas_nombre = '', $personas_apellido = '', $personas_dni = '', $personas_fecha_nac = '', $activo = 1) { // <-- Valor por defecto 1
        $this->id_personas = $id_personas;
        $this->personas_nombre = $personas_nombre;
        $this->personas_apellido = $personas_apellido;
        $this->personas_dni = $personas_dni;
        $this->personas_fecha_nac = $personas_fecha_nac;
        $this->activo = $activo; 
    }

    public function guardar() {
        $conexion = new Conexion(); 

        $query = "INSERT INTO personas (personas_nombre, personas_apellido, personas_dni, personas_fecha_nac, activo) VALUES (
            '" . $this->personas_nombre . "',
            '" . $this->personas_apellido . "',
            '" . $this->personas_dni . "',
            '" . $this->personas_fecha_nac . "',
            " . $this->activo . "
        )";
        
        return $conexion->insertar($query);
    }

    public function traer_personas() {
        $conexion = new Conexion();
        $query = "SELECT * FROM personas WHERE activo = 1";
        return $conexion->consultar($query);
    }

    public function traer_persona_por_id($id) {
        $conexion = new Conexion();
        $query = "SELECT * FROM personas WHERE id_personas = $id AND activo = 1";
        return $conexion->consultar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $query = "UPDATE personas SET 
                    personas_nombre = '$this->personas_nombre',
                    personas_apellido = '$this->personas_apellido',
                    personas_dni = '$this->personas_dni'
                WHERE id_personas = $this->id_personas";
        return $conexion->actualizar($query);
    }


    /**
     * Get the value of id_personas
     */ 
    public function getId_personas()
    {
        return $this->id_personas;
    }

    /**
     * Set the value of id_personas
     *
     * @return  self
     */ 
    public function setId_personas($id_personas)
    {
        $this->id_personas = $id_personas;

        return $this;
    }

    /**
     * Get the value of personas_nombre
     */ 
    public function getPersonas_nombre()
    {
        return $this->personas_nombre;
    }

    /**
     * Set the value of personas_nombre
     *
     * @return  self
     */ 
    public function setPersonas_nombre($personas_nombre)
    {
        $this->personas_nombre = $personas_nombre;

        return $this;
    }

    /**
     * Get the value of personas_apellido
     */ 
    public function getPersonas_apellido()
    {
        return $this->personas_apellido;
    }

    /**
     * Set the value of personas_apellido
     *
     * @return  self
     */ 
    public function setPersonas_apellido($personas_apellido)
    {
        $this->personas_apellido = $personas_apellido;

        return $this;
    }

    /**
     * Get the value of personas_dni
     */ 
    public function getPersonas_dni()
    {
        return $this->personas_dni;
    }

    /**
     * Set the value of personas_dni
     *
     * @return  self
     */ 
    public function setPersonas_dni($personas_dni)
    {
        $this->personas_dni = $personas_dni;

        return $this;
    }

    /**
     * Get the value of personas_fecha_nac
     */ 
    public function getPersonas_fecha_nac()
    {
        return $this->personas_fecha_nac;
    }

    /**
     * Set the value of personas_fecha_nac
     *
     * @return  self
     */ 
    public function setPersonas_fecha_nac($personas_fecha_nac)
    {
        $this->personas_fecha_nac = $personas_fecha_nac;

        return $this;
    }

    /**
     * Get the value of activo
     */ 
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set the value of activo
     *
     * @return  self
     */ 
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }
}
?>