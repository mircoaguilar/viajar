<?php

require_once('models/perfiles.php');
require_once('models/modulos.php');


$perfil_model = new Perfil(); 
$perfiles = $perfil_model->traer_perfiles(); 


$modulo_model = new Modulo(); 
$result_todos_los_modulos_disponibles = $modulo_model->traer_todos_los_modulos_disponibles();


$modulos_del_perfil_ids = []; 
$editing_mode = false;        
$id_perfil_editar = '';       
$perfiles_nombre_form = '';   
$form_action = 'guardar';     



if (isset($_GET['id'])) {
    $editing_mode = true;
    $id_perfil_editar = htmlspecialchars($_GET['id']);
    $form_action = 'actualizar'; 
    $perfil_data_editar = $perfil_model->traer_perfil($id_perfil_editar);
    if (!empty($perfil_data_editar)) {
        $perfiles_nombre_form = htmlspecialchars($perfil_data_editar[0]['perfiles_nombre']);
    } else {
        header("Location: index.php?page=modulos&message=Perfil no encontrado para editar.&status=danger");
        exit;
    }

    $result_modulos_editar = $modulo_model->traer_modulos_por_perfil($id_perfil_editar);
    foreach ($result_modulos_editar as $m_edit) {
        $modulos_del_perfil_ids[] = $m_edit['id_modulos'];
    }
}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Módulos por Perfil</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    </head>

<body>

    <?php if (isset($_GET['message'])): ?>
    <div class="alert <?php echo ($_GET['status'] == 'success') ? 'success' : 'danger'; ?>">
        <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
    <?php endif; ?>

    <div class="container">
        <form method="post" action="controllers/modulos/modulos.controlador.php">

            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id" value="<?php echo $id_perfil_editar; ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Módulos de Perfil' : 'Crear Nuevo Perfil'; ?></h1>

            <label for="id_perfil_nombre">Nombre de perfil</label>
            <input
                type="text"
                id="id_perfil_nombre"
                name="perfiles_nombre"
                placeholder="Ingrese nombre del perfil"
                value="<?php echo $perfiles_nombre_form; ?>"
            >

           <label for="id_modulos_select">Módulos</label>
            <select multiple name="id_modulos[]" id="id_modulos_select">
                <option value="" disabled>Seleccione módulos</option>
                <?php
                foreach($result_todos_los_modulos_disponibles as $modulo_disponible){
                    $selected = '';
                    if (in_array($modulo_disponible['id_modulos'], $modulos_del_perfil_ids)) {
                        $selected = 'selected';
                    }
                    echo '<option value="'.htmlspecialchars($modulo_disponible['id_modulos']).'" '.$selected.'>'.htmlspecialchars($modulo_disponible['modulos_nombre']).'</option>';
                }
                ?>
            </select>

            <button type="submit"><?php echo $editing_mode ? 'Actualizar' : 'Guardar'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=modulos" class="button cancel" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Perfiles y sus Módulos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Perfil</th>
                    <th>Módulos Asignados</th>
                    <th id="acciones" colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($perfiles as $perfil_actual){ ?>
                <tr>
                    <td><?=htmlspecialchars($perfil_actual['id_perfiles']); ?></td>
                    <td><?=htmlspecialchars($perfil_actual['perfiles_nombre']); ?></td>
                    <td><?php
                        $modulos_asignados = $modulo_model->traer_modulos_por_perfil($perfil_actual['id_perfiles']);
                        $nombres_modulos = [];
                        foreach($modulos_asignados as $m_asignado){
                            $nombres_modulos[] = htmlspecialchars($m_asignado['modulos_nombre']);
                        }
                        echo implode('<br>', $nombres_modulos);
                    ?></td>
                    <td><a id="editar" href="index.php?page=modulos&id=<?=htmlspecialchars($perfil_actual['id_perfiles']); ?>"><i class="fa-solid fa-pen-to-square" type="button"></i></a></td>
                    <td>
                        <form method="post" action="controllers/perfiles/perfiles.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este perfil?');">
                            <input type="hidden" name="action" value="eliminar"> <input type="hidden" name="id_perfiles_eliminar" value="<?php echo htmlspecialchars($perfil_actual['id_perfiles']); ?>">
                            <button id="eliminar" type="submit"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        </div>
</body>
</html>