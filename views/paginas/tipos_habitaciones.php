<?php
require_once('models/tipo_habitacion.php');

$tipo_model = new TipoHabitacion();
$result_tipos = $tipo_model->traer_tipos_habitaciones();

$editing_mode = false;
$id_editar = '';
$nombre_form = '';
$descripcion_form = '';
$capacidad_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_editar = htmlspecialchars($_GET['id']);
    $tipo_data = $tipo_model->traer_tipo_habitacion($id_editar);
    if (!empty($tipo_data)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $nombre_form = htmlspecialchars($tipo_data[0]['nombre']);
    } else {
        header("Location: index.php?page=tipos_habitaciones&message=Tipo de habitación no encontrado&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Habitación</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <form method="post" action="controllers/tipos_habitaciones/tipos_habitaciones.controlador.php">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
        <?php if ($editing_mode): ?>
            <input type="hidden" name="id_tipo_habitacion" value="<?php echo htmlspecialchars($id_editar); ?>" />
        <?php endif; ?>

        <h1><?php echo $editing_mode ? 'Editar Tipo de Habitación' : 'Crear Tipo de Habitación'; ?></h1>

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingrese nombre" value="<?php echo $nombre_form; ?>" >

        <button type="submit"><?php echo $editing_mode ? 'Actualizar' : 'Guardar'; ?></button>
        <?php if ($editing_mode): ?>
            <a href="index.php?page=tipos_habitaciones" class="button" style="margin-left: 10px;">Cancelar</a>
        <?php endif; ?>
    </form>
</div>

<div class="container">
    <h2>Listado de Tipos de Habitación</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th >Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($result_tipos)): ?>
                <tr><td colspan="6">No hay tipos de habitación registrados.</td></tr>
            <?php else: ?>
                <?php foreach($result_tipos as $tipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo['id_tipo_habitacion']); ?></td>
                        <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=tipos_habitaciones&id=<?php echo htmlspecialchars($tipo['id_tipo_habitacion']); ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                       
                            <form method="post" action="controllers/tipos_habitaciones/tipos_habitaciones.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este tipo de habitación?');">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id_tipo_habitacion_eliminar" value="<?php echo htmlspecialchars($tipo['id_tipo_habitacion']); ?>">
                                <button id="eliminar" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>
</html>
