<?php
require_once(__DIR__ . '/../../models/modulos.php');
require_once(__DIR__ . '/../../models/perfiles.php'); 

// Enrutamiento de acciones
if (isset($_POST["action"])) {
    $modulo_controlador = new ModuloControlador();
    if ($_POST["action"] == "actualizar") {
        $modulo_controlador->actualizar();
    }
    if ($_POST["action"] == "guardar") {
        $modulo_controlador->guardar_perfil_y_modulos();
    }
}

class ModuloControlador {

    // Actualiza un perfil y sus módulos asignados
    public function actualizar() {
        $modulo = new Modulo();

        // Validar que se reciba un ID de perfil
        if (empty($_POST['id'])) {
            $id_perfil_redir = htmlspecialchars($_POST['id'] ?? '');
            header('Location: ../../index.php?page=modulos&id='.$id_perfil_redir.'&message=Error: ID de perfil no especificado al actualizar módulos.&status=danger');
            exit;
        }

        // Guardar los módulos seleccionados
        $modulos_seleccionados = [];
        if (isset($_POST['id_modulos']) && is_array($_POST['id_modulos'])) {
            $modulos_seleccionados = $_POST['id_modulos'];
        }

        // Si se envió nombre de perfil, actualizarlo
        if (isset($_POST['perfiles_nombre'])) {
            $perfil_obj = new Perfil();
            $perfil_obj->setId_perfiles($_POST['id']);
            $perfil_obj->setPerfiles_nombre($_POST['perfiles_nombre']);
            $perfil_actualizado = $perfil_obj->actualizar();
        } else {
            $perfil_actualizado = true; 
        }

        // Actualizar relación de módulos
        $modulo->setId_perfil($_POST['id']);
        $modulo->setModulos_ids_seleccionados($modulos_seleccionados); 
        $modulos_actualizados = $modulo->actualizar(); 

        // Redirección según resultado
        if ($perfil_actualizado && $modulos_actualizados !== false) {
            header('Location: ../../index.php?page=modulos&message=Cambios actualizados correctamente&status=success');
        } else {
            header('Location: ../../index.php?page=modulos&id='.htmlspecialchars($_POST['id']).'&message=Error al actualizar perfil o módulos. Verifique los datos.&status=danger');
        }
        exit;
    }

    // Guarda un perfil nuevo y le asigna módulos
    public function guardar_perfil_y_modulos() {
        // Validar que se envíe nombre de perfil
        if (empty($_POST['perfiles_nombre'])) {
            header('Location: ../../index.php?page=modulos&message=Los campos son obligatorios.&status=danger');
            return;
        }

        // Crear y guardar nuevo perfil
        $perfil = new Perfil();
        $perfil->setPerfiles_nombre($_POST['perfiles_nombre']);
        $nuevo_perfil_id = $perfil->guardar();

        // Si se creó el perfil, asignar módulos seleccionados
        if ($nuevo_perfil_id) {
            if (isset($_POST['id_modulos']) && is_array($_POST['id_modulos'])) {
                $modulo = new Modulo();
                $modulo->setId_perfil($nuevo_perfil_id);
                $modulo->setModulos_ids_seleccionados($_POST['id_modulos']);
                $modulo->actualizar();
            }
            header('Location: ../../index.php?page=modulos&message=Cambios guardados correctamente.&status=success');
        } else {
            header('Location: ../../index.php?page=modulos&message=Error: No se pudo guardar el perfil.&status=danger');
        }
        exit;
    }
}
