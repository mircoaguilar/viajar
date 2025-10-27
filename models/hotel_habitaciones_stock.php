<?php
require_once('conexion.php');

class Hotel_Habitaciones_Stock {
    private $id_stock;
    private $rela_habitacion;
    private $fecha;
    private $cantidad;
    private $activo;

    public function __construct(
        $id_stock = '',
        $rela_habitacion = '',
        $fecha = '',
        $cantidad = 0,
        $activo = 1
    ) {
        $this->id_stock = $id_stock;
        $this->rela_habitacion = $rela_habitacion;
        $this->fecha = $fecha;
        $this->cantidad = $cantidad;
        $this->activo = $activo;
    }

    public function guardar_rango($id_habitacion, $fecha_inicio, $fecha_fin, $cantidad) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        
        $id_habitacion = (int)$id_habitacion;
        $cantidad = (int)$cantidad;

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
            
            $query = "INSERT INTO hotel_habitaciones_stock (rela_habitacion, fecha, cantidad_disponible, activo)
                    VALUES ($id_habitacion, '$fecha_sql', $cantidad, 1)
                    ON DUPLICATE KEY UPDATE cantidad_disponible=$cantidad, activo=1"; 
            
            $result = $mysqli->query($query);
            
            if ($result === false) {
                $error_msg = "Error SQL en fecha $fecha_sql: " . $mysqli->error;
                throw new Exception($error_msg);
            }

            if ($mysqli->affected_rows > 0 || $mysqli->insert_id > 0) {
                $insertados++;
            } else {
                $insertados++;
            }
        }


        return $insertados;
    }

    public function traer_por_habitacion($id_habitacion) {
        $conexion = new Conexion();
        $id_habitacion = (int)$id_habitacion;

        $query = "SELECT * FROM hotel_habitaciones_stock
                  WHERE rela_habitacion=$id_habitacion AND activo=1
                  ORDER BY fecha ASC";

        return $conexion->consultar($query);
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $cantidad = (int)$this->cantidad;
        $query = "UPDATE hotel_habitaciones_stock
                  SET cantidad_disponible=$cantidad
                  WHERE id_stock=" . (int)$this->id_stock;

        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE hotel_habitaciones_stock
                  SET activo=0
                  WHERE id_stock=" . (int)$this->id_stock;
        return $conexion->actualizar($query);
    }
    public function get_stock_fecha($id_habitacion, $fecha) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_habitacion = (int)$id_habitacion;
        $fecha_sql = $mysqli->real_escape_string($fecha);

        $query = "SELECT cantidad_disponible 
                FROM hotel_habitaciones_stock
                WHERE rela_habitacion=$id_habitacion 
                    AND fecha='$fecha_sql' 
                    AND activo=1
                LIMIT 1";
        $result = $conexion->consultar($query);

        if (!empty($result)) {
            return intval($result[0]['cantidad_disponible']);
        }
        return null;
    }

    public function decrementar_stock($id_habitacion, $fecha, $mysqli) {
    $id_habitacion = (int)$id_habitacion;
    $fecha_sql = $mysqli->real_escape_string($fecha);

    $query = "UPDATE hotel_habitaciones_stock
              SET cantidad_disponible = cantidad_disponible - 1
              WHERE rela_habitacion=$id_habitacion
                AND fecha='$fecha_sql'
                AND cantidad_disponible >= 1
                AND activo=1";

    $ok = $mysqli->query($query);
    return $ok;
}



    public function getId_stock() { return $this->id_stock; }
    public function setId_stock($id) { $this->id_stock = $id; return $this; }

    public function getRela_habitacion() { return $this->rela_habitacion; }
    public function setRela_habitacion($id) { $this->rela_habitacion = $id; return $this; }

    public function getFecha() { return $this->fecha; }
    public function setFecha($fecha) { $this->fecha = $fecha; return $this; }

    public function getCantidad() { return $this->cantidad; } 
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}