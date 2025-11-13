<?php
require_once('conexion.php');
require_once('paginacion.php');

class Auditoria extends Pagination {
    private $id_auditoria;
    private $rela_usuario;
    private $accion;
    private $descripcion;
    private $fecha;

    public function __construct($id_auditoria = '', $rela_usuario = '', $accion = '', $descripcion = '', $fecha = '') {
        $this->id_auditoria = $id_auditoria;
        $this->rela_usuario = $rela_usuario;
        $this->accion = $accion;
        $this->descripcion = $descripcion;
        $this->fecha = $fecha;
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $usuario_id = isset($this->rela_usuario) && $this->rela_usuario !== '' ? (int)$this->rela_usuario : "NULL";
        $accion = $mysqli->real_escape_string($this->accion ?? '');
        $descripcion = $mysqli->real_escape_string($this->descripcion ?? '');
        $fecha = $this->fecha ? "'" . $mysqli->real_escape_string($this->fecha) . "'" : "NOW()";

        $query = "INSERT INTO auditoria (rela_usuario, accion, descripcion, fecha)
                  VALUES ($usuario_id, '$accion', '$descripcion', $fecha)";
        return $conexion->insertar($query);
    }

    public function traer_auditorias_cantidad() {
        $conexion = new Conexion();
        $query = "SELECT COUNT(*) AS total FROM auditoria";
        return $conexion->consultar($query);
    }

    public function traer_auditoria_por_id($id_auditoria) {
        $conexion = new Conexion();
        $query = "SELECT 
                    auditoria.*, 
                    usuarios.usuarios_nombre_usuario AS usuario_nombre 
                  FROM auditoria
                  JOIN usuarios ON auditoria.rela_usuario = usuarios.id_usuarios
                  WHERE id_auditoria = $id_auditoria";
        return $conexion->consultar($query);
    }

    public function traer_todas() {
        $conexion = new Conexion();
        $query = "SELECT 
                    auditoria.id_auditoria,
                    auditoria.rela_usuario,
                    auditoria.accion,
                    auditoria.descripcion,
                    auditoria.fecha,
                    COALESCE(usuarios.usuarios_nombre_usuario, 'Desconocido') AS usuario_nombre,
                    COALESCE(perfiles.perfiles_nombre, '') AS perfil_nombre
                FROM auditoria
                LEFT JOIN usuarios ON auditoria.rela_usuario = usuarios.id_usuarios
                LEFT JOIN perfiles ON usuarios.rela_perfiles = perfiles.id_perfiles
                ORDER BY auditoria.fecha DESC";
        
        return $conexion->consultar($query);
    }


    public function filtrar($usuario = '', $accion = '', $fecha_desde = '', $fecha_hasta = '') {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $condiciones = [];

        if (!empty($usuario)) {
            $usuario = (int)$usuario;
            $condiciones[] = "auditoria.rela_usuario = $usuario";
        }

        if ($accion === 'Otros') {
            $condiciones[] = "(
                auditoria.accion NOT LIKE 'Alta%' AND
                auditoria.accion NOT LIKE 'Actualización%' AND
                auditoria.accion NOT LIKE 'Baja%'
            )";
        } elseif (!empty($accion)) {
            $accion = $mysqli->real_escape_string($accion);
            $condiciones[] = "auditoria.accion LIKE '{$accion}%'";
        }

        if (!empty($fecha_desde)) {
            $fecha_desde = $mysqli->real_escape_string($fecha_desde);
            $condiciones[] = "auditoria.fecha >= '$fecha_desde'";
        }

        if (!empty($fecha_hasta)) {
            $fecha_hasta = $mysqli->real_escape_string($fecha_hasta);
            $condiciones[] = "auditoria.fecha <= '$fecha_hasta'";
        }

        $where = count($condiciones) > 0 ? "WHERE " . implode(" AND ", $condiciones) : "";

        $query = "SELECT 
                    auditoria.id_auditoria,
                    auditoria.rela_usuario,
                    auditoria.accion,
                    auditoria.descripcion,
                    auditoria.fecha,
                    COALESCE(usuarios.usuarios_nombre_usuario, 'Desconocido') AS usuario_nombre,
                    COALESCE(perfiles.perfiles_nombre, '') AS perfil_nombre
                FROM auditoria
                LEFT JOIN usuarios ON auditoria.rela_usuario = usuarios.id_usuarios
                LEFT JOIN perfiles ON usuarios.rela_perfiles = perfiles.id_perfiles
                $where
                ORDER BY auditoria.fecha DESC";

        return $conexion->consultar($query);
    }

    public function traer_acciones_distintas() {
        $conexion = new Conexion();
        $query = "SELECT DISTINCT accion FROM auditoria ORDER BY accion ASC";
        return $conexion->consultar($query);
    }

    public static function registrar_evento($accion, $descripcion) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $usuario_id = $_SESSION['id_usuarios'] ?? null;

        if (!$usuario_id) {
            return false; 
        }

        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $accion = $mysqli->real_escape_string($accion);
        $descripcion = $mysqli->real_escape_string($descripcion);

        $query = "INSERT INTO auditoria (rela_usuario, accion, descripcion, fecha)
                VALUES ($usuario_id, '$accion', '$descripcion', NOW())";

        return $conexion->insertar($query);
    }



        /**
         * Get the value of id_auditoria
         */ 
        public function getId_auditoria()
        {
                return $this->id_auditoria;
        }

        /**
         * Set the value of id_auditoria
         *
         * @return  self
         */ 
        public function setId_auditoria($id_auditoria)
        {
                $this->id_auditoria = $id_auditoria;

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
         * Get the value of accion
         */ 
        public function getAccion()
        {
                return $this->accion;
        }

        /**
         * Set the value of accion
         *
         * @return  self
         */ 
        public function setAccion($accion)
        {
                $this->accion = $accion;

                return $this;
        }

        /**
         * Get the value of descripcion
         */ 
        public function getDescripcion()
        {
                return $this->descripcion;
        }

        /**
         * Set the value of descripcion
         *
         * @return  self
         */ 
        public function setDescripcion($descripcion)
        {
                $this->descripcion = $descripcion;

                return $this;
        }

        /**
         * Get the value of fecha
         */ 
        public function getFecha()
        {
                return $this->fecha;
        }

        /**
         * Set the value of fecha
         *
         * @return  self
         */ 
        public function setFecha($fecha)
        {
                $this->fecha = $fecha;

                return $this;
        }
    }

?>
