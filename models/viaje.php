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
            SELECT 
                viajes.id_viajes, 
                viajes.viaje_fecha, 
                viajes.hora_salida, 
                viajes.hora_llegada,
                viajes.rela_transporte_rutas,
                transporte_rutas.rela_transporte,   -- << AGREGADO
                viajes.activo,
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

    public function traer_viajes_futuros_por_transporte($idTransporte) {
        $conexion = new Conexion();
        $hoy = date('Y-m-d');

        $query = "
            SELECT viajes.id_viajes, 
                viajes.viaje_fecha, 
                viajes.hora_salida, 
                viajes.hora_llegada,
                viajes.rela_transporte_rutas,
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
        ";

        $result = $conexion->consultar($query);
        return !empty($result) ? $result : [];
    }


    public function traer_viajes_por_ruta($id_ruta){
        $conexion = new Conexion();
        $mysqli = $conexion->getConexion();

        $id_ruta = (int)$id_ruta;

        $query = "
            SELECT 
                v.id_viajes,
                v.viaje_fecha,
                v.hora_salida,
                v.hora_llegada,
                v.activo,

                t.transporte_capacidad AS asientos_totales,

                (
                    SELECT COUNT(*) 
                    FROM viaje_asientos va 
                    WHERE va.rela_viaje = v.id_viajes
                    AND va.ocupado = 1
                ) AS asientos_reservados,

                (
                    t.transporte_capacidad -
                    (
                        SELECT COUNT(*) 
                        FROM viaje_asientos va2 
                        WHERE va2.rela_viaje = v.id_viajes
                        AND va2.ocupado = 1
                    )
                ) AS asientos_disponibles

            FROM viajes v
            INNER JOIN transporte_rutas tr 
                ON tr.id_ruta = v.rela_transporte_rutas
            INNER JOIN transporte t 
                ON t.id_transporte = tr.rela_transporte

            WHERE v.rela_transporte_rutas = $id_ruta
            ORDER BY v.viaje_fecha ASC, v.hora_salida ASC
        ";

        $res = $mysqli->query($query);

        if (!$res) {
            return [];
        }

        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminar_logico() {
        if (!$this->id_viajes) return false;

        $conexion = new Conexion();
        $id = (int)$this->id_viajes;

        $query = "UPDATE viajes SET activo=0 WHERE id_viajes=$id";

        return $conexion->actualizar($query);
    }

    public function cambiar_estado($id, $nuevo_estado) {
        $conexion = new Conexion();
        $id = (int)$id;
        $nuevo_estado = (int)$nuevo_estado;

        $query = "UPDATE viajes SET activo = $nuevo_estado WHERE id_viajes = $id";
        return $conexion->actualizar($query);
    }

    public function traer_viajes_proximos_por_usuario($id_usuario, $limite = 10) {
        $conexion = new Conexion();
        $hoy = date('Y-m-d');
        $id_usuario = (int)$id_usuario;

        $query = "
            SELECT v.id_viajes,
                v.viaje_fecha,
                v.hora_salida,
                v.hora_llegada,
                t.nombre_servicio,
                tr.precio_por_persona,
                t.imagen_principal,
                c1.nombre AS origen,
                c2.nombre AS destino
            FROM viajes v
            JOIN transporte_rutas tr ON v.rela_transporte_rutas = tr.id_ruta
            JOIN transporte t ON tr.rela_transporte = t.id_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            JOIN ciudades c1 ON tr.rela_ciudad_origen = c1.id_ciudad
            JOIN ciudades c2 ON tr.rela_ciudad_destino = c2.id_ciudad
            WHERE p.rela_usuario = $id_usuario
            AND v.activo = 1
            AND v.viaje_fecha >= '$hoy'
            ORDER BY v.viaje_fecha ASC
            LIMIT $limite
        ";

        return $conexion->consultar($query);
    }

    public function top_viajes_mas_reservados_por_usuario($id_usuario, $limite = 5) {
        $conexion = new Conexion();
        $id_usuario = (int)$id_usuario;

        $query = "
            SELECT 
                v.id_viajes,
                t.nombre_servicio AS transporte_nombre,
                tr.trayecto AS ruta_trayecto,
                COUNT(dtv.id_detalle_transporte) AS total
            FROM detalle_reserva_transporte dtv
            JOIN viajes v ON dtv.id_viaje = v.id_viajes
            JOIN transporte_rutas tr ON v.rela_transporte_rutas = tr.id_ruta
            JOIN transporte t ON tr.rela_transporte = t.id_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE p.rela_usuario = $id_usuario
            GROUP BY v.id_viajes
            ORDER BY total DESC
            LIMIT $limite
        ";

        return $conexion->consultar($query);
    }

    public function reservas_por_mes($id_usuario, $anio = null) {
        $conexion = new Conexion();
        $id_usuario = (int)$id_usuario;
        $anio = $anio ?? date('Y');

        $query = "
            SELECT MONTH(drt.fecha_servicio) AS mes, COUNT(drt.id_detalle_transporte) AS total
            FROM detalle_reserva_transporte drt
            JOIN detalle_reservas dr ON dr.id_detalle_reserva = drt.rela_detalle_reserva
            JOIN reservas r ON r.id_reservas = dr.rela_reservas
            JOIN viajes v ON drt.id_viaje = v.id_viajes
            JOIN transporte_rutas tr ON v.rela_transporte_rutas = tr.id_ruta
            JOIN transporte t ON tr.rela_transporte = t.id_transporte
            JOIN proveedores p ON t.rela_proveedor = p.id_proveedores
            WHERE p.rela_usuario = $id_usuario
            AND r.activo = 1
            AND YEAR(drt.fecha_servicio) = $anio
            GROUP BY MONTH(drt.fecha_servicio)
            ORDER BY MONTH(drt.fecha_servicio)
        ";

        return $conexion->consultar($query);
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
