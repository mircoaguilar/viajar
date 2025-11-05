<?php
require_once('conexion.php');

class Transporte_Rutas {
    private $id_ruta;
    private $nombre;
    private $trayecto;
    private $rela_ciudad_origen;
    private $rela_ciudad_destino;
    private $duracion;
    private $descripcion;
    private $precio_por_persona;
    private $rela_transporte;
    private $activo;

    public function __construct(
        $id_ruta = '',
        $nombre = '',
        $trayecto = '',
        $rela_ciudad_origen = '',
        $rela_ciudad_destino = '',
        $duracion = '',
        $descripcion = '',
        $precio_por_persona = 0,
        $rela_transporte = '',
        $activo = 1
    ) {
        $this->id_ruta = $id_ruta;
        $this->nombre = $nombre;
        $this->trayecto = $trayecto;
        $this->rela_ciudad_origen = $rela_ciudad_origen;
        $this->rela_ciudad_destino = $rela_ciudad_destino;
        $this->duracion = $duracion;
        $this->descripcion = $descripcion;
        $this->precio_por_persona = $precio_por_persona;
        $this->rela_transporte = $rela_transporte;
        $this->activo = $activo;
    }

    public function traer_rutas_por_proveedor($id_proveedor) {
        $conexion = new Conexion();
        $query = "
            SELECT r.*, t.nombre_servicio, c1.nombre AS ciudad_origen, c2.nombre AS ciudad_destino
            FROM transporte_rutas r
            JOIN transporte t ON r.rela_transporte = t.id_transporte
            JOIN ciudades c1 ON r.rela_ciudad_origen = c1.id_ciudad
            JOIN ciudades c2 ON r.rela_ciudad_destino = c2.id_ciudad
            WHERE t.rela_proveedor = $id_proveedor
              AND r.activo = 1
            ORDER BY r.id_ruta DESC
        ";
        return $conexion->consultar($query);
    }

    public function traer_por_id($id_ruta) {
        $conexion = new Conexion();
        $id_ruta = (int)$id_ruta;
        $query = "
            SELECT r.*, t.nombre_servicio, c1.nombre AS ciudad_origen, c2.nombre AS ciudad_destino
            FROM transporte_rutas r
            JOIN transporte t ON r.rela_transporte = t.id_transporte
            JOIN ciudades c1 ON r.rela_ciudad_origen = c1.id_ciudad
            JOIN ciudades c2 ON r.rela_ciudad_destino = c2.id_ciudad
            WHERE r.id_ruta = $id_ruta AND r.activo = 1
            LIMIT 1
        ";
        $res = $conexion->consultar($query);
        return $res ? $res[0] : null;
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $nombre = $mysqli->real_escape_string($this->nombre);
        $trayecto = $mysqli->real_escape_string($this->trayecto);
        $origen = (int)$this->rela_ciudad_origen;
        $destino = (int)$this->rela_ciudad_destino;

        $duracion = trim($this->duracion);
        if (strlen($duracion) === 5) {
            $duracion .= ':00';
        }
        $duracion = $mysqli->real_escape_string($duracion);

        $descripcion = $mysqli->real_escape_string($this->descripcion);
        $precio = floatval($this->precio_por_persona);
        $transporte = (int)$this->rela_transporte;

        $query = "INSERT INTO transporte_rutas 
            (nombre, trayecto, rela_ciudad_origen, rela_ciudad_destino, duracion, descripcion, precio_por_persona, rela_transporte, activo)
            VALUES ('$nombre', '$trayecto', $origen, $destino, '$duracion', '$descripcion', $precio, $transporte, 1)";

        if (!$mysqli->query($query)) {
            echo json_encode([
                "status" => "error",
                "message" => "Error en INSERT: " . $mysqli->error,
                "sql" => $query
            ]);
            exit;
        }

        echo json_encode([
            "status" => "success",
            "id" => $mysqli->insert_id
        ]);
        exit;
    }

    public function actualizar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id = (int)$this->id_ruta;
        $nombre = $mysqli->real_escape_string($this->nombre);
        $trayecto = $mysqli->real_escape_string($this->trayecto);
        $origen = (int)$this->rela_ciudad_origen;
        $destino = (int)$this->rela_ciudad_destino;
        $duracion = $mysqli->real_escape_string($this->duracion);
        $descripcion = $mysqli->real_escape_string($this->descripcion);
        $precio = floatval($this->precio_por_persona);
        $transporte = (int)$this->rela_transporte;

        $query = "UPDATE transporte_rutas SET
            nombre='$nombre',
            trayecto='$trayecto',
            rela_ciudad_origen=$origen,
            rela_ciudad_destino=$destino,
            duracion='$duracion',
            descripcion='$descripcion',
            precio_por_persona=$precio,
            rela_transporte=$transporte
            WHERE id_ruta=$id";

        return $conexion->actualizar($query);
    }

    public function eliminar_logico() {
        $conexion = new Conexion();
        $id = (int)$this->id_ruta;
        $query = "UPDATE transporte_rutas SET activo=0 WHERE id_ruta=$id";
        return $conexion->actualizar($query);
    }

    public function getId_ruta() { return $this->id_ruta; }
    public function setId_ruta($id_ruta) { $this->id_ruta = $id_ruta; return $this; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; return $this; }

    public function getTrayecto() { return $this->trayecto; }
    public function setTrayecto($trayecto) { $this->trayecto = $trayecto; return $this; }

    public function getRela_ciudad_origen() { return $this->rela_ciudad_origen; }
    public function setRela_ciudad_origen($origen) { $this->rela_ciudad_origen = $origen; return $this; }

    public function getRela_ciudad_destino() { return $this->rela_ciudad_destino; }
    public function setRela_ciudad_destino($destino) { $this->rela_ciudad_destino = $destino; return $this; }

    public function getDuracion() { return $this->duracion; }
    public function setDuracion($duracion) { $this->duracion = $duracion; return $this; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; return $this; }

    public function getPrecio_por_persona() { return $this->precio_por_persona; }
    public function setPrecio_por_persona($precio) { $this->precio_por_persona = $precio; return $this; }

    public function getRela_transporte() { return $this->rela_transporte; }
    public function setRela_transporte($transporte) { $this->rela_transporte = $transporte; return $this; }

    public function getActivo() { return $this->activo; }
    public function setActivo($activo) { $this->activo = $activo; return $this; }
}
?>
