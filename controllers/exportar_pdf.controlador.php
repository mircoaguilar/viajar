<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/proveedores/proveedores.controlador.php';
require_once __DIR__ . '/../models/proveedor.php';
require_once __DIR__ . '/../models/hotel.php';
require_once __DIR__ . '/../models/transporte.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/hoteles/hoteles.controlador.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$html = '';

$tipo = $_GET['tipo'] ?? 'hotel';
$id_usuario = $_SESSION['id_usuarios'] ?? 0;

$controlador = new ProveedoresControlador();

switch ($tipo) {
    case 'hotel':
        $hoteles = $controlador->mis_hoteles($id_usuario);
        $html .= '<h2>Mis Hoteles</h2><table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<tr><th>Nombre</th><th>Provincia</th><th>Ciudad</th><th>Descripción</th></tr>';
        foreach ($hoteles as $h) {
            $html .= '<tr>';
            $html .= '<td>'.htmlspecialchars($h['hotel_nombre']).'</td>';
            $html .= '<td>'.htmlspecialchars($h['provincia_nombre']).'</td>';
            $html .= '<td>'.htmlspecialchars($h['ciudad_nombre']).'</td>';
            $html .= '<td>'.htmlspecialchars($h['descripcion']).'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        break;

    case 'transporte':
        $transportes = $controlador->mis_transportes($id_usuario);
        $html .= '<h2>Mis Transportes</h2><table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<tr><th>Nombre</th><th>Tipo</th><th>Capacidad</th><th>Descripción</th></tr>';
        foreach ($transportes as $t) {
            $html .= '<tr>';
            $html .= '<td>'.htmlspecialchars($t['nombre_servicio']).'</td>';
            $html .= '<td>'.htmlspecialchars($t['tipo_transporte']).'</td>';
            $html .= '<td>'.htmlspecialchars($t['transporte_capacidad']).'</td>';
            $html .= '<td>'.htmlspecialchars($t['descripcion']).'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        break;

    case 'tour':
        $tours = $controlador->mis_tours($id_usuario);
        $html .= '<h2>Mis Tours</h2><table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<tr><th>Nombre</th><th>Duración</th><th>Precio</th><th>Fecha Inicio</th></tr>';
        foreach ($tours as $tour) {
            $html .= '<tr>';
            $html .= '<td>'.htmlspecialchars($tour['nombre_tour']).'</td>';
            $html .= '<td>'.htmlspecialchars($tour['duracion_horas']).' hrs</td>';
            $html .= '<td>$'.number_format($tour['precio_por_persona'],0,',','.').'</td>';
            $html .= '<td>'.htmlspecialchars($tour['fecha_inicio']).'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        break;
}

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("listado_$tipo.pdf", ["Attachment" => true]);
