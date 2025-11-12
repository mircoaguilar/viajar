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

        $auditoriaModel = new Auditoria();
        $usuarioModel = new Usuario();

        $auditorias = $auditoriaModel->traer_todas();
        $usuarios   = $usuarioModel->traer_todos();
        $acciones   = $auditoriaModel->traer_acciones_distintas();

        require_once(__DIR__ . '/../../views/paginas/listado_auditorias.php');
    }

    public function filtrar() {
        $this->verificar_acceso();

        $usuario = $_POST['usuario'] ?? '';
        $accion = $_POST['accion'] ?? '';
        $fecha_desde = $_POST['fecha_desde'] ?? '';
        $fecha_hasta = $_POST['fecha_hasta'] ?? '';

        $auditoriaModel = new Auditoria();
        $usuarioModel = new Usuario();

        $auditorias = $auditoriaModel->filtrar($usuario, $accion, $fecha_desde, $fecha_hasta);

        $usuarios = $usuarioModel->traer_usuarios();
        $acciones = $auditoriaModel->traer_acciones_distintas();

        require_once(__DIR__ . '/../../views/paginas/listado_auditorias.php');
    }
}
