<?php

require_once ('conexion.php');

class Ganancia {
    private $id_ganancia;
    private $id_reserva;
    private $tipo_servicio;
    private $ganancia_neta;
    private $fecha_calculo;

    public function __construct($id_ganancia = '', $id_reserva = '', $tipo_servicio = '', $ganancia_neta = '', $fecha_calculo = '') {
        $this->id_ganancia = $id_ganancia;
        $this->id_reserva = $id_reserva;
        $this->tipo_servicio = $tipo_servicio;
        $this->ganancia_neta = $ganancia_neta;
        $this->fecha_calculo = $fecha_calculo;
    }

    public function guardar() {
        $conexion = new Conexion();
        $query = "INSERT INTO ganancias (id_reserva, tipo_servicio, ganancia_neta, fecha_calculo)
                  VALUES ('$this->id_reserva', '$this->tipo_servicio', '$this->ganancia_neta', NOW())";

        $resultado = $conexion->insertar($query);

        return $resultado;
    }

    public function obtenerGananciasPorReserva($id_reserva) {
        $conexion = new Conexion();
        $query = "SELECT * FROM ganancias WHERE id_reserva = $id_reserva";
        $resultado = $conexion->consultar($query);

        return $resultado;
    }

    public function obtenerTodasLasGanancias() {
        $conexion = new Conexion();
        $query = "SELECT * FROM ganancias";
        $resultado = $conexion->consultar($query);

        return $resultado;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $query = "UPDATE ganancias
                  SET tipo_servicio = '$this->tipo_servicio',
                      ganancia_neta = '$this->ganancia_neta',
                      fecha_calculo = NOW()
                  WHERE id_ganancia = $this->id_ganancia";

        $resultado = $conexion->actualizar($query);

        return $resultado;
    }

    public function eliminar() {
        $conexion = new Conexion();
        $query = "DELETE FROM ganancias WHERE id_ganancia = $this->id_ganancia";
        $resultado = $conexion->eliminar($query);

        return $resultado;
    }

    public function obtenerGananciasPorServicio($tipo_servicio, $inicio = null, $fin = null) {
    $conexion = new Conexion();
        if (!$inicio) $inicio = date('Y-m-01'); 
        if (!$fin) $fin = date('Y-m-t'); 
        $query = "SELECT g.id_reserva, g.tipo_servicio, g.ganancia_neta, g.fecha_calculo
              FROM ganancias g
              JOIN reservas r ON r.id_reservas = g.id_reserva  
              JOIN detalle_reservas dr ON dr.rela_reservas = r.id_reservas  
              JOIN detalle_reserva_hotel drh ON drh.rela_detalle_reserva = dr.id_detalle_reserva
              WHERE g.tipo_servicio = '$tipo_servicio'
              AND r.reservas_estado = 'confirmada'
              AND g.fecha_calculo BETWEEN '$inicio' AND '$fin'";
        $resultado = $conexion->consultar($query);
        return $resultado;
    }


    public function obtenerGananciasPorServicioYFecha($tipo_servicio, $inicio, $fin) {
        $conexion = new Conexion();
        $query = "SELECT g.id_reserva, g.tipo_servicio, g.ganancia_neta, g.fecha_calculo
                FROM ganancias g
                JOIN reservas r ON r.id_reservas = g.id_reserva
                JOIN detalle_reservas dr ON dr.rela_reservas = r.id_reserva
                JOIN detalle_reserva_hotel drh ON drh.rela_detalle_reserva = dr.id_detalle_reserva
                WHERE g.tipo_servicio = '$tipo_servicio'
                AND r.reservas_estado = 'confirmada'
                AND g.fecha_calculo BETWEEN '$inicio' AND '$fin'";

        $resultado = $conexion->consultar($query);
        return $resultado;
    }

    public function obtenerGananciasPorMes() {
        $conexion = new Conexion();
        $query = "SELECT
                    DATE_FORMAT(g.fecha_calculo, '%Y-%m') AS mes, 
                    SUM(g.ganancia_neta) AS ganancia_neta
                FROM ganancias g
                JOIN reservas r ON r.id_reservas = g.id_reserva
                WHERE r.reservas_estado = 'confirmada'
                GROUP BY mes
                ORDER BY mes DESC"; 

        $resultado = $conexion->consultar($query);
        return $resultado;
    }

    public function registrarGanancia($id_reserva, $tipo_servicio, $ganancia_neta) {
        $conexion = new Conexion();
        $query = "INSERT INTO ganancias (id_reserva, tipo_servicio, ganancia_neta, fecha_calculo)
                  VALUES ('$id_reserva', '$tipo_servicio', '$ganancia_neta', NOW())";
        $resultado = $conexion->insertar($query);

        return $resultado;
    }

    public function calcularGanancia($total) {
        return $total * 0.10; 
    }


}
