<?php

require_once('conexion.php');

class Modulo{
    private $id_modulos;
    private $modulos_nombre;
    private $id_perfil; 
    private $modulos_ids_seleccionados; 

    public function __construct($id_modulos='', $modulos_nombre='') {
        $this->id_modulos = $id_modulos;
        $this->modulos_nombre = $modulos_nombre;
    }

    public function actualizar(){
        $conexion = new Conexion();
        $query = "DELETE FROM perfiles_has_modulos WHERE perfiles_id_perfiles = '$this->id_perfil'";
        $conexion->actualizar($query);

        if (is_array($this->modulos_ids_seleccionados)) {
            foreach($this->modulos_ids_seleccionados as $modulo_id_individual){
                $query = "INSERT INTO perfiles_has_modulos(perfiles_id_perfiles, modulos_id_modulos, activo) 
                          VALUES ('$this->id_perfil', '$modulo_id_individual', 1)";
                $conexion->actualizar($query);
            }
        }
        return $this->id_perfil;
    }

    public function traer_modulos_por_perfil($id_perfiles){
        $conexion = new Conexion();
        $query = "SELECT modulos.* FROM modulos 
                  INNER JOIN perfiles_has_modulos ON perfiles_has_modulos.modulos_id_modulos = modulos.id_modulos
                  WHERE perfiles_has_modulos.perfiles_id_perfiles = " . $id_perfiles;
        return $conexion->consultar($query);
    }

    public function traer_modulos(){
        $conexion = new Conexion();
        $query = "SELECT p.id_perfiles, p.perfiles_nombre, m.id_modulos, m.modulos_nombre
                  FROM perfiles_has_modulos phm
                  INNER JOIN perfiles p ON p.id_perfiles = phm.perfiles_id_perfiles 
                  INNER JOIN modulos m ON m.id_modulos = phm.modulos_id_modulos;";
        return $conexion->consultar($query);
    }

    public function traer_todos_los_modulos_disponibles(){
        $conexion = new Conexion();
        $query = "SELECT id_modulos, modulos_nombre FROM modulos ORDER BY modulos_nombre ASC";
        return $conexion->consultar($query);
    }
    
    /**
     * Get the value of id_modulos
     */ 
    public function getId_modulos()
    {
        return $this->id_modulos;
    }

    /**
     * Set the value of id_modulos
     *
     * @return  self
     */ 
    public function setId_modulos($id_modulos)
    {
        $this->id_modulos = $id_modulos;

        return $this;
    }

    /**
     * Get the value of modulos_nombre
     */ 
    public function getModulos_nombre()
    {
        return $this->modulos_nombre;
    }

    /**
     * Set the value of modulos_nombre
     *
     * @return  self
     */ 
    public function setModulos_nombre($modulos_nombre)
    {
        $this->modulos_nombre = $modulos_nombre;

        return $this;
    }

    /**
     * Get the value of id_perfil
     */ 
    public function getId_perfil()
    {
        return $this->id_perfil;
    }

    /**
     * Set the value of id_perfil
     *
     * @return  self
     */ 
    public function setId_perfil($id_perfil)
    {
        $this->id_perfil = $id_perfil;

        return $this;
    }

    /**
     * Get the value of modulos_ids_seleccionados
     */ 
    public function getModulos_ids_seleccionados()
    {
        return $this->modulos_ids_seleccionados;
    }

    /**
     * Set the value of modulos_ids_seleccionados
     *
     * @param  array  $modulos_ids_seleccionados  
     * @return  self
     */ 
    public function setModulos_ids_seleccionados(array $modulos_ids_seleccionados)
    {
        $this->modulos_ids_seleccionados = $modulos_ids_seleccionados;

        return $this;
    }
}
