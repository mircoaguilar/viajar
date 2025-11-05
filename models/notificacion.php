<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/conexion_pusher.php';
require_once __DIR__ . '/../vendor/autoload.php';

class Notificacion
{
    /**
     * Crea una notificación y la envía por Pusher.
     *
     * @param int $usuario ID del usuario destinatario
     * @param string $titulo Título de la notificación
     * @param string $mensaje Cuerpo o descripción
     * @param string|null $tipo Tipo de notificación (reserva, pago, soporte…)
     * @param array $metadata Datos adicionales opcionales (JSON)
     * @return bool true si se creó correctamente, false si hubo error
     */
    public static function crear($usuario, $titulo, $mensaje, $tipo = null, $metadata = [])
    {
        try {
            $conexion = new Conexion();
            $mysqli = $conexion->getConexion();

            $metadataJson = json_encode($metadata, JSON_UNESCAPED_UNICODE);

            $sql = "INSERT INTO notificaciones (destinario_usuario, titulo, mensaje, tipo, leido, metadata, creado_en)
                    VALUES (?, ?, ?, ?, 0, ?, NOW())";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("issss", $usuario, $titulo, $mensaje, $tipo, $metadataJson);
            $stmt->execute();
            $stmt->close();

            $pusher = ConexionPusher::getPusher();
            $canal = "private-user-{$usuario}";

            $pusher->trigger($canal, 'notificacion-nueva', [
                'titulo' => $titulo,
                'mensaje' => $mensaje,
                'tipo' => $tipo,
                'metadata' => $metadata
            ]);

            return true;

        } catch (Exception $e) {
            error_log("Error al crear notificación: " . $e->getMessage());
            return false;
        }
    }
}
