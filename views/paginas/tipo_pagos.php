<?php

require_once('models/tipo_pagos.php');
require_once('models/conexion.php');

$tipo_pago_model = new Tipo_pago(); 

$result_tipos_pagos = $tipo_pago_model->traer_tipos_pagos(); 

$editing_mode = false;
$id_tipo_pago_editar = '';
$tipo_pago_descripcion_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_tipo_pago_editar = htmlspecialchars($_GET['id']);
    $tipo_pago_data_editar = $tipo_pago_model->traer_tipo_pago($id_tipo_pago_editar);
    
    if (!empty($tipo_pago_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $tipo_pago_descripcion_form = htmlspecialchars($tipo_pago_data_editar[0]['tipo_pago_descripcion']);
    } else {
        header("Location: index.php?page=tipo_pagos&message=Tipo de pago a editar no encontrado o inactivo.&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es"> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Pago</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="container">
        <form method="post" action="controllers/tipo_pagos/tipo_pagos.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_tipo_pago" value="<?php echo htmlspecialchars($id_tipo_pago_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Tipo de Pago' : 'Crear Tipo de Pago'; ?></h1>
            <label for="id_tipo_pago_descripcion">Nombre del tipo de pago</label>
            <input 
                type="text" 
                id="id_tipo_pago_descripcion" 
                name="tipo_pago_descripcion" 
                placeholder="Ingrese nombre del tipo de pago" 
                value="<?php echo $tipo_pago_descripcion_form; ?>"
            >

            <button type="submit"><?php echo $editing_mode ? 'Actualizar Tipo de Pago' : 'Guardar Tipo de Pago'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=tipo_pagos" class="button" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Listado de Tipos de Pago</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="nombre-tipo-pago">Nombre del tipo de pago</th>
                    <th id="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($result_tipos_pagos)): ?>
                    <tr>
                        <td colspan="4">No hay tipos de pago registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($result_tipos_pagos as $tipo_pago_actual){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo_pago_actual['id_tipo_pago']); ?></td>
                        <td class="nombre-tipo-pago"><?php echo htmlspecialchars($tipo_pago_actual['tipo_pago_descripcion']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=tipo_pagos&id=<?php echo htmlspecialchars($tipo_pago_actual['id_tipo_pago']); ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                       
                            <form method="post" action="controllers/tipo_pagos/tipo_pagos.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este tipo de pago?');">
                                <input type="hidden" name="action" value="eliminar"> 
                                <input type="hidden" name="id_tipo_pago_eliminar" value="<?php echo htmlspecialchars($tipo_pago_actual['id_tipo_pago']); ?>">
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