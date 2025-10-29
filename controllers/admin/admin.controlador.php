<?php
require_once(__DIR__ . '/../../models/admin.php');

if (isset($_GET['action'])) {
    $controlador = new AdminControlador();

    switch ($_GET['action']) {
        case 'dashboard':
            $controlador->mostrar_dashboard();
            break;

        default:
            header("Location: ../../index.php?page=dashboard_admin&message=Acción no válida&status=danger");
            exit;
    }
}

class AdminControlador {

    public function mostrar_dashboard() {
        session_start();
        if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [1, 2])) {
            header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
            exit;
        }

        require_once(__DIR__ . '/../../models/admin.php');
        $adminModel = new Admin();

        $data = [
            'usuarios_count' => $adminModel->contarUsuarios(),
            'reservas_count' => $adminModel->contarReservas(),
            'ingresos_total' => $adminModel->obtenerIngresosTotales(),
        ];

        require_once(__DIR__ . '/../../views/admin/dashboard_admin.php');
    }

}
