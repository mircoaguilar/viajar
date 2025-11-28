<?php
require_once('conexion.php');

class Pago {
    private $id_pago;
    private $pago_fecha;
    private $pago_monto;
    private $pago_estado;
    private $rela_reservas;
    private $rela_tipo_pago;
    private $rela_monedas;
    private $pago_comprobante;
    private $activo;

    public function __construct(
        $id_pago = '',
        $pago_monto = 0,
        $pago_estado = 'pendiente',
        $rela_reservas = '',
        $rela_tipo_pago = 8, 
        $rela_monedas = 3,
        $pago_comprobante = null
    ) {
        $this->id_pago = $id_pago;
        $this->pago_fecha = date('Y-m-d H:i:s');
        $this->pago_monto = $pago_monto;
        $this->pago_estado = $pago_estado;
        $this->rela_reservas = $rela_reservas;
        $this->rela_tipo_pago = $rela_tipo_pago;
        $this->rela_monedas = $rela_monedas;
        $this->pago_comprobante = $pago_comprobante;
        $this->activo = 1;
    }

    public function crear() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $monto = (float)$this->pago_monto;
        $estado = $mysqli->real_escape_string($this->pago_estado);
        $id_reserva = (int)$this->rela_reservas;
        $tipo_pago = (int)$this->rela_tipo_pago;
        $moneda = (int)$this->rela_monedas;
        $comprobante = $mysqli->real_escape_string($this->pago_comprobante ?? '');

        $query = "INSERT INTO pago (pago_fecha, pago_monto, pago_estado, rela_reservas, rela_tipo_pago, rela_monedas, pago_comprobante, activo)
                  VALUES ('{$this->pago_fecha}', $monto, '$estado', $id_reserva, $tipo_pago, $moneda, '$comprobante', 1)";

        $id = $conexion->insertar($query);
        $this->id_pago = $id;
        return $id;
    }

    public function crear_pago($id_reserva, $monto, $estado = 'pendiente', $tipoPagoId = 8, $id_moneda = 3, $comprobante = null) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $stmt = $mysqli->prepare("INSERT INTO pago (pago_fecha, pago_monto, pago_estado, rela_reservas, rela_tipo_pago, rela_monedas, pago_comprobante) VALUES (NOW(), ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("dsiiss", $monto, $estado, $id_reserva, $tipoPagoId, $id_moneda, $comprobante);
        $stmt->execute();
        $id_pago = $stmt->insert_id;
        $stmt->close();

        return $id_pago;
    }

    public function actualizarEstado($estado) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $estado = $mysqli->real_escape_string($estado);
        $id = (int)$this->id_pago;

        $query = "UPDATE pago SET pago_estado='$estado' WHERE id_pago=$id";
        return $conexion->actualizar($query);
    }

    public function actualizarComprobante($comprobante, $estado) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $id = (int)$this->id_pago;

        $stmt = $mysqli->prepare("UPDATE pago SET pago_comprobante = ?, pago_estado = ? WHERE id_pago = ?");
        $stmt->bind_param("ssi", $comprobante, $estado, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function traerPorReserva($id_reserva) {
        $conexion = new Conexion();
        $id_reserva = (int)$id_reserva;
        $query = "SELECT * 
                FROM pago 
                WHERE rela_reservas = $id_reserva
                ORDER BY pago_fecha DESC 
                LIMIT 1";

        $resultado = $conexion->consultar($query);
        return !empty($resultado) ? $resultado[0] : null;
    }


    public function eliminarLogico() {
        $conexion = new Conexion();
        $id = (int)$this->id_pago;

        $query = "UPDATE pago SET activo=0 WHERE id_pago=$id";
        return $conexion->actualizar($query);
    }

    public function enviarComprobante($email_usuario) {
        $asunto = "Comprobante de pago ViajAR";
        $mensaje = "Hola, tu pago de $ {$this->pago_monto} se registró correctamente.\nEstado: {$this->pago_estado}\nComprobante: {$this->pago_comprobante}";
        mail($email_usuario, $asunto, $mensaje);
    }

    public function traerPorId($id_pago) {
        $conexion = new Conexion();
        $id_pago = (int)$id_pago;

        $query = "SELECT * FROM pago WHERE id_pago=$id_pago";
        $result = $conexion->consultar($query);

        if ($result && count($result) > 0) {
            return $result[0];
        }

        return null; 
    }

    public function getId_pago() { return $this->id_pago; }
    public function setId_pago($id) { $this->id_pago = $id; return $this; }

    public function getPago_fecha() { return $this->pago_fecha; }
    public function setPago_fecha($fecha) { $this->pago_fecha = $fecha; return $this; }

    public function getPago_monto() { return $this->pago_monto; }
    public function setPago_monto($monto) { $this->pago_monto = $monto; return $this; }

    public function getPago_estado() { return $this->pago_estado; }
    public function setPago_estado($estado) { $this->pago_estado = $estado; return $this; }

    public function getRela_reservas() { return $this->rela_reservas; }
    public function setRela_reservas($id_reserva) { $this->rela_reservas = $id_reserva; return $this; }

    public function getRela_tipo_pago() { return $this->rela_tipo_pago; }
    public function setRela_tipo_pago($tipo) { $this->rela_tipo_pago = $tipo; return $this; }

    public function getRela_monedas() { return $this->rela_monedas; }
    public function setRela_monedas($id_moneda) { $this->rela_monedas = $id_moneda; return $this; }

    public function getPago_comprobante() { return $this->pago_comprobante; }
    public function setPago_comprobante($comprobante) { $this->pago_comprobante = $comprobante; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
