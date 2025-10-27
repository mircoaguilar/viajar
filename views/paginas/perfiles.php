<?php

require_once('models/perfiles.php');
require_once('models/conexion.php'); 

$perfil_model = new Perfil(); 

$result_perfiles = $perfil_model->traer_perfiles(); 

$editing_mode = false;
$id_perfil_editar = '';
$perfiles_nombre_form = '';
$form_action = 'guardar';


if (isset($_GET['id'])) {
    $id_perfil_editar = htmlspecialchars($_GET['id']);

    $perfil_data_editar = $perfil_model->traer_perfil($id_perfil_editar); 
    
    if (!empty($perfil_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $perfiles_nombre_form = htmlspecialchars($perfil_data_editar[0]['perfiles_nombre']);
    } else {
        header("Location: index.php?page=perfiles&message=Perfil a editar no encontrado o inactivo.&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es"> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Perfiles</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    

    <div class="container">
        <form method="post" action="controllers/perfiles/perfiles.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_perfiles" value="<?php echo htmlspecialchars($id_perfil_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Perfil' : 'Crear Perfil'; ?></h1>
            <label for="id_perfil_nombre">Nombre de perfil</label>
            <input 
                type="text" 
                id="id_perfil_nombre" 
                name="perfiles_nombre" 
                placeholder="Ingrese nombre del perfil" 
                value="<?php echo $perfiles_nombre_form; ?>"
            >

            <button type="submit"><?php echo $editing_mode ? 'Actualizar Perfil' : 'Guardar Perfil'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=perfiles" class="button" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Listado de Perfiles</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="nombre-perfil">Nombre del perfil</th>
                    <th id="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($result_perfiles)): ?>
                    <tr>
                        <td colspan="4">No hay perfiles activos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($result_perfiles as $perfil_actual){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($perfil_actual['id_perfiles']); ?></td>
                        <td class="nombre-perfil"><?php echo htmlspecialchars($perfil_actual['perfiles_nombre']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=perfiles&id=<?php echo htmlspecialchars($perfil_actual['id_perfiles']); ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                       
                            <form method="post" action="controllers/perfiles/perfiles.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este perfil?');">
                                <input type="hidden" name="action" value="eliminar"> 
                                <input type="hidden" name="id_perfiles_eliminar" value="<?php echo htmlspecialchars($perfil_actual['id_perfiles']); ?>">
                                <button id="eliminar" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php }?>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>

</html>