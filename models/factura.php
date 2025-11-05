<?php
require_once('conexion.php');

class Factura {
    private $id_factura;
    private $factura_numero_factura;
    private $factura_fecha_emision;
    private $rela_reserva;

    public function __construct(
        $id_factura = '',
        $factura_numero_factura = '',
        $rela_reserva = ''
    ) {
        $this->id_factura = $id_factura;
        $this->factura_numero_factura = $factura_numero_factura;
        $this->factura_fecha_emision = date('Y-m-d H:i:s');
        $this->rela_reserva = $rela_reserva;
    }

    public function crear() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $numero = $mysqli->real_escape_string($this->factura_numero_factura);
        $fecha = $this->factura_fecha_emision;
        $id_reserva = (int)$this->rela_reserva;

        $query = "INSERT INTO factura (factura_numero_factura, factura_fecha_emision, rela_reserva)
                  VALUES ('$numero', '$fecha', $id_reserva)";

        $id = $conexion->insertar($query);
        $this->id_factura = $id;
        return $id;
    }

    public function traerPorId($id_factura) {
        $conexion = new Conexion();
        $id_factura = (int)$id_factura;

        $query = "SELECT * FROM factura WHERE id_factura = $id_factura LIMIT 1";
        $facturas = $conexion->consultar($query);
        return !empty($facturas) ? $facturas[0] : null;
    }

    public function traerPorReserva($id_reserva) {
        $conexion = new Conexion();
        $id_reserva = (int)$id_reserva;

        $query = "SELECT * FROM factura WHERE rela_reserva = $id_reserva";
        return $conexion->consultar($query);
    }

    public function crear_factura($numero, $id_reserva) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("INSERT INTO factura (factura_numero_factura, factura_fecha_emision, rela_reserva) VALUES (?, NOW(), ?)");
        $stmt->bind_param("si", $numero, $id_reserva);
        $stmt->execute();
        $id_factura = $stmt->insert_id;
        $stmt->close();
        return $id_factura;
    }


    public function getId_factura() { return $this->id_factura; }
    public function setId_factura($id) { $this->id_factura = $id; return $this; }

    public function getFactura_numero_factura() { return $this->factura_numero_factura; }
    public function setFactura_numero_factura($numero) { $this->factura_numero_factura = $numero; return $this; }

    public function getFactura_fecha_emision() { return $this->factura_fecha_emision; }
    public function setFactura_fecha_emision($fecha) { $this->factura_fecha_emision = $fecha; return $this; }

    public function getRela_reserva() { return $this->rela_reserva; }
    public function setRela_reserva($id_reserva) { $this->rela_reserva = $id_reserva; return $this; }
}
