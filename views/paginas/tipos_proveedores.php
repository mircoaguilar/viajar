<?php
require_once('models/tipo_proveedor.php');

$tipo_model = new Tipo_proveedor();
$result_tipos = $tipo_model->traer_tipos_proveedores();

$editing_mode = false;
$id_editar = '';
$nombre_form = '';
$descripcion_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_editar = htmlspecialchars($_GET['id']);
    $tipo_data = $tipo_model->traer_tipo_proveedor($id_editar);
    if (!empty($tipo_data)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $nombre_form = htmlspecialchars($tipo_data[0]['nombre']);
        $descripcion_form = htmlspecialchars($tipo_data[0]['descripcion']);
    } else {
        header("Location: index.php?page=tipos_proveedores&message=Tipo de proveedor no encontrado&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Proveedor</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
<div class="container">
    <form method="post" action="controllers/tipos_proveedores/tipos_proveedores.controlador.php">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
        <?php if ($editing_mode): ?>
            <input type="hidden" name="id_tipo_proveedor" value="<?php echo htmlspecialchars($id_editar); ?>" />
        <?php endif; ?>

        <h1><?php echo $editing_mode ? 'Editar Tipo de Proveedor' : 'Crear Tipo de Proveedor'; ?></h1>

        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingrese nombre" value="<?php echo $nombre_form; ?>" >

        <label for="descripcion">Descripcion</label>
        <input type="text" id="descripcion" name="descripcion" placeholder="Ingrese descripcion" value="<?php echo $descripcion_form; ?>" >

        <button type="submit"><?php echo $editing_mode ? 'Actualizar' : 'Guardar'; ?></button>
        <?php if ($editing_mode): ?>
            <a href="index.php?page=tipos_proveedores" class="button" style="margin-left: 10px;">Cancelar</a>
        <?php endif; ?>
    </form>
</div>

<div class="container">
    <h2>Listado de Tipos de Proveedor</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($result_tipos)): ?>
                <tr><td colspan="4">No hay tipos de proveedor registrados.</td></tr>
            <?php else: ?>
                <?php foreach($result_tipos as $tipo): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo['id_tipo_proveedor']); ?></td>
                        <td><?php echo htmlspecialchars($tipo['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($tipo['descripcion']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=tipos_proveedores&id=<?php echo htmlspecialchars($tipo['id_tipo_proveedor']); ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        
                            <form method="post" action="controllers/tipos_proveedores/tipos_proveedores.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este tipo de proveedor?');">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id_tipo_proveedor_eliminar" value="<?php echo htmlspecialchars($tipo['id_tipo_proveedor']); ?>">
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
