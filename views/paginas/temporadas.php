<?php
require_once('models/Temporada.php');

$temporada_model = new Temporada();
$result_temporadas = $temporada_model->traer_temporadas();

$editing_mode = false;
$id_temporada_editar = '';
$nombre_form = '';
$fecha_inicio_form = '';
$fecha_fin_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_temporada_editar = htmlspecialchars($_GET['id']);
    $temporada_data_editar = $temporada_model->traer_temporada($id_temporada_editar);

    if (!empty($temporada_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $nombre_form = htmlspecialchars($temporada_data_editar[0]['nombre']);
        $fecha_inicio_form = htmlspecialchars($temporada_data_editar[0]['fecha_inicio']);
        $fecha_fin_form = htmlspecialchars($temporada_data_editar[0]['fecha_fin']);
    } else {
        header("Location: index.php?page=temporadas&message=Temporada no encontrada&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Temporadas</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">

    <!-- Incluyendo flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> 
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
</head>
<body>


<div class="container">
    <form method="post" action="controllers/temporadas/temporadas.controlador.php">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>">
        <?php if ($editing_mode): ?>
            <input type="hidden" name="id_temporada" value="<?php echo htmlspecialchars($id_temporada_editar); ?>">
        <?php endif; ?>

        <h1><?php echo $editing_mode ? 'Editar Temporada' : 'Crear Temporada'; ?></h1>

        <label>Nombre</label>
        <input type="text" name="nombre" value="<?php echo $nombre_form; ?>" placeholder="Nombre de temporada" >

        <label>Fecha Inicio</label>
        <input type="text" id="fecha_inicio" name="fecha_inicio" value="<?php echo $fecha_inicio_form; ?>" placeholder="Seleccionar fecha de inicio" >

        <label>Fecha Fin</label>
        <input type="text" id="fecha_fin" name="fecha_fin" value="<?php echo $fecha_fin_form; ?>" placeholder="Seleccionar fecha de fin" >

        <button type="submit"><?php echo $editing_mode ? 'Actualizar' : 'Guardar'; ?></button>
        <?php if ($editing_mode): ?>
            <a href="index.php?page=temporadas" class="button" style="margin-left: 10px;">Cancelar</a>
        <?php endif; ?>
    </form>
</div>

<div class="container">
    <h2>Listado de Temporadas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($result_temporadas)): ?>
                <tr><td colspan="6">No hay temporadas registradas.</td></tr>
            <?php else: ?>
                <?php foreach($result_temporadas as $temp): ?>
                    <tr>
                        <td><?php echo $temp['id_temporada']; ?></td>
                        <td><?php echo htmlspecialchars($temp['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($temp['fecha_inicio']); ?></td>
                        <td><?php echo htmlspecialchars($temp['fecha_fin']); ?></td>
                        <td>
                            <a id="editar" href="index.php?page=temporadas&id=<?php echo $temp['id_temporada']; ?>">
                                  <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                        </td>
                        <td>
                            <form method="post" action="controllers/temporadas/temporadas.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar esta temporada?');">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id_temporada_eliminar" value="<?php echo $temp['id_temporada']; ?>">
                                <button id="eliminar" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    flatpickr("#fecha_inicio", {
        dateFormat: "Y-m-d", 
        locale: "es"
    });

    flatpickr("#fecha_fin", {
        dateFormat: "Y-m-d", 
        locale: "es"
    });
</script>

</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>
</html>
