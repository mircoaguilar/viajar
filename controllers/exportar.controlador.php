<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$perfiles_permitidos = [3,5,13,14]; 
if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, $perfiles_permitidos)) {
    header('HTTP/1.1 403 Forbidden');
    echo "Acceso no autorizado.";
    exit;
}

$tipo = $_GET['tipo'] ?? '';
$scope = $_GET['scope'] ?? 'mine'; 

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/proveedor.php';
require_once __DIR__ . '/../models/Tour.php';
require_once __DIR__ . '/../models/transporte.php';
require_once __DIR__ . '/../models/hotel.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$usuario_id = $_SESSION['id_usuarios'];

$provModel = new Proveedor();
$prov = $provModel->obtenerPorUsuario($usuario_id);
$id_proveedor = $prov ? (int)$prov['id_proveedores'] : null;

$data = [];
$label = 'export';

function normalize_row($r) {
    $out = [];
    foreach ($r as $k => $v) {
        if (is_null($v)) $out[$k] = '';
        elseif (is_bool($v)) $out[$k] = $v ? '1' : '0';
        elseif (is_array($v)) $out[$k] = json_encode($v, JSON_UNESCAPED_UNICODE);
        else $out[$k] = $v;
    }
    return $out;
}

try {
    switch ($tipo) {
        case 'tours':
            $label = 'tours';
            $tourModel = new Tour();
            if ($scope === 'mine' && $id_proveedor) {
                if (method_exists($tourModel, 'traer_tours_por_usuario')) {
                    $data = $tourModel->traer_tours_por_usuario($usuario_id);
                } else {
                    $data = $tourModel->traer_tours(); 
                }
            } else {
                $data = $tourModel->traer_tours();
            }
            break;

        case 'transportes':
            $label = 'transportes';
            $transModel = new Transporte();
            if (method_exists($transModel, 'traer_transportes_por_usuario')) {
                $data = $transModel->traer_transportes_por_usuario($usuario_id);
            } else {
                if (method_exists($transModel, 'traer_transportes')) $data = $transModel->traer_transportes();
            }
            break;

        case 'hoteles':
            $label = 'hoteles';
            $hotelModel = new Hotel();
            if (method_exists($hotelModel, 'traer_hoteles_por_usuario')) {
                $data = $hotelModel->traer_hoteles_por_usuario($usuario_id);
            } else {
                if (method_exists($hotelModel, 'traer_hoteles')) $data = $hotelModel->traer_hoteles();
            }
            break;

        default:
            throw new Exception("Tipo no válido. Usa ?tipo=tours|transportes|hoteles");
    }
    $rows = [];
    foreach ($data as $r) {
        if (is_object($r)) $r = (array)$r;
        $rows[] = normalize_row($r);
    }

    if (!empty($rows) && array_key_exists('activo', $rows[0])) {
        $rows = array_filter($rows, function($rr){ return ($rr['activo'] === "1" || $rr['activo'] === 1 || $rr['activo'] === 'true'); });
        $rows = array_values($rows);
    }

    if (empty($rows)) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No hay registros para exportar.');
    } else {
        $first = $rows[0];
        $headers = array_keys($first);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $col = 'A';
        foreach ($headers as $h) {
            $labelHeader = ucwords(str_replace('_', ' ', $h));
            $sheet->setCellValue($col . '1', $labelHeader);
            $col++;
        }

        $rnum = 2;
        foreach ($rows as $row) {
            $col = 'A';
            foreach ($headers as $h) {
                $val = $row[$h];
                if (preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $val)) {
                    $sheet->setCellValue($col . $rnum, $val);
                    $sheet->getStyle($col . $rnum)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
                } else {
                    $sheet->setCellValue($col . $rnum, $val);
                }
                $col++;
            }
            $rnum++;
        }

        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        for ($i = 1; $i <= $highestColumnIndex; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    $filename = $label . '_' . date('Ymd_His') . '.xlsx';
    if (ob_get_length()) ob_end_clean();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (\Throwable $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status'=>'error','msg'=>$e->getMessage()]);
    exit;
}
