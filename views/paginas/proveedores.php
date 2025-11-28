<?php
require_once('models/proveedor.php');
require_once('models/tipo_proveedor.php');

$prov_model = new Proveedor();
$result_proveedores = $prov_model->traer_proveedores();

$tipo_model = new Tipo_proveedor();
$result_tipos = $tipo_model->traer_tipos_proveedores();

$editing_mode = false;
$id_editar = '';
$razon_form = '';
$cuit_form = '';
$domicilio_form = '';
$email_form = '';
$rela_tipo_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_editar = htmlspecialchars($_GET['id']);
    $prov_data = $prov_model->traer_proveedor($id_editar);
    if (!empty($prov_data)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $razon_form = htmlspecialchars($prov_data[0]['razon_social']);
        $cuit_form = htmlspecialchars($prov_data[0]['cuit']);
        $domicilio_form = htmlspecialchars($prov_data[0]['proveedor_direccion']);
        $email_form = htmlspecialchars($prov_data[0]['proveedor_email']);
        $rela_tipo_form = htmlspecialchars($prov_data[0]['rela_tipo_proveedor']);
    } else {
        header("Location: index.php?page=proveedores&message=Proveedor no encontrado&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Proveedores</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <form method="post" action="controllers/proveedores/proveedores.controlador.php">
        <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
        <?php if ($editing_mode): ?>
            <input type="hidden" name="id_proveedores" value="<?php echo htmlspecialchars($id_editar); ?>" />
        <?php endif; ?>

        <h1><?php echo $editing_mode ? 'Editar Proveedor' : 'Crear Proveedor'; ?></h1>

        <label for="razon_social">Razón Social</label>
        <input type="text" name="razon_social" value="<?php echo $razon_form; ?>" >

        <label for="cuit">CUIT</label>
        <input type="text" name="cuit" value="<?php echo $cuit_form; ?>" >

        <label for="proveedor_domicilio">Domicilio</label>
        <input type="text" name="proveedor_domicilio" value="<?php echo $domicilio_form; ?>">

        <label for="proveedor_email">Email</label>
        <input type="email" name="proveedor_email" value="<?php echo $email_form; ?>">

        <label for="rela_tipo_proveedor">Tipo de Proveedor</label>
        <select name="rela_tipo_proveedor" >
            <option value="">Seleccione tipo</option>
            <?php foreach ($result_tipos as $tipo): ?>
                <option value="<?php echo $tipo['id_tipo_proveedor']; ?>" <?php echo ($rela_tipo_form==$tipo['id_tipo_proveedor'])?'selected':''; ?>>
                    <?php echo htmlspecialchars($tipo['nombre']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit"><?php echo $editing_mode ? 'Actualizar' : 'Guardar'; ?></button>
        <?php if ($editing_mode): ?>
            <a href="index.php?page=proveedores" class="button" style="margin-left:10px;">Cancelar</a>
        <?php endif; ?>
    </form>
</div>

<div class="container">
    <h2>Listado de Proveedores</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Razón Social</th>
                <th>CUIT</th>
                <th>Email</th>
                <th>Tipo de Proveedor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($result_proveedores)): ?>
                <tr><td colspan="7">No hay proveedores registrados.</td></tr>
            <?php else: ?>
                <?php foreach($result_proveedores as $prov): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prov['id_proveedores']); ?></td>
                        <td><?php echo htmlspecialchars($prov['razon_social']); ?></td>
                        <td><?php echo htmlspecialchars($prov['cuit']); ?></td>
                        <td><?php echo htmlspecialchars($prov['proveedor_email']); ?></td>
                        <td><?php echo htmlspecialchars($prov['nombre']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=proveedores&id=<?php echo htmlspecialchars($prov['id_proveedores']); ?>">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                        
                            <form method="post" action="controllers/proveedores/proveedores.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este proveedor?');">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id_proveedor_eliminar" value="<?php echo htmlspecialchars($prov['id_proveedores']); ?>">
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
