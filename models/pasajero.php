<?php

require_once('conexion.php');

class Pasajero {

    private $id_pasajeros;
    private $rela_usuario;
    private $nombre;
    private $apellido;
    private $rela_nacionalidad;
    private $rela_tipo_documento;
    private $numero_documento;
    private $sexo;
    private $fecha_nacimiento;

    public function __construct(
        $id_pasajeros = '',
        $rela_usuario = null,
        $nombre = '',
        $apellido = '',
        $rela_nacionalidad = '',
        $rela_tipo_documento = '',
        $numero_documento = '',
        $sexo = '',
        $fecha_nacimiento = ''
    ) {
        $this->id_pasajeros = $id_pasajeros;
        $this->rela_usuario = $rela_usuario;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->rela_nacionalidad = $rela_nacionalidad;
        $this->rela_tipo_documento = $rela_tipo_documento;
        $this->numero_documento = $numero_documento;
        $this->sexo = $sexo;
        $this->fecha_nacimiento = $fecha_nacimiento;
    }
    
    public function guardar() {
        $conexion = new Conexion();

        $query = "INSERT INTO pasajeros 
                    (rela_usuario, nombre, apellido, rela_nacionalidad, rela_tipo_documento, numero_documento, sexo, fecha_nacimiento)
                  VALUES (
                        " . ($this->rela_usuario ? "'$this->rela_usuario'" : "NULL") . ",
                        '$this->nombre',
                        '$this->apellido',
                        '$this->rela_nacionalidad',
                        '$this->rela_tipo_documento',
                        '$this->numero_documento',
                        '$this->sexo',
                        '$this->fecha_nacimiento'
                    )";

        return $conexion->insertar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();

        $query = "UPDATE pasajeros SET
                    rela_usuario = " . ($this->rela_usuario ? "'$this->rela_usuario'" : "NULL") . ",
                    nombre = '$this->nombre',
                    apellido = '$this->apellido',
                    rela_nacionalidad = '$this->rela_nacionalidad',
                    rela_tipo_documento = '$this->rela_tipo_documento',
                    numero_documento = '$this->numero_documento',
                    sexo = '$this->sexo',
                    fecha_nacimiento = '$this->fecha_nacimiento'
                  WHERE id_pasajeros = '$this->id_pasajeros'";

        return $conexion->actualizar($query);
    }

    public function eliminar() {
        $conexion = new Conexion();
        $query = "DELETE FROM pasajeros WHERE id_pasajeros = '$this->id_pasajeros'";
        return $conexion->eliminar($query);
    }

    public function obtener_por_id($id_pasajeros) {
        $conexion = new Conexion();
        $query = "SELECT * FROM pasajeros WHERE id_pasajeros = '$id_pasajeros'";
        $resultado = $conexion->consultar($query);
        return $resultado ? $resultado[0] : null;
    }

    public function obtener_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $query = "SELECT * FROM pasajeros WHERE rela_usuario = '$id_usuario'";
        return $conexion->consultar($query);
    }

    /**
     * Get the value of id_pasajeros
     */ 
    public function getId_pasajeros()
    {
        return $this->id_pasajeros;
    }

    /**
     * Set the value of id_pasajeros
     *
     * @return  self
     */ 
    public function setId_pasajeros($id_pasajeros)
    {
        $this->id_pasajeros = $id_pasajeros;

        return $this;
    }

    /**
     * Get the value of rela_usuario
     */ 
    public function getRela_usuario()
    {
        return $this->rela_usuario;
    }

    /**
     * Set the value of rela_usuario
     *
     * @return  self
     */ 
    public function setRela_usuario($rela_usuario)
    {
        $this->rela_usuario = $rela_usuario;

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

    /**
     * Get the value of apellido
     */ 
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set the value of apellido
     *
     * @return  self
     */ 
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get the value of rela_nacionalidad
     */ 
    public function getRela_nacionalidad()
    {
        return $this->rela_nacionalidad;
    }

    /**
     * Set the value of rela_nacionalidad
     *
     * @return  self
     */ 
    public function setRela_nacionalidad($rela_nacionalidad)
    {
        $this->rela_nacionalidad = $rela_nacionalidad;

        return $this;
    }

    /**
     * Get the value of rela_tipo_documento
     */ 
    public function getRela_tipo_documento()
    {
        return $this->rela_tipo_documento;
    }

    /**
     * Set the value of rela_tipo_documento
     *
     * @return  self
     */ 
    public function setRela_tipo_documento($rela_tipo_documento)
    {
        $this->rela_tipo_documento = $rela_tipo_documento;

        return $this;
    }

    /**
     * Get the value of numero_documento
     */ 
    public function getNumero_documento()
    {
        return $this->numero_documento;
    }

    /**
     * Set the value of numero_documento
     *
     * @return  self
     */ 
    public function setNumero_documento($numero_documento)
    {
        $this->numero_documento = $numero_documento;

        return $this;
    }

    /**
     * Get the value of sexo
     */ 
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set the value of sexo
     *
     * @return  self
     */ 
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get the value of fecha_nacimiento
     */ 
    public function getFecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }

    /**
     * Set the value of fecha_nacimiento
     *
     * @return  self
     */ 
    public function setFecha_nacimiento($fecha_nacimiento)
    {
        $this->fecha_nacimiento = $fecha_nacimiento;

        return $this;
    }
}
