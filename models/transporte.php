<?php
require_once('conexion.php');
require_once('proveedor.php');

class Transporte {
    private $id_transporte;
    private $transporte_matricula;
    private $transporte_capacidad;
    private $rela_tipo_transporte;
    private $nombre_servicio;
    private $descripcion;
    private $imagen_principal;
    private $rela_proveedor;
    private $activo;
    private $estado_revision;
    private $motivo_rechazo;
    private $fecha_revision;
    private $revisado_por;

    public function __construct(
        $id_transporte = '',
        $transporte_matricula = '',
        $transporte_capacidad = '',
        $rela_tipo_transporte = '',
        $nombre_servicio = '',
        $descripcion = '',
        $imagen_principal = '',
        $rela_proveedor = '',
        $activo = 1,
        $estado_revision = 'pendiente',
        $motivo_rechazo = null,
        $fecha_revision = null,
        $revisado_por = null
    ) {
        $this->id_transporte = $id_transporte;
        $this->transporte_matricula = $transporte_matricula;
        $this->transporte_capacidad = $transporte_capacidad;
        $this->rela_tipo_transporte = $rela_tipo_transporte;
        $this->nombre_servicio = $nombre_servicio;
        $this->descripcion = $descripcion;
        $this->imagen_principal = $imagen_principal;
        $this->rela_proveedor = $rela_proveedor;
        $this->activo = $activo;
        $this->estado_revision = $estado_revision;
        $this->motivo_rechazo = $motivo_rechazo;
        $this->fecha_revision = $fecha_revision;
        $this->revisado_por = $revisado_por;
    }

    public function traer_transportes() {
        $conexion = new Conexion();
        $query = "
            SELECT t.*, tt.descripcion AS tipo_transporte, p.razon_social AS proveedor_nombre
            FROM transporte t
            JOIN tipo_transporte tt ON t.rela_tipo_transporte = tt.id_tipo_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE t.activo = 1
            ORDER BY t.id_transporte DESC
        ";
        return $conexion->consultar($query);
    }

    public function traer_transporte($id) {
        $conexion = new Conexion();
        $id = (int)$id;
        $query = "
            SELECT t.*, tt.descripcion AS tipo_transporte, p.razon_social AS proveedor_nombre
            FROM transporte t
            JOIN tipo_transporte tt ON t.rela_tipo_transporte = tt.id_tipo_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE t.id_transporte = $id AND t.activo = 1
        ";
        $res = $conexion->consultar($query);
        return $res ? $res[0] : null;
    }

    // Guardar transporte
    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $matricula = $mysqli->real_escape_string($this->transporte_matricula);
        $capacidad = (int)$this->transporte_capacidad;
        $tipo = (int)$this->rela_tipo_transporte;
        $nombre = $mysqli->real_escape_string($this->nombre_servicio);
        $desc = $mysqli->real_escape_string($this->descripcion);
        $imagen = $mysqli->real_escape_string($this->imagen_principal);

        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($_SESSION['id_usuarios']);
        if (!$proveedor) throw new Exception("No se encontró proveedor asociado al usuario.");
        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "INSERT INTO transporte 
                    (transporte_matricula, transporte_capacidad, rela_tipo_transporte, 
                     nombre_servicio, descripcion, imagen_principal, rela_proveedor, activo)
                  VALUES
                    ('$matricula', $capacidad, $tipo, '$nombre', '$desc', '$imagen', $id_proveedor, 1)";

        if ($mysqli->query($query)) {
            return $mysqli->insert_id;
        } else {
            return false;
        }
    }

    // Actualizar transporte
    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $matricula = $mysqli->real_escape_string($this->transporte_matricula);
        $capacidad = (int)$this->transporte_capacidad;
        $tipo = (int)$this->rela_tipo_transporte;
        $nombre = $mysqli->real_escape_string($this->nombre_servicio);
        $desc = $mysqli->real_escape_string($this->descripcion);
        $imagen = $this->imagen_principal ? ", imagen_principal='" . $mysqli->real_escape_string($this->imagen_principal) . "'" : "";

        $query = "UPDATE transporte SET
                    transporte_matricula = '$matricula',
                    transporte_capacidad = $capacidad,
                    rela_tipo_transporte = $tipo,
                    nombre_servicio = '$nombre',
                    descripcion = '$desc'
                    $imagen
                  WHERE id_transporte = " . (int)$this->id_transporte;

        return $conexion->actualizar($query);
    }

    // Eliminar lógico
    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE transporte SET activo = 0 WHERE id_transporte = " . (int)$this->id_transporte;
        return $conexion->actualizar($query);
    }

    // Verificar propietario
    public function verificar_propietario($id_transporte, $id_proveedor) {
        $conexion = new Conexion();
        $id_transporte = (int)$id_transporte;
        $id_proveedor = (int)$id_proveedor;
        $query = "SELECT id_transporte 
                  FROM transporte 
                  WHERE id_transporte = $id_transporte 
                    AND rela_proveedor = $id_proveedor
                    AND activo = 1
                  LIMIT 1";
        $res = $conexion->consultar($query);
        return !empty($res);
    }

    public function buscar($destino = '', $desde = '', $hasta = '') {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();
        $destino = $mysqli->real_escape_string($destino);

        $query = "
            SELECT t.id_transporte, t.nombre_servicio, t.imagen_principal,
                r.precio_por_persona, r.fecha_salida, r.hora_salida,
                c1.nombre AS origen, c2.nombre AS destino
            FROM transporte t
            LEFT JOIN transporte_rutas r ON t.id_transporte = r.rela_transporte
            LEFT JOIN ciudades c1 ON r.rela_origen = c1.id_ciudad
            LEFT JOIN ciudades c2 ON r.rela_destino = c2.id_ciudad
            WHERE t.activo = 1
        ";

        if ($destino) {
            $query .= " AND (c1.nombre LIKE '%$destino%' OR c2.nombre LIKE '%$destino%' OR t.nombre_servicio LIKE '%$destino%')";
        }
        if ($desde) {
            $desde = $mysqli->real_escape_string($desde);
            $query .= " AND r.fecha_salida >= '$desde'";
        }
        if ($hasta) {
            $hasta = $mysqli->real_escape_string($hasta);
            $query .= " AND r.fecha_llegada <= '$hasta'";
        }

        $query .= " ORDER BY r.fecha_salida ASC";

        return $conexion->consultar($query);
    }


    // Getters & Setters
    public function getId_transporte() { return $this->id_transporte; }
    public function setId_transporte($id) { $this->id_transporte = $id; return $this; }

    public function getTransporte_matricula() { return $this->transporte_matricula; }
    public function setTransporte_matricula($m) { $this->transporte_matricula = $m; return $this; }

    public function getTransporte_capacidad() { return $this->transporte_capacidad; }
    public function setTransporte_capacidad($c) { $this->transporte_capacidad = $c; return $this; }

    public function getRela_tipo_transporte() { return $this->rela_tipo_transporte; }
    public function setRela_tipo_transporte($t) { $this->rela_tipo_transporte = $t; return $this; }

    public function getNombre_servicio() { return $this->nombre_servicio; }
    public function setNombre_servicio($n) { $this->nombre_servicio = $n; return $this; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($d) { $this->descripcion = $d; return $this; }

    public function getImagen_principal() { return $this->imagen_principal; }
    public function setImagen_principal($img) { $this->imagen_principal = $img; return $this; }

    public function getRela_proveedor() { return $this->rela_proveedor; }
    public function setRela_proveedor($p) { $this->rela_proveedor = $p; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($a) { $this->activo = $a; return $this; }
}
?>
