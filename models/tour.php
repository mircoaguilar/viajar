<?php
require_once('conexion.php');
require_once('proveedor.php');

class Tour {
    private $id_tour;
    private $nombre_tour;
    private $descripcion;
    private $duracion_horas;
    private $precio_por_persona;
    private $hora_encuentro;
    private $lugar_encuentro;
    private $imagen_principal;
    private $rela_proveedor;
    private $activo;
    private $created_at;

    public function __construct(
        $id_tour = '',
        $nombre_tour = '',
        $descripcion = '',
        $duracion_horas = '',
        $precio_por_persona = '',
        $hora_encuentro = '',
        $lugar_encuentro = '',
        $imagen_principal = '',
        $rela_proveedor = '',
        $activo = 1,
        $created_at = ''
    ) {
        $this->id_tour = $id_tour;
        $this->nombre_tour = $nombre_tour;
        $this->descripcion = $descripcion;
        $this->duracion_horas = $duracion_horas;
        $this->precio_por_persona = $precio_por_persona;
        $this->hora_encuentro = $hora_encuentro;
        $this->lugar_encuentro = $lugar_encuentro;
        $this->imagen_principal = $imagen_principal;
        $this->rela_proveedor = $rela_proveedor;
        $this->activo = $activo;
        $this->created_at = $created_at;
    }

    public function traer_tours() {
        $conexion = new Conexion();
        $query = "SELECT t.*, p.id_proveedores 
                  FROM tours t
                  JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
                  WHERE t.activo = 1
                  ORDER BY t.id_tour DESC";
        return $conexion->consultar($query);
    }

    public function traer_tours_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $proveedor = (new Proveedor())->obtenerPorUsuario((int)$id_usuario);
        if (!$proveedor) return [];
        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "SELECT t.*, p.razon_social AS proveedor_nombre
                  FROM tours t
                  JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
                  WHERE t.rela_proveedor = $id_proveedor
                    AND t.activo = 1
                  ORDER BY t.id_tour DESC";
        return $conexion->consultar($query);
    }

    public function traer_tour($id) {
        $conexion = new Conexion();
        $res = $conexion->consultar("SELECT * FROM tours WHERE id_tour = " . (int)$id . " AND activo = 1");
        return $res ? $res[0] : null;
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $nombre = $mysqli->real_escape_string($this->nombre_tour);
        $desc = $mysqli->real_escape_string($this->descripcion);
        $precio = floatval($this->precio_por_persona);
        $lugar = $mysqli->real_escape_string($this->lugar_encuentro);
        $imagen = $mysqli->real_escape_string($this->imagen_principal);
        $duracion = $this->duracion_horas ?: '00:00:00';
        $hora = $this->hora_encuentro ?: null;

        $proveedor = (new Proveedor())->obtenerPorUsuario($_SESSION['id_usuarios']);
        if (!$proveedor) throw new Exception("No se encontró proveedor asociado al usuario.");
        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "INSERT INTO tours
                    (nombre_tour, descripcion, duracion_horas, precio_por_persona,
                     hora_encuentro, lugar_encuentro, imagen_principal, rela_proveedor, activo)
                  VALUES
                    ('$nombre', '$desc', '$duracion', $precio,
                     " . ($hora ? "'$hora'" : "NULL") . ",
                     '$lugar', '$imagen', $id_proveedor, 1)";
        return $mysqli->query($query) ? $mysqli->insert_id : false;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $nombre = $mysqli->real_escape_string($this->nombre_tour);
        $desc = $mysqli->real_escape_string($this->descripcion);
        $precio = floatval($this->precio_por_persona);
        $lugar = $mysqli->real_escape_string($this->lugar_encuentro);
        $duracion = $this->duracion_horas ?: '00:00:00';
        $hora = $this->hora_encuentro ?: null;
        $imagen = $this->imagen_principal ? ", imagen_principal='" . $mysqli->real_escape_string($this->imagen_principal) . "'" : "";

        $query = "UPDATE tours SET
                    nombre_tour = '$nombre',
                    descripcion = '$desc',
                    duracion_horas = '$duracion',
                    precio_por_persona = $precio,
                    hora_encuentro = " . ($hora ? "'$hora'" : "NULL") . ",
                    lugar_encuentro = '$lugar'
                    $imagen
                  WHERE id_tour = " . (int)$this->id_tour;

        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        return $conexion->actualizar("UPDATE tours SET activo = 0 WHERE id_tour = " . (int)$this->id_tour);
    }

    public function verificar_propietario($id_tour, $id_proveedor) {
        $conexion = new Conexion();
        $res = $conexion->consultar("SELECT id_tour FROM tours WHERE id_tour = " . (int)$id_tour . " AND rela_proveedor = " . (int)$id_proveedor . " AND activo = 1 LIMIT 1");
        return !empty($res);
    }

    public function buscar($destino = '') {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $destino = $mysqli->real_escape_string($destino);
        $query = "SELECT t.* FROM tours t WHERE t.activo = 1";
        if ($destino) $query .= " AND t.nombre_tour LIKE '%$destino%'";
        $query .= " ORDER BY t.id_tour DESC";

        return $conexion->consultar($query);
    }

    public function buscar_disponibles($id_ciudad = '', $fecha = '') {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $where = " WHERE t.activo = 1 ";

        if ($id_ciudad) {
            $id_ciudad = (int)$id_ciudad;
            $where .= " AND t.rela_ciudad = $id_ciudad ";
        }

        if ($fecha) {
            $fecha = $mysqli->real_escape_string($fecha);
            $where .= " AND EXISTS (
                            SELECT 1 FROM tour_stock ts
                            WHERE ts.rela_tour = t.id_tour
                            AND ts.fecha = '$fecha'
                            AND ts.cupos_disponibles > 0
                        )";
        }

        $query = "SELECT t.* FROM tours t $where ORDER BY t.nombre_tour ASC";
        return $conexion->consultar($query);
    }


    // Getters & Setters
    public function getId_tour() { return $this->id_tour; }
    public function setId_tour($id) { $this->id_tour = $id; return $this; }

    public function getNombre_tour() { return $this->nombre_tour; }
    public function setNombre_tour($n) { $this->nombre_tour = $n; return $this; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($d) { $this->descripcion = $d; return $this; }

    public function getDuracion_horas() { return $this->duracion_horas; }
    public function setDuracion_horas($d) { $this->duracion_horas = $d; return $this; }

    public function getPrecio_por_persona() { return $this->precio_por_persona; }
    public function setPrecio_por_persona($p) { $this->precio_por_persona = $p; return $this; }

    public function getHora_encuentro() { return $this->hora_encuentro; }
    public function setHora_encuentro($h) { $this->hora_encuentro = $h; return $this; }

    public function getLugar_encuentro() { return $this->lugar_encuentro; }
    public function setLugar_encuentro($l) { $this->lugar_encuentro = $l; return $this; }

    public function getImagen_principal() { return $this->imagen_principal; }
    public function setImagen_principal($img) { $this->imagen_principal = $img; return $this; }

    public function getRela_proveedor() { return $this->rela_proveedor; }
    public function setRela_proveedor($p) { $this->rela_proveedor = $p; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($a) { $this->activo = $a; return $this; }

    public function getCreated_at() { return $this->created_at; }
}
?>
