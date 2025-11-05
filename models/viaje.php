<?php

require_once('conexion.php');

class Viaje {
    private $id_viajes;
    private $viaje_fecha;
    private $activo;
    private $rela_transporte_rutas;
    private $hora_salida;
    private $hora_llegada;

    public function __construct(
        $id_viajes = '',
        $viaje_fecha = '',
        $activo = 1,
        $rela_transporte_rutas = '',
        $hora_salida = '',
        $hora_llegada = ''
    ) {
        $this->id_viajes = $id_viajes;
        $this->viaje_fecha = $viaje_fecha;
        $this->activo = $activo;
        $this->rela_transporte_rutas = $rela_transporte_rutas;
        $this->hora_salida = $hora_salida;
        $this->hora_llegada = $hora_llegada;
    }

    public function traer_viajes_proximos($limit = 5) {
        $conexion = new Conexion();
        $hoy = date('Y-m-d');
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
              AND viajes.viaje_fecha >= '$hoy'
            ORDER BY viajes.viaje_fecha ASC
            LIMIT $limit
        ";
        return $conexion->consultar($query);
    }

    public function traer_viaje_por_id($id) {
        $conexion = new Conexion();
        $query = "
            SELECT viajes.id_viajes, 
                viajes.viaje_fecha, 
                viajes.hora_salida, 
                viajes.hora_llegada,
                viajes.rela_transporte_rutas,  -- << agregada
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
            WHERE viajes.id_viajes = $id 
            AND viajes.activo = 1
            LIMIT 1
        ";
        $result = $conexion->consultar($query);
        return !empty($result) ? $result[0] : null;
    }

    public function guardar() {
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $fecha = $mysqli->real_escape_string($this->viaje_fecha);
        $activo = (int)$this->activo;
        $ruta = (int)$this->rela_transporte_rutas;
        $hora_salida = $mysqli->real_escape_string($this->hora_salida);
        $hora_llegada = $mysqli->real_escape_string($this->hora_llegada);

        $query = "INSERT INTO viajes 
            (viaje_fecha, activo, rela_transporte_rutas, hora_salida, hora_llegada) 
            VALUES ('$fecha', $activo, $ruta, '$hora_salida', '$hora_llegada')";

        return $conexion->insertar($query); 
    }

    public function actualizar() {
        if (!$this->id_viajes) return false;

        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id = (int)$this->id_viajes;
        $fecha = $mysqli->real_escape_string($this->viaje_fecha);
        $activo = (int)$this->activo;
        $ruta = (int)$this->rela_transporte_rutas;
        $hora_salida = $mysqli->real_escape_string($this->hora_salida);
        $hora_llegada = $mysqli->real_escape_string($this->hora_llegada);

        $query = "UPDATE viajes SET
            viaje_fecha='$fecha',
            activo=$activo,
            rela_transporte_rutas=$ruta,
            hora_salida='$hora_salida',
            hora_llegada='$hora_llegada'
            WHERE id_viajes=$id";

        return $conexion->actualizar($query);
    }

    public function traer_primer_viaje_por_transporte($idTransporte) {
        $conexion = new Conexion();
        $hoy = date('Y-m-d');

        $query = "
            SELECT viajes.id_viajes, 
                viajes.viaje_fecha, 
                viajes.hora_salida, 
                viajes.hora_llegada,
                viajes.rela_transporte_rutas,  -- << agregada
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
            WHERE transporte.id_transporte = $idTransporte
            AND viajes.activo = 1
            AND viajes.viaje_fecha >= '$hoy'
            ORDER BY viajes.viaje_fecha ASC
            LIMIT 1
        ";
        $result = $conexion->consultar($query);
        return !empty($result) ? $result[0] : null;
    }


    public function eliminar_logico() {
        if (!$this->id_viajes) return false;

        $conexion = new Conexion();
        $id = (int)$this->id_viajes;

        $query = "UPDATE viajes SET activo=0 WHERE id_viajes=$id";

        return $conexion->actualizar($query);
    }

    public function getId_viajes() {
        return $this->id_viajes;
    }

    public function setId_viajes($id_viajes) {
        $this->id_viajes = $id_viajes;
        return $this;
    }

    public function getViaje_fecha() {
        return $this->viaje_fecha;
    }

    public function setViaje_fecha($viaje_fecha) {
        $this->viaje_fecha = $viaje_fecha;
        return $this;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
        return $this;
    }

    public function getRela_transporte_rutas() {
        return $this->rela_transporte_rutas;
    }

    public function setRela_transporte_rutas($rela_transporte_rutas) {
        $this->rela_transporte_rutas = $rela_transporte_rutas;
        return $this;
    }

    public function getHora_salida() {
        return $this->hora_salida;
    }

    public function setHora_salida($hora_salida) {
        $this->hora_salida = $hora_salida;
        return $this;
    }

    public function getHora_llegada() {
        return $this->hora_llegada;
    }

    public function setHora_llegada($hora_llegada) {
        $this->hora_llegada = $hora_llegada;
        return $this;
    }
}

?>
