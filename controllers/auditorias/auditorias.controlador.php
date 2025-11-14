<?php
require_once(__DIR__ . '/../../models/auditoria.php');
require_once(__DIR__ . '/../../models/usuarios.php');

if (isset($_GET['action'])) {
    $controlador = new AuditoriasControlador();

    switch ($_GET['action']) {
        case 'listar_auditorias':
            $controlador->listar_auditorias();
            break;

        case 'filtrar':
            $controlador->filtrar();
            break;

        default:
            header("Location: ../../index.php?page=listado_auditorias&message=Acción no válida&status=danger");
            exit;
    }
}

class AuditoriasControlador {

    private function verificar_acceso() {
        session_start();
        if (!isset($_SESSION['id_perfiles']) || !in_array($_SESSION['id_perfiles'], [2])) {
            header("Location: ../../index.php?page=login&message=Acceso no autorizado&status=danger");
            exit;
        }
    }

    public function listar_auditorias() {
        $this->verificar_acceso();

        $page_size = 10;
        $current_page = isset($_GET['current_page']) ? max(0, (int)$_GET['current_page']) : 0;

        $usuario = $_GET['usuario'] ?? '';
        $accion = $_GET['accion'] ?? '';
        $fecha_desde = $_GET['fecha_desde'] ?? '';
        $fecha_hasta = $_GET['fecha_hasta'] ?? '';

        $auditoriaModel = new Auditoria();
        $auditoriaModel->page_size = $page_size;
        $auditoriaModel->current_page = $current_page;

        $total_rows = $auditoriaModel->contar($usuario, $accion, $fecha_desde, $fecha_hasta);
        $total_pages = max(1, ceil($total_rows / $page_size));

        $auditorias = $auditoriaModel->filtrar($usuario, $accion, $fecha_desde, $fecha_hasta);

        if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
            require(__DIR__ . '/../../views/paginas/tabla_auditorias.php');
            exit;
        }

        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->traer_usuarios();

        require_once(__DIR__ . '/../../views/paginas/listado_auditorias.php');
    }



    public function filtrar() {
        $this->verificar_acceso();

        $usuario = $_POST['usuario'] ?? '';
        $accion = $_POST['accion'] ?? '';
        $fecha_desde = $_POST['fecha_desde'] ?? '';
        $fecha_hasta = $_POST['fecha_hasta'] ?? '';

        $auditoriaModel = new Auditoria();
        $auditorias = $auditoriaModel->filtrar($usuario, $accion, $fecha_desde, $fecha_hasta);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "status" => "success",
            "auditorias" => $auditorias
        ]);
        exit;
    }
    

}
