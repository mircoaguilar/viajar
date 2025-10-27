<?php
require_once('conexion.php');

class Carrito {
    private $id_carrito;
    private $rela_usuario;
    private $activo; // 1 = activo, 0 = cerrado
    private $fecha_creacion;

    private $db;

    public function __construct() {
        $this->db = new Conexion();
    }

    public function guardar() {
        $id = $this->db->insertar("
            INSERT INTO carrito (rela_usuario, activo, fecha_creacion)
            VALUES ('{$this->rela_usuario}', '{$this->activo}', NOW())
        ");
        if (!$id) throw new Exception("No se pudo crear el carrito: " . $this->db->error());
        return $id;
    }

    public function cerrar() {
        return $this->db->actualizar("
            UPDATE carrito SET activo = 0
            WHERE id_carrito = '{$this->id_carrito}'
        ");
    }

    public function traer_carrito_activo($id_usuario) {
        $result = $this->db->consultar("
            SELECT * FROM carrito 
            WHERE rela_usuario = '{$id_usuario}' AND activo = 1
            LIMIT 1
        ");
        return $result[0] ?? null;
    }

    public function traer_items($id_carrito) {
        return $this->db->consultar("
            SELECT 
                ci.*,
                CASE 
                    WHEN ci.tipo_servicio = 'hotel' THEN th.nombre
                    WHEN ci.tipo_servicio = 'transporte' THEN t.nombre_servicio
                    WHEN ci.tipo_servicio = 'tour' THEN g.nombre_tour
                    ELSE ci.id_servicio
                END AS nombre_servicio,
                CASE 
                    WHEN ci.tipo_servicio = 'tour' THEN ci.fecha_inicio
                    WHEN ci.tipo_servicio = 'hotel' THEN CONCAT(ci.fecha_inicio, ' → ', ci.fecha_fin)
                    ELSE NULL
                END AS fecha_tour
            FROM carrito_items ci
            LEFT JOIN hotel_habitaciones hh 
                ON ci.tipo_servicio = 'hotel' AND ci.id_servicio = hh.id_hotel_habitacion
            LEFT JOIN tipos_habitacion th 
                ON hh.rela_tipo_habitacion = th.id_tipo_habitacion
            LEFT JOIN transporte t 
                ON ci.tipo_servicio = 'transporte' AND ci.id_servicio = t.id_transporte
            LEFT JOIN tours g 
                ON ci.tipo_servicio = 'tour' AND ci.id_servicio = g.id_tour
            WHERE ci.rela_carrito = '$id_carrito'
            ORDER BY ci.id_item ASC
        ");
    }

    public function contar_items($id_usuario) {
        $carrito = $this->traer_carrito_activo($id_usuario);
        if (!$carrito) return 0;

        $result = $this->db->consultar("
            SELECT COUNT(*) AS total 
            FROM carrito_items 
            WHERE rela_carrito = '{$carrito['id_carrito']}'
        ");
        return $result[0]['total'] ?? 0;
    }

    public function listar_items_directo($id_carrito) {
        return $this->db->consultar("SELECT * FROM carrito_items WHERE rela_carrito = '$id_carrito'");
    }

    public function limpiar_carrito_usuario($id_usuario) {
        $carrito = $this->traer_carrito_activo($id_usuario);
        if (!$carrito) return false;
        $id_carrito = $carrito['id_carrito'];
        $this->db->actualizar("DELETE FROM carrito_items WHERE rela_carrito = '$id_carrito'");
        $this->db->actualizar("DELETE FROM carrito WHERE id_carrito = '$id_carrito'");
        return true;
    }



    // Setters
    public function setId_carrito($id) { $this->id_carrito = $id; return $this; }
    public function setId_usuario($id) { $this->rela_usuario = $id; return $this; }
    public function setActivo($activo = 1) { $this->activo = $activo; return $this; }
}
?>
