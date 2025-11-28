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
    private $fecha_alta;
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
        $fecha_alta = null,
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
        $this->fecha_alta = $fecha_alta;
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

    public function traer_transportes_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $id_usuario = (int)$id_usuario;

        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
        if (!$proveedor) return [];

        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "
            SELECT 
                t.*, 
                t.transporte_matricula AS matricula,
                tt.descripcion AS tipo_transporte, 
                p.razon_social AS proveedor_nombre,
                (SELECT COUNT(*) 
                FROM transporte_pisos tp 
                WHERE tp.rela_transporte = t.id_transporte) AS total_pisos,
                (SELECT COUNT(*) 
                FROM transporte_rutas tr 
                WHERE tr.rela_transporte = t.id_transporte 
                AND tr.activo = 1) AS total_rutas,
                (SELECT COUNT(*) 
                FROM viajes v
                JOIN transporte_rutas tr2 ON v.rela_transporte_rutas = tr2.id_ruta
                WHERE tr2.rela_transporte = t.id_transporte
                AND v.activo = 1) AS total_viajes
            FROM transporte t
            JOIN tipo_transporte tt ON t.rela_tipo_transporte = tt.id_tipo_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE t.rela_proveedor = $id_proveedor
            AND t.activo = 1
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
        return $res; 
    }

    public function traer_transporte_por_id($id) {
        $conexion = new Conexion();
        $id = (int)$id;

        $query = "
            SELECT 
                t.*, 
                tt.descripcion AS tipo_transporte, 
                p.razon_social AS proveedor_nombre
            FROM transporte t
            LEFT JOIN tipo_transporte tt ON t.rela_tipo_transporte = tt.id_tipo_transporte
            LEFT JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE t.id_transporte = $id
            AND t.activo = 1
            LIMIT 1
        ";

        $resultado = $conexion->consultar($query);
        return $resultado ? $resultado[0] : null;
    }

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
                    nombre_servicio, descripcion, imagen_principal, rela_proveedor, activo, fecha_alta)
                  VALUES
                    ('$matricula', $capacidad, $tipo,
                    '$nombre', '$desc', '$imagen', $id_proveedor, 1, NOW())";

        if ($mysqli->query($query)) {
            return $mysqli->insert_id;
        } else {
            return false;
        }
    }

    public function guardarConPisos($pisos = []) {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        try {
            $mysqli->begin_transaction();

            $id_transporte = $this->guardar();
            if (!$id_transporte) {
                throw new Exception("Error al guardar transporte.");
            }

            foreach ($pisos as $piso) {
                $numero = (int)$piso['numero_piso'];
                $filas = (int)$piso['filas'];
                $asientos = (int)$piso['asientos_por_fila'];

                $queryPiso = "
                    INSERT INTO transporte_pisos (rela_transporte, numero_piso, filas, asientos_por_fila)
                    VALUES ($id_transporte, $numero, $filas, $asientos)
                ";
                if (!$mysqli->query($queryPiso)) {
                    throw new Exception("Error al guardar piso: " . $mysqli->error);
                }
            }

            $mysqli->commit();
            return $id_transporte;
        } catch (Exception $e) {
            $mysqli->rollback();
            error_log("Error en guardarConPisos: " . $e->getMessage());
            return false;
        }
    }

   public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $actual = $this->traer_transporte($this->id_transporte);
        if (!$actual) return false;

        $estado_actual = $actual[0]['estado_revision'];

        $matricula = $mysqli->real_escape_string($this->transporte_matricula ?? '');
        $capacidad = (int)($this->transporte_capacidad ?? 0);
        $tipo = (int)($this->rela_tipo_transporte ?? 0);
        $nombre = $mysqli->real_escape_string($this->nombre_servicio ?? '');
        $desc = $mysqli->real_escape_string($this->descripcion ?? '');

        if ($tipo <= 0) {
            return false;
        }

        $imagen = !empty($this->imagen_principal)
            ? ", imagen_principal='" . $mysqli->real_escape_string($this->imagen_principal) . "'"
            : "";

        if ($estado_actual === 'rechazado') {
            $revision_sql = ",
                estado_revision='pendiente',
                motivo_rechazo=NULL,
                fecha_revision=NULL,
                revisado_por=NULL
            ";
        } else {
            $revision_sql = "";
        }

        $query = "
            UPDATE transporte SET
                transporte_matricula = '$matricula',
                transporte_capacidad = $capacidad,
                rela_tipo_transporte = $tipo,
                nombre_servicio = '$nombre',
                descripcion = '$desc'
                $imagen
                $revision_sql
            WHERE id_transporte = " . (int)$this->id_transporte;

        return $conexion->actualizar($query);
    }

    public function es_propietario_de_ruta($id_ruta, $id_usuario){
        $conexion = new Conexion();
        $id_ruta = (int)$id_ruta;
        $id_usuario = (int)$id_usuario;

        $query = "
            SELECT r.id_ruta
            FROM transporte_rutas r
            JOIN transporte t ON r.rela_transporte = t.id_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE r.id_ruta = $id_ruta
            AND p.rela_usuario = $id_usuario
            LIMIT 1
        ";

        $res = $conexion->consultar($query);
        return !empty($res);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $query = "UPDATE transporte SET activo = 0 WHERE id_transporte = " . (int)$this->id_transporte;
        return $conexion->actualizar($query);
    }

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



    public function buscar($origen, $destino, $desde, $hasta) {
        $conexion = new Conexion();
        $query = "
            SELECT viajes.id_viajes, 
                viajes.viaje_fecha, 
                viajes.hora_salida, 
                viajes.hora_llegada,
                c1.nombre AS origen, 
                c2.nombre AS destino,
                transporte.nombre_servicio, 
                transporte_rutas.precio_por_persona, 
                transporte.imagen_principal
            FROM viajes
            JOIN transporte_rutas 
                ON viajes.rela_transporte_rutas = transporte_rutas.id_ruta
            JOIN transporte 
                ON transporte_rutas.rela_transporte = transporte.id_transporte
            JOIN ciudades c1 
                ON transporte_rutas.rela_ciudad_origen = c1.id_ciudad
            JOIN ciudades c2 
                ON transporte_rutas.rela_ciudad_destino = c2.id_ciudad
            WHERE viajes.activo = 1
        ";

        if ($origen) {
            $query .= " AND transporte_rutas.rela_ciudad_origen = $origen";
        }

        if ($destino) {
            $query .= " AND transporte_rutas.rela_ciudad_destino = $destino";
        }

        if ($desde) {
            $query .= " AND viajes.viaje_fecha >= '$desde'";
        }

        if ($hasta) {
            $query .= " AND viajes.viaje_fecha <= '$hasta'";
        }

        $query .= " ORDER BY viajes.viaje_fecha ASC";

        return $conexion->consultar($query);
    }


    public function traer_rutas_por_usuario($id_usuario) {
        $conexion = new Conexion();
        $id_usuario = (int)$id_usuario;
        $proveedorModel = new Proveedor();
        $proveedor = $proveedorModel->obtenerPorUsuario($id_usuario);
        if (!$proveedor) return [];

        $id_proveedor = (int)$proveedor['id_proveedores'];

        $query = "
            SELECT r.*, 
                t.nombre_servicio AS transporte_nombre, 
                t.transporte_matricula, 
                tt.descripcion AS tipo_transporte
            FROM transporte_rutas r
            JOIN transporte t ON r.rela_transporte = t.id_transporte
            JOIN tipo_transporte tt ON t.rela_tipo_transporte = tt.id_tipo_transporte
            WHERE t.rela_proveedor = $id_proveedor
            AND r.activo = 1
            ORDER BY r.id_ruta DESC
        ";

        return $conexion->consultar($query);
    }

    public function traer_por_proveedor($id_proveedor){
        $conexion = new Conexion();
        $id_proveedor = (int)$id_proveedor;

        $query = "
            SELECT 
                t.*, 
                t.transporte_matricula AS matricula,
                tt.descripcion AS tipo_transporte, 
                p.razon_social AS proveedor_nombre,
                (SELECT COUNT(*) 
                    FROM transporte_pisos tp 
                    WHERE tp.rela_transporte = t.id_transporte) AS total_pisos,
                (SELECT COUNT(*) 
                    FROM transporte_rutas tr 
                    WHERE tr.rela_transporte = t.id_transporte 
                    AND tr.activo = 1) AS total_rutas,
                (SELECT COUNT(*) 
                    FROM viajes v
                    JOIN transporte_rutas tr2 ON v.rela_transporte_rutas = tr2.id_ruta
                    WHERE tr2.rela_transporte = t.id_transporte
                    AND v.activo = 1) AS total_viajes
            FROM transporte t
            JOIN tipo_transporte tt ON t.rela_tipo_transporte = tt.id_tipo_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE t.rela_proveedor = $id_proveedor
            AND t.activo = 1
            ORDER BY t.id_transporte DESC
        ";

        return $conexion->consultar($query);
    }

    public function traer_viaje_por_id($id_viaje) {
        $conexion = new Conexion();
        $id_viaje = (int)$id_viaje;

        $query = "
            SELECT 
                v.*,

                tr.id_ruta,
                tr.nombre AS ruta_nombre,
                tr.trayecto,
                tr.rela_ciudad_origen,
                tr.rela_ciudad_destino,
                tr.duracion,
                tr.descripcion AS ruta_descripcion,
                tr.precio_por_persona,
                tr.rela_transporte,

                t.id_transporte,
                t.nombre_servicio AS transporte_nombre,
                t.transporte_matricula,

                tt.descripcion AS tipo_transporte,

                c1.nombre AS origen_nombre,
                c2.nombre AS destino_nombre

            FROM viajes v

            INNER JOIN transporte_rutas tr 
                ON v.rela_transporte_rutas = tr.id_ruta

            INNER JOIN transporte t 
                ON tr.rela_transporte = t.id_transporte

            LEFT JOIN tipo_transporte tt 
                ON t.rela_tipo_transporte = tt.id_tipo_transporte

            LEFT JOIN ciudades c1 
                ON tr.rela_ciudad_origen = c1.id_ciudad

            LEFT JOIN ciudades c2 
                ON tr.rela_ciudad_destino = c2.id_ciudad

            WHERE v.id_viajes = $id_viaje
            AND v.activo = 1

            LIMIT 1
        ";

        $resultado = $conexion->consultar($query);
        return $resultado ? $resultado[0] : null;
    }


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

    public function getFecha_alta() { return $this->fecha_alta; }
    public function setFecha_alta($f) { $this->fecha_alta = $f; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($a) { $this->activo = $a; return $this; }

    public function getEstado_revision() { return $this->estado_revision; }
    public function setEstado_revision($e) { $this->estado_revision = $e; return $this; }

    public function getMotivo_rechazo() { return $this->motivo_rechazo; }
    public function setMotivo_rechazo($m) { $this->motivo_rechazo = $m; return $this; }

    public function getFecha_revision() { return $this->fecha_revision; }
    public function setFecha_revision($f) { $this->fecha_revision = $f; return $this; }

    public function getRevisado_por() { return $this->revisado_por; }
    public function setRevisado_por($r) { $this->revisado_por = $r; return $this; }
}
?>
