<?php
require_once('conexion.php');

class CarritoItem {
    private $id_item;
    private $rela_carrito;
    private $tipo_servicio;
    private $id_servicio;
    private $cantidad;
    private $fecha_inicio;
    private $fecha_fin;
    private $precio_unitario;
    private $subtotal;

    private $db;

    public function __construct() {
        $this->db = new Conexion();
    }

    public function guardar() {
        if (!$this->subtotal) {
            $this->subtotal = $this->cantidad * $this->precio_unitario;
        }

        $query = "INSERT INTO carrito_items 
            (rela_carrito, tipo_servicio, id_servicio, cantidad, fecha_inicio, fecha_fin, precio_unitario, subtotal)
            VALUES (
                '{$this->rela_carrito}',
                '{$this->tipo_servicio}',
                '{$this->id_servicio}',
                '{$this->cantidad}',
                " . ($this->fecha_inicio ? "'{$this->fecha_inicio}'" : "NULL") . ",
                " . ($this->fecha_fin ? "'{$this->fecha_fin}'" : "NULL") . ",
                '{$this->precio_unitario}',
                '{$this->subtotal}'
            )";

        return $this->db->insertar($query);
    }

    public function actualizar($id_item = null) {
        if ($id_item) $this->id_item = $id_item;
        $this->subtotal = $this->cantidad * $this->precio_unitario;

        $query = "UPDATE carrito_items SET
            cantidad = '{$this->cantidad}',
            precio_unitario = '{$this->precio_unitario}',
            subtotal = '{$this->subtotal}',
            fecha_inicio = " . ($this->fecha_inicio ? "'{$this->fecha_inicio}'" : "NULL") . ",
            fecha_fin = " . ($this->fecha_fin ? "'{$this->fecha_fin}'" : "NULL") . "
            WHERE id_item = '{$this->id_item}'";

        return $this->db->actualizar($query);
    }

    public function eliminar($id_item) {
        $query = "DELETE FROM carrito_items WHERE id_item = '{$id_item}'";
        return $this->db->eliminar($query);
    }

    public function traer_por_carrito($id_carrito) {
    return $this->db->consultar("
        SELECT 
            *,
            fecha_inicio AS fecha_tour
        FROM carrito_items
        WHERE rela_carrito = '{$id_carrito}'
    ");
}

    public function traer_por_id($id_item) {
        $result = $this->db->consultar("
            SELECT * FROM carrito_items
            WHERE id_item = '{$id_item}'
            LIMIT 1
        ");
        return $result[0] ?? null;
    }

    public function getError() {
        return $this->db->ultimo_error();
    }

    public function setId_item($id) { $this->id_item = $id; return $this; }
    public function getId_item() { return $this->id_item; }

    public function setId_carrito($id) { $this->rela_carrito = $id; return $this; }
    public function getId_carrito() { return $this->rela_carrito; }

    public function setTipo_servicio($tipo) { $this->tipo_servicio = $tipo; return $this; }
    public function getTipo_servicio() { return $this->tipo_servicio; }

    public function setId_servicio($id) { $this->id_servicio = $id; return $this; }
    public function getId_servicio() { return $this->id_servicio; }

    public function setCantidad($cant) { $this->cantidad = $cant; return $this; }
    public function getCantidad() { return $this->cantidad; }

    public function setFecha_inicio($fecha) { $this->fecha_inicio = $fecha; return $this; }
    public function getFecha_inicio() { return $this->fecha_inicio; }

    public function setFecha_fin($fecha) { $this->fecha_fin = $fecha; return $this; }
    public function getFecha_fin() { return $this->fecha_fin; }

    public function setPrecio_unitario($precio) { $this->precio_unitario = $precio; return $this; }
    public function getPrecio_unitario() { return $this->precio_unitario; }

    public function setSubtotal($sub) { $this->subtotal = $sub; return $this; }
    public function getSubtotal() { return $this->subtotal; }
}
?>
