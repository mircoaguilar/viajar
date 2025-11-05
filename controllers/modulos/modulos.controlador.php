<?php
require_once(__DIR__ . '/../../models/modulos.php');
require_once(__DIR__ . '/../../models/perfiles.php'); 

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

    public function actualizar() {
        $modulo = new Modulo();

        if (empty($_POST['id'])) {
            $id_perfil_redir = htmlspecialchars($_POST['id'] ?? '');
            header('Location: ../../index.php?page=modulos&id='.$id_perfil_redir.'&message=Error: ID de perfil no especificado al actualizar módulos.&status=danger');
            exit;
        }

        $modulos_seleccionados = [];
        if (isset($_POST['id_modulos']) && is_array($_POST['id_modulos'])) {
            $modulos_seleccionados = $_POST['id_modulos'];
        }

        if (isset($_POST['perfiles_nombre'])) {
            $perfil_obj = new Perfil();
            $perfil_obj->setId_perfiles($_POST['id']);
            $perfil_obj->setPerfiles_nombre($_POST['perfiles_nombre']);
            $perfil_actualizado = $perfil_obj->actualizar();
        } else {
            $perfil_actualizado = true; 
        }

        $modulo->setId_perfil($_POST['id']);
        $modulo->setModulos_ids_seleccionados($modulos_seleccionados); 
        $modulos_actualizados = $modulo->actualizar(); 

        if ($perfil_actualizado && $modulos_actualizados !== false) {
            header('Location: ../../index.php?page=modulos&message=Cambios actualizados correctamente&status=success');
        } else {
            header('Location: ../../index.php?page=modulos&id='.htmlspecialchars($_POST['id']).'&message=Error al actualizar perfil o módulos. Verifique los datos.&status=danger');
        }
        exit;
    }

    public function guardar_perfil_y_modulos() {
        if (empty($_POST['perfiles_nombre'])) {
            header('Location: ../../index.php?page=modulos&message=Los campos son obligatorios.&status=danger');
            return;
        }

        $perfil = new Perfil();
        $perfil->setPerfiles_nombre($_POST['perfiles_nombre']);
        $nuevo_perfil_id = $perfil->guardar();

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
