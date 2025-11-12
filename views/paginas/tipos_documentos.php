<?php
require_once('models/tipo_documentos.php');
require_once('models/conexion.php'); 

$tipo_documento_model = new TipoDocumento(); 
$result_tipos_documentos = $tipo_documento_model->traer_tipos_documentos(); 

$editing_mode = false;
$id_tipo_documento_editar = '';
$nombre_form = '';
$form_action = 'guardar';

if (isset($_GET['id'])) {
    $id_tipo_documento_editar = htmlspecialchars($_GET['id']);
    $tipo_documento_data_editar = $tipo_documento_model->traer_tipo_documento($id_tipo_documento_editar); 
    
    if (!empty($tipo_documento_data_editar)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $nombre_form = htmlspecialchars($tipo_documento_data_editar[0]['nombre']);
    } else {
        header("Location: index.php?page=tipos_documentos&message=Tipo de documento a editar no encontrado o inactivo.&status=danger");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Documento</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="container">
        <form method="post" action="controllers/tipos_documentos/tipo_documentos.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_tipo_documento" value="<?php echo htmlspecialchars($id_tipo_documento_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Tipo de Documento' : 'Crear Tipo de Documento'; ?></h1>
            <label for="id_nombre">Descripción del tipo de documento</label>
            <input 
                type="text" 
                id="id_tipo_documento_nombre" 
                name="nombre" 
                placeholder="Ingrese nombre del tipo de documento" 
                value="<?php echo $nombre_form; ?>"
            >


            <button type="submit"><?php echo $editing_mode ? 'Actualizar Tipo de Documento' : 'Guardar Tipo de Documento'; ?></button>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=tipos_documentos" class="button" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Listado de Tipos de Documento</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="nombre-documento">Descripción del tipo de documento</th>
                    <th id="acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (empty($result_tipos_documentos)): ?>
                    <tr>
                        <td colspan="4">No hay tipos de documentos registrados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($result_tipos_documentos as $tipo_documento_actual){ ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tipo_documento_actual['id_tipo_documento']); ?></td>
                        <td class="nombre-documento"><?php echo htmlspecialchars($tipo_documento_actual['nombre']); ?></td>
                        <td class="acciones-botones">
                            <a id="editar" href="index.php?page=tipos_documentos&id=<?php echo htmlspecialchars($tipo_documento_actual['id_tipo_documento']); ?>">
                                <i class="fa-solid fa-pen-to-square" type="button"></i>
                            </a>
                       
                            <form method="post" action="controllers/tipos_documentos/tipo_documentos.controlador.php" onsubmit="return confirm('¿Seguro que quieres eliminar este tipo de documento?');">
                                <input type="hidden" name="action" value="eliminar"> 
                                <input type="hidden" name="id_tipo_documento_eliminar" value="<?php echo htmlspecialchars($tipo_documento_actual['id_tipo_documento']); ?>">
                                <button id="eliminar" type="submit"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>

</html>
