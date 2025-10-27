<?php
require_once('models/Moneda.php');

$moneda_model = new Moneda();
$result_monedas = $moneda_model->traer_monedas();

$editing_mode = false;
$id_moneda_editar = '';
$nombre_form = '';
$simbolo_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_moneda_editar = htmlspecialchars($_GET['id']);
    $moneda_data_editar = $moneda_model->traer_moneda($id_moneda_editar);

    if (!empty($moneda_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $nombre_form = htmlspecialchars($moneda_data_editar[0]['nombre']);
        $simbolo_form = htmlspecialchars($moneda_data_editar[0]['simbolo']);
    } else {
        header("Location: index.php?page=monedas&message=Moneda no encontrada&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Monedas</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
</head>
<body>

<div class="container">
    <form method="post" action="controllers/monedas/monedas.controlador.php">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>">
        <?php if ($editing_mode): ?>
            <input type="hidden" name="id_moneda" value="<?php echo htmlspecialchars($id_moneda_editar); ?>">
        <?php endif; ?>

        <h1><?php echo $editing_mode ? 'Editar Moneda' : 'Crear Moneda'; ?></h1>

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?php echo $nombre_form; ?>" placeholder="Nombre de la moneda" >

        <label>Símbolo</label>
        <input type="text" name="simbolo" value="<?php echo $simbolo_form; ?>" placeholder="Símbolo (ej: $)" >

        <button type="submit"><?php echo $editing_mode ? 'Actualizar' : 'Guardar'; ?></button>
        <?php if ($editing_mode): ?>
            <a href="index.php?page=monedas" class="button" style="margin-left: 10px;">Cancelar</a>
        <?php endif; ?>
    </form>
</div>

<div class="container">
    <h2>Listado de Monedas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Símbolo</th>
                <th class="nombre-acciones">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($result_monedas)): ?>
                <tr><td colspan="5">No hay monedas registradas.</td></tr>
            <?php else: ?>
                <?php foreach($result_monedas as $mon): ?>
                    <tr>
                        <td><?php echo $mon['id_moneda']; ?></td>
                        <td><?php echo htmlspecialchars($mon['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($mon['simbolo']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=monedas&id=<?php echo $mon['id_moneda']; ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>

                            <form method="post" action="controllers/monedas/monedas.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar esta moneda?');">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id_moneda_eliminar" value="<?php echo $mon['id_moneda']; ?>">
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
</div

