<?php
require_once('models/estado_reserva.php');
require_once('models/conexion.php'); 

$estado_model = new EstadoReserva(); 
$result_estados = $estado_model->traer_estados(); 

$editing_mode = false;
$id_estado_editar = '';
$estado_nombre_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_estado_editar = htmlspecialchars($_GET['id']);
    $estado_data_editar = $estado_model->traer_estado($id_estado_editar); 
    
    if (!empty($estado_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $estado_nombre_form = htmlspecialchars($estado_data_editar[0]['nombre_estado']);
    } else {
        header("Location: index.php?page=estado_reserva&message=Estado no encontrado o inactivo.&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es"> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estados de Reserva</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <form method="post" action="controllers/estado_reservas/estado_reservas.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_estado_reserva" value="<?php echo htmlspecialchars($id_estado_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Estado de Reserva' : 'Crear Estado de Reserva'; ?></h1>
            <label for="id_estado_nombre">Nombre del estado</label>
            <input 
                type="text" 
                id="id_estado_nombre" 
                name="nombre_estado" 
                placeholder="Ej: Pendiente, Confirmada..." 
                value="<?php echo $estado_nombre_form; ?>"
            >

            <button type="submit"><?php echo $editing_mode ? 'Actualizar Estado' : 'Guardar Estado'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=estado_reserva" class="button" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Listado de Estados de Reserva</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="nombre-estado">Nombre del estado</th>
                    <th id="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($result_estados)): ?>
                    <tr>
                        <td colspan="4">No hay estados registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($result_estados as $estado_actual){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($estado_actual['id_estado_reserva']); ?></td>
                        <td class="nombre-estado"><?php echo htmlspecialchars($estado_actual['nombre_estado']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=estado_reserva&id=<?php echo htmlspecialchars($estado_actual['id_estado_reserva']); ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                        
                        
                            <form method="post" action="controllers/estado_reservas/estado_reservas.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este estado?');">
                                <input type="hidden" name="action" value="eliminar"> 
                                <input type="hidden" name="id_estado_eliminar" value="<?php echo htmlspecialchars($estado_actual['id_estado_reserva']); ?>">
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
