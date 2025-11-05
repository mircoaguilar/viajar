<?php
require_once '../../models/personas.php';

header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

switch ($action) {

    case 'obtener':
        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            exit;
        }

        $id_personas = (int) $_GET['id'];
        $modelo = new Persona();
        $resultado = $modelo->traer_persona_por_id($id_personas);

        if (!empty($resultado)) {
            echo json_encode(['success' => true, 'persona' => $resultado[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Persona no encontrada o inactiva']);
        }
        break;
    case 'actualizar':
        $id = $_POST['id_personas'] ?? '';
        $nombre = $_POST['personas_nombre'] ?? '';
        $apellido = $_POST['personas_apellido'] ?? '';
        $dni = $_POST['personas_dni'] ?? '';

        if ($id === '' || $nombre === '' || $apellido === '' || $dni === '') {
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Faltan datos para actualizar']);
            exit;
        }

        $persona = new Persona();
        $persona->setId_personas($id);
        $persona->setPersonas_nombre($nombre);
        $persona->setPersonas_apellido($apellido);
        $persona->setPersonas_dni($dni);

        $exito = $persona->actualizar();

        ob_clean();
        if ($exito) {
            echo json_encode(['success' => true, 'message' => 'Persona actualizada correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la persona']);
        }
        exit;

    default:
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
        exit;
}
