<?php

require_once('models/tipo_contactos.php');
require_once('models/conexion.php'); 

$tipo_contacto_model = new Tipo_contacto(); 


$result_tipos_contactos = $tipo_contacto_model->traer_tipos_contactos(); 

$editing_mode = false;
$id_tipo_contacto_editar = '';
$tipo_contacto_descripcion_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_tipo_contacto_editar = htmlspecialchars($_GET['id']);
    $tipo_contacto_data_editar = $tipo_contacto_model->traer_tipo_contacto($id_tipo_contacto_editar); 
    
    if (!empty($tipo_contacto_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $tipo_contacto_descripcion_form = htmlspecialchars($tipo_contacto_data_editar[0]['tipo_contacto_descripcion']);
    } else {
        header("Location: index.php?page=tipo_contactos&message=Tipo de contacto a editar no encontrado o inactivo.&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es"> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Contacto</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    

    <div class="container">
        <form method="post" action="controllers/tipo_contactos/tipo_contactos.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_tipo_contacto" value="<?php echo htmlspecialchars($id_tipo_contacto_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Tipo de Contacto' : 'Crear Tipo de Contacto'; ?></h1>
            <label for="id_tipo_contacto_descripcion">Nombre del tipo de contacto</label>
            <input 
                type="text" 
                id="id_tipo_contacto_descripcion" 
                name="tipo_contacto_descripcion" 
                placeholder="Ingrese nombre del tipo de contacto" 
                value="<?php echo $tipo_contacto_descripcion_form; ?>"
            >

            <button type="submit"><?php echo $editing_mode ? 'Actualizar Tipo de Contacto' : 'Guardar Tipo de Contacto'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=tipo_contactos" class="button" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Listado de Tipos de Contacto</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="nombre-tipo-contacto">Nombre del tipo de contacto</th>
                    <th id="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($result_tipos_contactos)): ?>
                    <tr>
                        <td colspan="4">No hay tipos de contacto registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($result_tipos_contactos as $tipo_contacto_actual){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo_contacto_actual['id_tipo_contacto']); ?></td>
                        <td class="nombre-tipo-contacto"><?php echo htmlspecialchars($tipo_contacto_actual['tipo_contacto_descripcion']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=tipo_contactos&id=<?php echo htmlspecialchars($tipo_contacto_actual['id_tipo_contacto']); ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                       
                            <form method="post" action="controllers/tipo_contactos/tipo_contactos.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este tipo de contacto?');">
                                <input type="hidden" name="action" value="eliminar"> 
                                <input type="hidden" name="id_tipo_contacto_eliminar" value="<?php echo htmlspecialchars($tipo_contacto_actual['id_tipo_contacto']); ?>">
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