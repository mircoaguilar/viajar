<?php

require_once('conexion.php');
require_once('auditoria.php');

class Perfil {
    private $id_perfiles;
    private $perfiles_nombre;
    private $activo;

    public function __construct($id_perfiles = '', $perfiles_nombre = '', $activo = 1) {
        $this->id_perfiles = $id_perfiles;
        $this->perfiles_nombre = $perfiles_nombre;
        $this->activo = $activo;
    }

    public function traer_perfiles() {
        $conexion = new Conexion();
        $query = "SELECT * FROM perfiles WHERE activo = 1 ORDER BY perfiles_nombre ASC";
        return $conexion->consultar($query);
    }

    public function traer_perfil($id_perfiles) {
        $conexion = new Conexion();
        $query = "SELECT id_perfiles, perfiles_nombre 
                  FROM perfiles 
                  WHERE id_perfiles = " . (int)$id_perfiles . " AND activo = 1";
        return $conexion->consultar($query);
    }

    public function guardar() {
        $conexion = new Conexion();
        $nombre_escapado = $conexion->getConexion()->real_escape_string($this->perfiles_nombre);

        $query = "INSERT INTO perfiles (perfiles_nombre, activo) VALUES ('$nombre_escapado', 1)";
        $resultado = $conexion->insertar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '', 
                $_SESSION['id_usuarios'] ?? null,
                'Alta de perfil',
                "Se creó el perfil: {$this->perfiles_nombre}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli_connection = $conexion->getConexion(); 
        $nombre_escapado = $mysqli_connection->real_escape_string($this->perfiles_nombre);

        $query = "UPDATE perfiles 
                  SET perfiles_nombre = '$nombre_escapado' 
                  WHERE id_perfiles = " . (int)$this->id_perfiles;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Modificación de perfil',
                "Se modificó el perfil (ID {$this->id_perfiles}) a: {$this->perfiles_nombre}"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE perfiles 
                  SET activo = 0 
                  WHERE id_perfiles = " . (int)$this->id_perfiles;
        $resultado = $conexion->actualizar($query);

        if ($resultado) {
            $auditoria = new Auditoria(
                '',
                $_SESSION['id_usuarios'] ?? null,
                'Baja lógica de perfil',
                "Se desactivó el perfil (ID {$this->id_perfiles})"
            );
            $auditoria->guardar();
        }

        return $resultado;
    }

    /**
     * Get the value of id_perfiles
     */ 
    public function getId_perfiles()
    {
        return $this->id_perfiles;
    }

    /**
     * Set the value of id_perfiles
     *
     * @return  self
     */ 
    public function setId_perfiles($id_perfiles)
    {
        $this->id_perfiles = $id_perfiles;

        return $this;
    }

    /**
     * Get the value of perfiles_nombre
     */ 
    public function getPerfiles_nombre()
    {
        return $this->perfiles_nombre;
    }

    /**
     * Set the value of perfiles_nombre
     *
     * @return  self
     */ 
    public function setPerfiles_nombre($perfiles_nombre)
    {
        $this->perfiles_nombre = $perfiles_nombre;

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