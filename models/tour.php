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
    private $direccion;            
    private $imagen_principal;
    private $rela_proveedor;
    private $activo;
    private $created_at;
    private $estado_revision;
    private $motivo_rechazo;
    private $fecha_revision;
    private $revisado_por;

    public function __construct(
        $id_tour = '',
        $nombre_tour = '',
        $descripcion = '',
        $duracion_horas = '',
        $precio_por_persona = 0,
        $hora_encuentro = null,
        $lugar_encuentro = '',
        $direccion = '',            
        $imagen_principal = '',
        $rela_proveedor = '',
        $activo = 1,
        $created_at = '',
        $estado_revision = 'pendiente',
        $motivo_rechazo = null,
        $fecha_revision = null,
        $revisado_por = null
    ) {
        $this->id_tour = $id_tour;
        $this->nombre_tour = $nombre_tour;
        $this->descripcion = $descripcion;
        $this->duracion_horas = $duracion_horas;
        $this->precio_por_persona = $precio_por_persona;
        $this->hora_encuentro = $hora_encuentro;
        $this->lugar_encuentro = $lugar_encuentro;
        $this->direccion = $direccion;        
        $this->imagen_principal = $imagen_principal;
        $this->rela_proveedor = $rela_proveedor;
        $this->activo = $activo;
        $this->created_at = $created_at;
        $this->estado_revision = $estado_revision;
        $this->motivo_rechazo = $motivo_rechazo;
        $this->fecha_revision = $fecha_revision;
        $this->revisado_por = $revisado_por;
    }

    public function traer_tours() {
        $conexion = new Conexion();
        $query = "SELECT t.*, p.razon_social AS proveedor_nombre
                  FROM tours t
                  JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
                  WHERE t.activo = 1
                  AND t.estado_revision = 'aprobado'
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
                  WHERE t.rela_proveedor = $id_proveedor AND t.activo = 1
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
        $direccion = $mysqli->real_escape_string($this->direccion ?? '');
        $imagen = $mysqli->real_escape_string($this->imagen_principal);
        $duracion = $this->duracion_horas ?: '00:00:00';
        $hora = $this->hora_encuentro ? $mysqli->real_escape_string($this->hora_encuentro) : null;

        $proveedor = (new Proveedor())->obtenerPorUsuario($_SESSION['id_usuarios']);
        if (!$proveedor) throw new Exception("No se encontró proveedor asociado al usuario.");
        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "INSERT INTO tours
                    (nombre_tour, descripcion, duracion_horas, precio_por_persona,
                     hora_encuentro, lugar_encuentro, direccion, imagen_principal, rela_proveedor,
                     activo, estado_revision, created_at)
                  VALUES
                    ('$nombre', '$desc', '$duracion', $precio,
                     " . ($hora ? "'$hora'" : "NULL") . ",
                     '$lugar', '$direccion', '$imagen', $id_proveedor,
                     1, 'pendiente', NOW())";

        return $mysqli->query($query) ? $mysqli->insert_id : false;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $actual = $this->traer_tour($this->id_tour);
        if (!$actual) return false;
        if (empty($this->imagen_principal) && !empty($actual['imagen_principal'])) {
            $this->imagen_principal = $actual['imagen_principal'];
        }

        $estado_actual = $actual['estado_revision'] ?? 'pendiente';

        $toString = function($val) {
            if (is_array($val)) return reset($val); 
            if (is_null($val)) return '';
            return (string)$val;
        };

        $nombre    = $mysqli->real_escape_string($toString($this->nombre_tour));
        $desc      = $mysqli->real_escape_string($toString($this->descripcion));
        $precio    = floatval($this->precio_por_persona);
        $lugar     = $mysqli->real_escape_string($toString($this->lugar_encuentro));
        $direccion = $mysqli->real_escape_string($toString($this->direccion ?? ''));
        $duracion  = $toString($this->duracion_horas) ?: '00:00:00';
        $hora      = $this->hora_encuentro 
                    ? $mysqli->real_escape_string($toString($this->hora_encuentro))
                    : null;
        $imagen_sql = !empty($this->imagen_principal) 
            ? ", imagen_principal='" . $mysqli->real_escape_string($toString($this->imagen_principal)) . "'" 
            : ", imagen_principal=NULL"; 

        $revision_sql = '';
        if ($estado_actual === 'rechazado') {
            $revision_sql = ", estado_revision='pendiente', motivo_rechazo=NULL, fecha_revision=NULL, revisado_por=NULL";
        }

        $query = "
            UPDATE tours SET
                nombre_tour = '$nombre',
                descripcion = '$desc',
                duracion_horas = '$duracion',
                precio_por_persona = $precio,
                hora_encuentro = " . ($hora ? "'$hora'" : "NULL") . ",
                lugar_encuentro = '$lugar',
                direccion = '$direccion'
                $imagen_sql 
                $revision_sql
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

    public function buscar($fecha) {
    $conexion = new Conexion();

    $query = "
        SELECT t.*, 
               p.razon_social AS proveedor_nombre, 
               MIN(st.fecha) AS fecha,  -- Obtener la fecha más temprana para cada tour
               SUM(st.cupos_disponibles) AS total_cupos_disponibles  -- Sumar los cupos disponibles
        FROM tours t
        JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
        LEFT JOIN stock_tour st ON t.id_tour = st.rela_tour
        WHERE t.activo = 1
        AND t.estado_revision = 'aprobado'
        AND st.fecha >= '$fecha'  -- Filtrar por fecha
        AND st.cupos_disponibles > 0  -- Solo mostrar tours con cupos disponibles
        GROUP BY t.id_tour  -- Agrupar por el id del tour
        ORDER BY fecha ASC  -- Ordenar por la fecha más temprana
    ";

    return $conexion->consultar($query);
}



    public function traer_tours_aprobados_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $proveedor = (new Proveedor())->obtenerPorUsuario((int)$id_usuario);
        if (!$proveedor) return [];
        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "SELECT t.*, p.razon_social AS proveedor_nombre
                FROM tours t
                JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
                WHERE t.rela_proveedor = $id_proveedor
                    AND t.activo = 1
                    AND t.estado_revision = 'aprobado'
                ORDER BY t.id_tour DESC";

        return $conexion->consultar($query);
    }

    public function traer_tours_proveedor($id_usuario, $estado = 'aprobado') {
        $conexion = new Conexion();
        $proveedor = (new Proveedor())->obtenerPorUsuario((int)$id_usuario);
        if (!$proveedor) return [];
        $id_proveedor = (int)$proveedor['id_proveedores'];

        $estado_sql = $estado ? " AND t.estado_revision = '$estado'" : "";

        $query = "SELECT t.*, p.razon_social AS proveedor_nombre
                FROM tours t
                JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
                WHERE t.rela_proveedor = $id_proveedor
                    AND t.activo = 1
                    $estado_sql
                ORDER BY t.id_tour DESC";

        return $conexion->consultar($query);
    }

    public function liberarCupo($id_detalle_reserva) {
        $conexion = new Conexion();

        $queryCantidad = "SELECT cantidad FROM detalle_reservas WHERE id_detalle_reserva = $id_detalle_reserva";
        $resCantidad = $conexion->consultar($queryCantidad);
        if(empty($resCantidad)) return false;
        $cantidad = (int)$resCantidad[0]['cantidad'];

        $queryDetallesTour = "SELECT * FROM detalle_reserva_tour WHERE rela_detalle_reserva = $id_detalle_reserva";
        $detallesTour = $conexion->consultar($queryDetallesTour);

        foreach($detallesTour as $det) {
            $idTour = $det['rela_tour'];
            $fecha = $det['fecha'];
            $conexion->actualizar("UPDATE detalle_reserva_tour 
                                SET estado = 'cancelada' 
                                WHERE id_detalle_tour = {$det['id_detalle_tour']}");

            $conexion->actualizar("UPDATE stock_tour 
                                SET cupos_disponibles = cupos_disponibles + $cantidad,
                                    cupos_reservados = cupos_reservados - $cantidad
                                WHERE rela_tour = $idTour AND fecha = '$fecha'");
        }
        return true;
    }


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
    public function getDireccion() { return $this->direccion; }
    public function setDireccion($d) { $this->direccion = $d; return $this; }
    public function getImagen_principal() { return $this->imagen_principal; }
    public function setImagen_principal($img) { $this->imagen_principal = $img; return $this; }
    public function getRela_proveedor() { return $this->rela_proveedor; }
    public function setRela_proveedor($p) { $this->rela_proveedor = $p; return $this; }
    public function getActivo() { return $this->activo; }
    public function setActivo($a) { $this->activo = $a; return $this; }
    public function getEstadoRevision() { return $this->estado_revision; }
    public function setEstadoRevision($e) { $this->estado_revision = $e; return $this; }
    public function getMotivoRechazo() { return $this->motivo_rechazo; }
    public function setMotivoRechazo($m) { $this->motivo_rechazo = $m; return $this; }
    public function getFechaRevision() { return $this->fecha_revision; }
    public function setFechaRevision($f) { $this->fecha_revision = $f; return $this; }
    public function getRevisadoPor() { return $this->revisado_por; }
    public function setRevisadoPor($r) { $this->revisado_por = $r; return $this; }
}
?>
