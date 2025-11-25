<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/proveedor.php';
require_once __DIR__ . '/../models/hotel.php';
require_once __DIR__ . '/../models/transporte.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/proveedores/proveedores.controlador.php';

use Dompdf\Dompdf;

$id_usuario = $_SESSION['id_usuarios'];
$tipo = $_GET['tipo'] ?? 'hotel';

$controlador = new ProveedoresControlador();
$dompdf = new Dompdf();

$html = '<style>
    body { font-family: DejaVu Sans, sans-serif; }
    h1 { text-align:center; margin-bottom: 20px; }
    table { width:100%; border-collapse: collapse; margin-top:15px; }
    th, td { border:1px solid #000; padding:6px; font-size:14px; }
    th { background:#f0f0f0; }
    .no-data { margin-top:20px; font-size:16px; color:#777; }
</style>';

$html .= "<h1>Listado de mis servicios</h1>";

switch ($tipo) {

    case 'hotel':
        $hoteles = $controlador->mis_hoteles($id_usuario);

        $html .= "<h2>Mis Hoteles</h2>";

        if (empty($hoteles)) {
            $html .= '<p class="no-data">No tienes hoteles registrados.</p>';
            break;
        }

        $html .= '<table border="1" cellspacing="0" cellpadding="5">
                    <tr>
                        <th>Nombre</th>
                        <th>Provincia</th>
                        <th>Ciudad</th>
                        <th>Habitaciones</th>
                        <th>Reservas activas</th>
                        <th>Estado</th>
                    </tr>';

        foreach ($hoteles as $h) {

            $estado = ucfirst(strtolower($h['estado_revision'] ?? 'Pendiente'));

            $html .= "<tr>
                        <td>".htmlspecialchars($h['hotel_nombre'])."</td>
                        <td>".htmlspecialchars($h['provincia_nombre'])."</td>
                        <td>".htmlspecialchars($h['ciudad_nombre'])."</td>
                        <td>".(int)$h['total_habitaciones']."</td>
                        <td>".(int)$h['total_reservas_activas']."</td>
                        <td>".$estado."</td>
                    </tr>";
        }

        $html .= "</table>";
        break;


    case 'transporte':
        $transportes = $controlador->mis_transportes($id_usuario);

        $html .= "<h2>Mis Transportes</h2>";

        if (empty($transportes)) {
            $html .= '<p class="no-data">No tienes transportes registrados.</p>';
            break;
        }

        $html .= '<table border="1" cellspacing="0" cellpadding="5">
                    <tr>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Pisos</th>
                        <th>Rutas</th>
                        <th>Viajes</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </tr>';

        foreach ($transportes as $t) {

            $estado = ucfirst(strtolower($t['estado_revision'] ?? 'Pendiente'));

            $html .= "<tr>
                        <td>".htmlspecialchars($t['nombre_servicio'])."</td>
                        <td>".htmlspecialchars($t['tipo_transporte'])."</td>
                        <td>".htmlspecialchars($t['transporte_capacidad'])."</td>
                        <td>".(int)($t['total_pisos'] ?? 0)."</td>
                        <td>".(int)($t['total_rutas'] ?? 0)."</td>
                        <td>".(int)($t['total_viajes'] ?? 0)."</td>
                        <td>".htmlspecialchars($t['descripcion'] ?? '-')."</td>
                        <td>".$estado."</td>
                    </tr>";
        }

        $html .= "</table>";
        break;


    case 'tour':
        $tours = $controlador->mis_tours($id_usuario);

        $html .= "<h2>Mis Tours</h2>";

        if (empty($tours)) {
            $html .= '<p class="no-data">No tienes tours registrados.</p>';
            break;
        }

        $html .= '<table>
                    <tr>
                        <th>Nombre</th>
                        <th>Duración</th>
                        <th>Precio</th>
                        <th>Hora</th>
                        <th>Punto de encuentro</th>
                        <th>Dirección</th>
                        <th>Estado</th>
                    </tr>';

        foreach ($tours as $tour) {
            
            $duracion = $tour['duracion_horas'];
            list($h, $m) = explode(':', substr($duracion, 0, 5));
            $duracionTexto = intval($h) . "h";
            if (intval($m) > 0) $duracionTexto .= " " . intval($m) . "m";

            $estado = ucfirst($tour['estado_revision'] ?? "Pendiente");

            $html .= "<tr>
                        <td>".htmlspecialchars($tour['nombre_tour'])."</td>
                        <td>{$duracionTexto}</td>
                        <td>$".number_format($tour['precio_por_persona'], 0, ',', '.')."</td>
                        <td>".htmlspecialchars(substr($tour['hora_encuentro'], 0, 5))." hs</td>
                        <td>".htmlspecialchars($tour['lugar_encuentro'])."</td>
                        <td>".htmlspecialchars($tour['direccion'])."</td>
                        <td>{$estado}</td>
                    </tr>";
        }

        $html .= "</table>";
        break;

        default:
            $html .= "<p class='no-data'>Tipo de listado no reconocido.</p>";
            break;
    }

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("listado_{$tipo}.pdf", ["Attachment" => true]);
