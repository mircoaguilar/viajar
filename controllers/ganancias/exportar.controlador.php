<?php
session_start();
require_once __DIR__ . '/../../models/ganancia.php'; 
require_once __DIR__ . '/../../vendor/autoload.php';

$id_usuario = $_SESSION['id_usuarios'] ?? null;
if (!$id_usuario) {
    die("Acceso no autorizado.");
}

$ganancia_model = new Ganancia();
$ganancias = $ganancia_model->obtenerTodasLasGanancias(); 

$html = '<style>
            body { font-family: DejaVu Sans, sans-serif; }
            h1 { text-align:center; margin-bottom: 20px; }
            table { width:100%; border-collapse: collapse; margin-top:15px; }
            th, td { border:1px solid #000; padding:6px; font-size:14px; }
            th { background:#f0f0f0; }
         </style>';
$html .= "<h1>Reporte de Ganancias</h1>";

$html .= '<table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Reserva ID</th>
                <th>Tipo de Servicio</th>
                <th>Ganancia Neta</th>
                <th>Fecha de la Reserva</th>
            </tr>';

foreach ($ganancias as $ganancia) {
    $html .= "<tr>
                <td>" . htmlspecialchars($ganancia['id_reserva']) . "</td>
                <td>" . htmlspecialchars($ganancia['tipo_servicio']) . "</td>
                <td>$" . number_format($ganancia['ganancia_neta'], 2, ',', '.') . "</td>
                <td>" . htmlspecialchars($ganancia['fecha_calculo']) . "</td>
              </tr>";
}

$html .= "</table>";

use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_ganancias.pdf", ["Attachment" => true]);
