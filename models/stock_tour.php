<?php
require_once('conexion.php');

class Tour_Stock {
    private $id_stock_tour;
    private $rela_tour;
    private $fecha;
    private $cupos_disponibles;
    private $activo;

    public function __construct(
        $id_stock_tour = '',
        $rela_tour = '',
        $fecha = '',
        $cupos_disponibles = 0,
        $activo = 1
    ) {
        $this->id_stock_tour = $id_stock_tour;
        $this->rela_tour = $rela_tour;
        $this->fecha = $fecha;
        $this->cupos_disponibles = $cupos_disponibles;
        $this->activo = $activo;
    }

    public function guardar_rango($id_tour, $fecha_inicio, $fecha_fin, $cupos) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_tour = (int)$id_tour;
        $cupos = (int)$cupos;

        try {
            $start = new DateTime($fecha_inicio);
            $end = new DateTime($fecha_fin);
            $interval = new DateInterval('P1D');
            $period = new DatePeriod($start, $interval, $end->modify('+1 day'));
        } catch (Exception $e) {
            throw new Exception("Error en el formato de fechas: " . $e->getMessage());
        }

        $insertados = 0;

        foreach ($period as $date) {
            $fecha_sql = $mysqli->real_escape_string($date->format('Y-m-d'));
            $query = "
                INSERT INTO stock_tour (rela_tour, fecha, cupos_disponibles, cupos_reservados, activo, created_at, updated_at)
                VALUES ($id_tour, '$fecha_sql', $cupos, 0, 1, NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    cupos_disponibles = VALUES(cupos_disponibles),
                    activo = 1,
                    updated_at = NOW()
            ";

            if (!$mysqli->query($query)) {
                throw new Exception("Error SQL en fecha $fecha_sql: " . $mysqli->error);
            }

            $insertados++;
        }

        return $insertados;
    }

    public function traer_por_tour($id_tour) {
        $conexion = new Conexion();
        $id_tour = (int)$id_tour;

        $query = "
            SELECT id_stock_tour, rela_tour, fecha, cupos_disponibles, cupos_reservados, activo
            FROM stock_tour 
            WHERE rela_tour = $id_tour 
              AND activo = 1 
            ORDER BY fecha ASC
        ";

        return $conexion->consultar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $cupos = (int)$this->cupos_disponibles;

        $query = "
            UPDATE stock_tour 
            SET cupos_disponibles = $cupos, updated_at = NOW() 
            WHERE id_stock_tour = " . (int)$this->id_stock_tour;

        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();

        $query = "
            UPDATE stock_tour 
            SET activo = 0, updated_at = NOW() 
            WHERE id_stock_tour = " . (int)$this->id_stock_tour;

        return $conexion->actualizar($query);
    }

    public function get_stock_fecha($id_tour, $fecha) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_tour = (int)$id_tour;
        $fecha_sql = $mysqli->real_escape_string($fecha);

        $query = "
            SELECT cupos_disponibles 
            FROM stock_tour 
            WHERE rela_tour = $id_tour 
              AND fecha = '$fecha_sql' 
              AND activo = 1 
            LIMIT 1
        ";

        $result = $conexion->consultar($query);

        if (!empty($result)) {
            return intval($result[0]['cupos_disponibles']);
        }

        return null;
    }

    public function decrementar_stock($id_tour, $fecha, $mysqli) {
        $id_tour = (int)$id_tour;
        $fecha_sql = $mysqli->real_escape_string($fecha);

        $query = "
            UPDATE stock_tour 
            SET cupos_disponibles = cupos_disponibles - 1,
                cupos_reservados = cupos_reservados + 1,
                updated_at = NOW()
            WHERE rela_tour = $id_tour 
              AND fecha = '$fecha_sql' 
              AND cupos_disponibles >= 1 
              AND activo = 1
        ";

        return $mysqli->query($query);
    }

    public function traer_fechas_disponibles($id_tour) {
        $conexion = new Conexion();
        $id_tour = (int)$id_tour;

        $query = "
            SELECT id_stock_tour, fecha, cupos_disponibles
            FROM stock_tour
            WHERE rela_tour = $id_tour
            AND activo = 1
            AND cupos_disponibles > 0
            ORDER BY fecha ASC
        ";
        return $conexion->consultar($query);
    }


    public function getId_stock_tour() { return $this->id_stock_tour; }
    public function setId_stock_tour($id) { $this->id_stock_tour = $id; return $this; }

    public function getRela_tour() { return $this->rela_tour; }
    public function setRela_tour($id) { $this->rela_tour = $id; return $this; }

    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; return $this; }

    public function getCupos_disponibles() { return $this->cupos_disponibles; }
    public function setCupos_disponibles($cupos) { $this->cupos_disponibles = $cupos; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
