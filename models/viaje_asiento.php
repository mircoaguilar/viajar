<?php
require_once('conexion.php');

class ViajeAsiento {
    private $id_asiento;
    private $rela_viaje;
    private $piso;
    private $fila;
    private $columna;
    private $ocupado;
    private $rela_reserva;

    public function __construct(
        $id_asiento = '',
        $rela_viaje = '',
        $piso = '',
        $fila = '',
        $columna = '',
        $ocupado = 0,
        $rela_reserva = null
    ) {
        $this->id_asiento = $id_asiento;
        $this->rela_viaje = $rela_viaje;
        $this->piso = $piso;
        $this->fila = $fila;
        $this->columna = $columna;
        $this->ocupado = $ocupado;
        $this->rela_reserva = $rela_reserva;
    }

    public function traerAsientosPorViaje($id_viaje) {
        $conexion = new Conexion();
        $id_viaje = (int)$id_viaje;
        $query = "SELECT * FROM viaje_asientos WHERE rela_viaje = $id_viaje ORDER BY piso, fila, columna";
        return $conexion->consultar($query);
    }

    public function traerAsiento($id_asiento) {
        $conexion = new Conexion();
        $id_asiento = (int)$id_asiento;
        $query = "SELECT * FROM viaje_asientos WHERE id_asiento = $id_asiento LIMIT 1";
        $res = $conexion->consultar($query);
        return $res ? $res[0] : null;
    }

    public function ocuparAsiento($id_asiento, $id_reserva) {
        $conexion = new Conexion();
        $id_asiento = (int)$id_asiento;
        $id_reserva = (int)$id_reserva;
        $query = "UPDATE viaje_asientos SET ocupado = 1, rela_reserva = $id_reserva WHERE id_asiento = $id_asiento";
        return $conexion->actualizar($query);
    }

    public function liberarAsiento($id_asiento) {
        $conexion = new Conexion();
        $id_asiento = (int)$id_asiento;
        $query = "UPDATE viaje_asientos SET ocupado = 0, rela_reserva = NULL WHERE id_asiento = $id_asiento";
        return $conexion->actualizar($query);
    }

    public function obtener_asientos_ocupados($id_viaje) {
        $asientos = $this->traerAsientosPorViaje($id_viaje);
        $ocupados = [
            'piso1' => [],
            'piso2' => []
        ];

        foreach ($asientos as $a) {
            if ($a['ocupado']) {
                $ocupados['piso'.$a['piso']][] = $a['columna'] + ($a['fila'] - 1) * 4;
            }
        }

        return $ocupados;
    }

    public function obtener_asientos_ocupados_por_transporte($id_transporte) {
        $conexion = new Conexion();
        $id_transporte = (int)$id_transporte;

        $query = "SELECT va.piso, va.fila, va.columna
                FROM viaje_asientos va
                INNER JOIN viajes v ON va.rela_viaje = v.id_viajes
                WHERE v.rela_transporte_rutas = $id_transporte AND va.ocupado = 1
                ORDER BY va.piso, va.fila, va.columna";

        $asientos = $conexion->consultar($query);

        $ocupados = [];
        foreach ($asientos as $a) {
            $piso = $a['piso'];
            $ocupados['piso' . $piso][] = $a['columna'] + ($a['fila'] - 1) * 4;
        }

        return $ocupados;
    }


    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $rela_viaje = (int)$this->rela_viaje;
        $piso = (int)$this->piso;
        $fila = (int)$this->fila;
        $columna = (int)$this->columna;
        $ocupado = (int)$this->ocupado;
        $rela_reserva = $this->rela_reserva !== null ? (int)$this->rela_reserva : "NULL";

        $query = "INSERT INTO viaje_asientos (rela_viaje, piso, fila, columna, ocupado, rela_reserva) 
                  VALUES ($rela_viaje, $piso, $fila, $columna, $ocupado, $rela_reserva)";

        if ($mysqli->query($query)) {
            return $mysqli->insert_id;
        } else {
            return false;
        }
    }

    public function getIdAsiento() { return $this->id_asiento; }
    public function setIdAsiento($v) { $this->id_asiento = $v; return $this; }

    public function getRelaViaje() { return $this->rela_viaje; }
    public function setRelaViaje($v) { $this->rela_viaje = $v; return $this; }

    public function getPiso() { return $this->piso; }
    public function setPiso($v) { $this->piso = $v; return $this; }

    public function getFila() { return $this->fila; }
    public function setFila($v) { $this->fila = $v; return $this; }

    public function getColumna() { return $this->columna; }
    public function setColumna($v) { $this->columna = $v; return $this; }

    public function getOcupado() { return $this->ocupado; }
    public function setOcupado($v) { $this->ocupado = $v; return $this; }

    public function getRelaReserva() { return $this->rela_reserva; }
    public function setRelaReserva($v) { $this->rela_reserva = $v; return $this; }
}
?>
