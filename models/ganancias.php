<?php
require_once('conexion.php');

class Ganancias extends Conexion {

    private $rela_reserva;
    private $monto_venta;
    private $metodo_pago;
    private $fecha_registro;

    public function setRelaReserva($id) {
        $this->rela_reserva = $id;
    }

    public function setMonto($monto) {
        $this->monto_venta = $monto;
    }

    public function setMetodoPago($metodo) {
        $this->metodo_pago = $metodo;
    }

    public function setFecha($fecha) {
        $this->fecha_registro = $fecha;
    }

        public function insertarGanancia() {
        try {
            $conexion = $this->conectar();

            $sql = "INSERT INTO ganancias 
                (rela_reserva, rela_detalle_reserva, monto_venta, costo_proveedor, costo_transaccion)
                SELECT 
                    r.id_reservas,
                    d.id_detalle_reserva,
                    r.total,
                    d.precio_unitario,
                    0
                FROM reservas r
                LEFT JOIN detalle_reserva d ON d.rela_reservas = r.id_reservas
                WHERE r.id_reservas = ?";

            $consulta = $conexion->prepare($sql);
            $consulta->bind_param("i", $this->rela_reserva);

            return $consulta->execute();
        } catch (Exception $e) {
            echo "Error en insertar ganancia: " . $e->getMessage();
            return false;
        }
    }

    public function listarGanancias() {
        try {
            $conexion = $this->conectar();

            $sql = "SELECT 
            g.id_ganancia,
            g.rela_reserva,
            g.rela_detalle_reserva,
            g.monto_venta,
            g.costo_proveedor,
            g.costo_transaccion,
            g.ganancia_neta,
            g.fecha_registro,
            COALESCE(r.reservas_estado, 'Sin estado') AS reservas_estado
        FROM ganancias g
        LEFT JOIN reservas r ON r.id_reservas = g.rela_reserva
        ORDER BY g.fecha_registro DESC";


            $result = $conexion->query($sql);

            $datos = [];
            if ($result && $result->num_rows > 0) {
                while ($fila = $result->fetch_assoc()) {
                    $datos[] = $fila;
                }
            }

            return $datos;

        } catch (Exception $e) {
            echo "Error al listar ganancias: " . $e->getMessage();
            return [];
        }
    }

}
