<?php
require_once('models/ciudad.php');
require_once('models/provincia.php'); // para traer lista de provincias

$ciudad_model = new Ciudad();
$result_ciudades = $ciudad_model->traer_ciudades();

$prov_model = new Provincia(); 
$result_provincias = $prov_model->traer_provincias(); // suponiendo que tenés un método así

$editing_mode = false;
$id_editar = '';
$nombre_form = '';
$provincia_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_editar = htmlspecialchars($_GET['id']);
    $ciudad_data = $ciudad_model->traer_ciudad($id_editar);
    if (!empty($ciudad_data)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $nombre_form = htmlspecialchars($ciudad_data[0]['nombre']);
        $provincia_form = htmlspecialchars($ciudad_data[0]['rela_provincia']);
    } else {
        header("Location: index.php?page=ciudades&message=Ciudad no encontrada&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Ciudades</title>
<link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
<form method="post" action="controllers/ciudades/ciudades.controlador.php">
<input type="hidden" name="action" value="<?php echo $form_action; ?>" />
<?php if ($editing_mode): ?>
<input type="hidden" name="id_ciudad" value="<?php echo htmlspecialchars($id_editar); ?>" />
<?php endif; ?>

<h1><?php echo $editing_mode?'Editar Ciudad':'Crear Ciudad'; ?></h1>

<label>Nombre de la Ciudad</label>
<input type="text" name="nombre" value="<?php echo $nombre_form; ?>" >

<label>Provincia</label>
<select name="rela_provincia" >
<option value="">Seleccione provincia</option>
<?php foreach($result_provincias as $prov): ?>
<option value="<?php echo $prov['id_provincia']; ?>" <?php echo ($provincia_form==$prov['id_provincia'])?'selected':''; ?>>
<?php echo htmlspecialchars($prov['nombre']); ?>
</option>
<?php endforeach; ?>
</select>

<button type="submit"><?php echo $editing_mode?'Actualizar':'Guardar'; ?></button>
<?php if($editing_mode): ?>
<a href="index.php?page=ciudades" class="button" style="margin-left:10px;">Cancelar</a>
<?php endif; ?>
</form>
</div>

<div class="container">
<h2>Listado de Ciudades</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Nombre</th>
<th>Provincia</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
<?php if(empty($result_ciudades)): ?>
<tr><td colspan="5">No hay ciudades registradas.</td></tr>
<?php else: ?>
<?php foreach($result_ciudades as $ciudad): ?>
<tr>
<td><?php echo htmlspecialchars($ciudad['id_ciudad']); ?></td>
<td><?php echo htmlspecialchars($ciudad['nombre']); ?></td>
<td><?php echo htmlspecialchars($ciudad['provincia']); ?></td>
<td class="acciones-botones">
<a id="editar" href="index.php?page=ciudades&id=<?php echo htmlspecialchars($ciudad['id_ciudad']); ?>">
<i class="fa-solid fa-pen-to-square"></i>
</a>

<button class="btn-eliminar" 
        data-id="<?php echo htmlspecialchars($ciudad['id_ciudad']); ?>">
    <i class="fa-solid fa-trash"></i>
</button>

</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
<script src="assets/js/validaciones/usuarios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>
</body>
</html>
