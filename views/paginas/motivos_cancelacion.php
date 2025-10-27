<?php
require_once('models/motivo_cancelacion.php');

$motivo_model = new MotivoCancelacion();
$result_motivos = $motivo_model->traer_motivos();

$editing_mode = false;
$id_motivo_cancelacion_editar = '';
$descripcion_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_motivo_cancelacion_editar = htmlspecialchars($_GET['id']);
    $motivo_data_editar = $motivo_model->traer_motivo($id_motivo_cancelacion_editar);

    if (!empty($motivo_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $descripcion_form = htmlspecialchars($motivo_data_editar[0]['descripcion']);
    } else {
        header("Location: index.php?page=motivos_cancelacion&message=Motivo no encontrado&status=danger");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de motivos de cancelación</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
</head>
<body>

<div class="container">
    <form method="post" action="controllers/motivos_cancelacion/motivos_cancelacion.controlador.php">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>">
        <?php if ($editing_mode): ?>
            <input type="hidden" name="id_motivo_cancelacion" value="<?php echo htmlspecialchars($id_motivo_cancelacion_editar); ?>">
        <?php endif; ?>

        <h1><?php echo $editing_mode ? 'Editar Motivo de Cancelación' : 'Crear Motivo de Cancelación'; ?></h1>

        <label>Descripción</label>
        <input type="text" name="descripcion" value="<?php echo $descripcion_form; ?>" placeholder="Descripción del motivo" >

        <button type="submit"><?php echo $editing_mode ? 'Actualizar' : 'Guardar'; ?></button>
        <?php if ($editing_mode): ?>
            <a href="index.php?page=monedas" class="button" style="margin-left: 10px;">Cancelar</a>
        <?php endif; ?>
    </form>
</div>

<div class="container">
    <h2>Listado de Motivos de Cancelación</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th class="nombre-motivo-cancelacion">Descripción</th>
                <th class="nombre-acciones">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($result_motivos)): ?>
                <tr><td colspan="5">No hay motivos de cancelación registrados.</td></tr>
            <?php else: ?>
            <?php foreach ($result_motivos as $motivo): ?>
                <tr>
                    <td><?php echo htmlspecialchars($motivo['id_motivo_cancelacion']); ?></td>
                    <td class="nombre-motivo-cancelacion"><?php echo htmlspecialchars($motivo['descripcion']); ?></td>
                    <td class="acciones-botones">
                        <a id="editar" href="index.php?page=motivos_cancelacion&id=<?php echo $motivo['id_motivo_cancelacion']; ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>

                            <form method="post" action="controllers/motivos_cancelacion/motivos_cancelacion.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este motivo?');">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id_motivo_cancelacion" value="<?php echo $motivo['id_motivo_cancelacion']; ?>">
                                 <button id="eliminar" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>
</div>