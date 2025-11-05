<?php

require_once('models/provincia.php');
require_once('models/conexion.php'); 

$provincia_model = new Provincia(); 

$result_provincias = $provincia_model->traer_provincias(); 

$editing_mode = false;
$id_provincia_editar = '';
$provincia_nombre_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_provincia_editar = htmlspecialchars($_GET['id']);
    $provincia_data_editar = $provincia_model->traer_provincia($id_provincia_editar); 
    
    if (!empty($provincia_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $provincia_nombre_form = htmlspecialchars($provincia_data_editar[0]['nombre']);
    } else {
        header("Location: index.php?page=provincias&message=Provincia a editar no encontrada o inactiva.&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es"> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Provincias</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="container">
        <form method="post" action="controllers/provincias/provincias.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_provincia" value="<?php echo htmlspecialchars($id_provincia_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Provincia' : 'Crear Provincia'; ?></h1>
            <label for="id_provincia_nombre">Nombre de la provincia</label>
            <input 
                type="text" 
                id="id_provincia_nombre" 
                name="nombre" 
                placeholder="Ingrese nombre de la provincia" 
                value="<?php echo $provincia_nombre_form; ?>"
            >

            <button type="submit"><?php echo $editing_mode ? 'Actualizar Provincia' : 'Guardar Provincia'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=provincias" class="button" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Listado de Provincias</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="nombre-provincia">Nombre de la provincia</th>
                    <th id="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($result_provincias)): ?>
                    <tr>
                        <td colspan="4">No hay provincias registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($result_provincias as $provincia_actual){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($provincia_actual['id_provincia']); ?></td>
                        <td class="nombre-provincia"><?php echo htmlspecialchars($provincia_actual['nombre']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=provincias&id=<?php echo htmlspecialchars($provincia_actual['id_provincia']); ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                        
                            <form method="post" action="controllers/provincias/provincias.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar esta provincia?');">
                                <input type="hidden" name="action" value="eliminar"> 
                                <input type="hidden" name="id_provincia_eliminar" value="<?php echo htmlspecialchars($provincia_actual['id_provincia']); ?>">
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
