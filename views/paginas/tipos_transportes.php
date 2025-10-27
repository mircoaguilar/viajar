<?php
require_once('models/tipo_transporte.php');
require_once('models/conexion.php'); 

$tipo_transporte_model = new TipoTransporte(); 
$result_tipos_transportes = $tipo_transporte_model->traer_tipos_transportes(); 

$editing_mode = false;
$id_tipo_transporte_editar = '';
$descripcion_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_tipo_transporte_editar = htmlspecialchars($_GET['id']);
    $tipo_transporte_data_editar = $tipo_transporte_model->traer_tipo_transporte($id_tipo_transporte_editar); 
    
    if (!empty($tipo_transporte_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $descripcion_form = htmlspecialchars($tipo_transporte_data_editar[0]['descripcion']);
    } else {
        header("Location: index.php?page=tipos_transportes&message=Tipo de transporte a editar no encontrado o inactivo.&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Transporte</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="container">
        <form method="post" action="controllers/tipos_transportes/tipos_transportes.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_tipo_transporte" value="<?php echo htmlspecialchars($id_tipo_transporte_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Tipo de Transporte' : 'Crear Tipo de Transporte'; ?></h1>
            <label for="id_tipo_transporte_descripcion">Descripción del tipo de transporte</label>
            <input 
                type="text" 
                id="id_tipo_transporte_descripcion" 
                name="descripcion" 
                placeholder="Ingrese descripción del tipo de transporte" 
                value="<?php echo $descripcion_form; ?>"
            >

            <button type="submit"><?php echo $editing_mode ? 'Actualizar Tipo de Transporte' : 'Guardar Tipo de Transporte'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=tipos_transportes" class="button" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Listado de Tipos de Transporte</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="nombre-transporte">Descripción del tipo de transporte</th>
                    <th id="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($result_tipos_transportes)): ?>
                    <tr>
                        <td colspan="4">No hay tipos de transporte registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($result_tipos_transportes as $tipo_transporte_actual){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo_transporte_actual['id_tipo_transporte']); ?></td>
                        <td class="nombre-transporte"><?php echo htmlspecialchars($tipo_transporte_actual['descripcion']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=tipos_transportes&id=<?php echo htmlspecialchars($tipo_transporte_actual['id_tipo_transporte']); ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                    
        
                            <form method="post" action="controllers/tipos_transportes/tipos_transportes.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este tipo de transporte?');">
                                <input type="hidden" name="action" value="eliminar"> 
                                <input type="hidden" name="id_tipo_transporte_eliminar" value="<?php echo htmlspecialchars($tipo_transporte_actual['id_tipo_transporte']); ?>">
                                <button id="eliminar" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>
</html>
