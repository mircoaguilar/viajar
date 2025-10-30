<?php

require_once('models/personas.php');
require_once('models/perfiles.php');
require_once('models/usuarios.php');

$page_size = 5; 
$current_page = 0;

if (isset($_GET['current_page'])) {
    $current_page = (int)$_GET['current_page']; 
    if ($current_page < 0) $current_page = 0; 
}
    
$persona = new Persona();
$personas = $persona->traer_personas(); 

$perfil = new Perfil();
$perfiles = $perfil->traer_perfiles(); 

$usuarios = new Usuario();
$usuarios->page_size = $page_size; 
$usuarios->current_page = $current_page; 
$result_usuarios = $usuarios->traer_usuarios(); 

$total_rows = 0; 
$result_usuarios_cantidad_array = $usuarios->traer_usuarios_cantidad();
if (!empty($result_usuarios_cantidad_array) && isset($result_usuarios_cantidad_array[0]['total'])) {
    $total_rows = $result_usuarios_cantidad_array[0]['total'];
}
$total_pages = ceil($total_rows / $page_size); 
if ($total_pages == 0) $total_pages = 1; 


$editing_mode = false;
$id_usuario_editar = '';
$usuario_nombre_form = '';
$usuario_email_form = '';
$usuario_rela_persona_form = ''; 
$usuario_rela_perfil_form = '';  
$form_action = 'guardar'; 

if (isset($_GET['id'])) {
    $id_usuario_editar = htmlspecialchars($_GET['id']);
    $usuario_data_para_form = $usuarios->traer_usuarios_por_id($id_usuario_editar); 

    if (!empty($usuario_data_para_form)) {
        $editing_mode = true;
        $form_action = 'actualizar';
        $usuario_nombre_form = htmlspecialchars($usuario_data_para_form[0]['usuarios_nombre_usuario']);
        $usuario_email_form = htmlspecialchars($usuario_data_para_form[0]['usuarios_email']);
        $usuario_rela_persona_form = htmlspecialchars($usuario_data_para_form[0]['rela_personas']);
        $usuario_rela_perfil_form = htmlspecialchars($usuario_data_para_form[0]['rela_perfiles']);
    } else {
        header("Location: index.php?page=usuarios&message=Usuario a editar no encontrado o inactivo&status=danger");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es"> 
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" href="assets/css/listado_usuarios.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <form id="form-crear-usuario" method="post" action="controllers/usuarios/usuarios.controlador.php">
            <input type="hidden" name="action" value="<?php echo $form_action; ?>" />
            <?php if ($editing_mode): ?>
                <input type="hidden" name="id_usuarios" value="<?php echo htmlspecialchars($id_usuario_editar); ?>" />
            <?php endif; ?>

            <h1><?php echo $editing_mode ? 'Editar Usuario' : 'Crear Usuario'; ?></h1>
            
            <div class="grupo-select">
                <p id="error-persona" class="alerta-error"></p> 
                <select name="rela_personas" id="rela_personas"
                    <?php echo ($editing_mode) ? 'disabled' : ''; ?> > <option value="">Seleccionar la persona correspondiente</option>
                    <?php foreach ($personas as $persona_data){  ?>
                        <option value="<?php echo htmlspecialchars($persona_data['id_personas']); ?>"
                            <?php echo ($editing_mode && $usuario_rela_persona_form == $persona_data['id_personas']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($persona_data['personas_nombre'] . ' ' . $persona_data['personas_apellido'] . ' - ' . $persona_data['personas_dni']); ?>
                        </option>
                    <?php } ?>
                </select>
                <?php if ($editing_mode): ?>
                    <input type="hidden" name="rela_personas" value="<?php echo htmlspecialchars($usuario_rela_persona_form); ?>">
                <?php endif; ?>
            </div>

            <div class="grupo-input">
                <p id="error-usuario" class="alerta-error"></p>
                <input type="text" onfocusout="validate_username(event)" id="id_nombre_usuario" name="usuarios_nombre_usuario" placeholder="Nombre de usuario" value="<?php echo htmlspecialchars($usuario_nombre_form); ?>">
            </div>

            <div class="grupo-input">
                <p id="error-email" class="alerta-error"></p>
                <input type="email" onfocusout="validate_email(event)" id="id_email" name="usuarios_email" placeholder="Correo electrónico"  value="<?php echo htmlspecialchars($usuario_email_form); ?>">
            </div>

            <div class="grupo-select">
                <p id="error-perfil" class="alerta-error"></p>
                <select name="rela_perfiles" id="rela_perfiles" >
                    <option value="">Seleccionar un perfil</option>
                    <?php foreach ($perfiles as $perfil_data){  ?>
                        <option value="<?php echo htmlspecialchars($perfil_data['id_perfiles']); ?>"
                            <?php echo ($editing_mode && $usuario_rela_perfil_form == $perfil_data['id_perfiles']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($perfil_data['perfiles_nombre']); ?>
                        </option>
                    <?php } ?>
                </select> 
            </div>
    <div>
            <button class="button-usuarios" type="button" onclick="validarFormulario()">Guardar</button>
        </form>
            <?php if ($editing_mode): ?>
                <a href="index.php?page=usuarios" class="button cancel" style="margin-left: 10px;">Cancelar Edición</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="container">
        <h2>Usuarios registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th> 
                    <th>Nombre de usuario</th> 
                    <th>Correo electrónico</th> 
                    <th>Persona asociada</th> 
                    <th>Perfil</th> 
                    <th id="acciones" colspan="3">Acciones</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php 
                if (!empty($result_usuarios)):
                    foreach ($result_usuarios as $row): 
                ?>
                    <tr> 
                        <td><?php echo htmlspecialchars($row['id_usuarios']); ?></td> 
                        <td><?php echo htmlspecialchars($row['usuarios_nombre_usuario']); ?></td> 
                        <td><?php echo htmlspecialchars($row['usuarios_email']); ?></td> 
                        <td>
                        <?php echo htmlspecialchars($row['personas_nombre'] . ' ' . $row['personas_apellido']); ?>
                        <a href="#" class="editar-persona-btn" data-persona-id="<?php echo htmlspecialchars($row['rela_personas']); ?>" title="Editar persona">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        </td>
                        <td><?php echo htmlspecialchars($row['perfiles_nombre']); ?></td> 
                        <td><a id="editar" href="index.php?page=usuarios&id=<?php echo htmlspecialchars($row['id_usuarios']); ?>"><i class="fa-solid fa-pen-to-square"></i></a></td> 
                        <td> 
                            <button class="btn-eliminar" 
                                    data-id="<?php echo htmlspecialchars($row['id_usuarios']); ?>" 
                                    data-entity="usuarios" 
                                    data-action="eliminar">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td> 
                        <td>
                            <a id="reset" href="controllers/usuarios/email.controlador.php?action=reset_password_admin&email=<?php echo urlencode($row['usuarios_email']); ?>&origin=admin">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        </td>
                    </tr> 
                <?php 
                    endforeach; 
                else:
                ?>
                    <tr>
                        <td colspan="7">No hay usuarios registrados.</td>
                    </tr>
                <?php
                endif;
                ?>
            </tbody> 
        </table> 
        <nav class="paginacion"> 
            <ul> 
                <li> 
                    <a href="index.php?page=usuarios&current_page=<?php echo max(0, $current_page - 1); ?>"
                       class="<?php echo ($current_page <= 0) ? 'disabled' : ''; ?>">Atrás</a> 
                </li> 
                <li> 
                    <span class="numero-pagina-actual"> 
                        <?php echo ($current_page + 1);?> de <?php echo $total_pages; ?>
                    </span> 
                </li> 
                <li> 
                    <a href="index.php?page=usuarios&current_page=<?php echo min($total_pages - 1, $current_page + 1); ?>"
                       class="<?php echo ($current_page >= $total_pages - 1) ? 'disabled' : ''; ?>">Siguiente</a> 
                </li> 
            </ul> 
        </nav> 
    </div>
<div id="editar-persona-modal" style="display:none; position:fixed; top:5%; left:50%; transform:translateX(-50%); background:#fff; border:1px solid #ccc; padding:30px; z-index:1000; width:600px; max-width:90%;">
  <h3>Datos personales</h3>
  <div id="datos-persona-lectura">
  </div>
  <div id="datos-persona-edicion" style="display:none;">
    <p style="color:#c00; font-weight:bold;">
      ⚠️ Los cambios impactarán en toda la base de datos. Confirmá que este DNI pertenece al usuario correcto.
    </p>
    <form id="form-editar-persona">
      <input type="hidden" name="id_personas" id="id_personas">
      <label for="personas_nombre">Nombre:</label>
      <input type="text" name="personas_nombre" id="personas_nombre"><br><br>
      <label for="personas_apellido">Apellido:</label>
      <input type="text" name="personas_apellido" id="personas_apellido"><br><br>
      <label for="personas_dni">DNI:</label>
      <input type="text" name="personas_dni" id="personas_dni"><br><br>
      <button type="submit">Guardar cambios</button>
      <button type="button" id="cancelar-edicion-persona" class="button cancel">Cancelar edición</button>
    </form>
  </div>
  <button id="boton-editar-persona" class="button-usuarios">Editar datos personales</button>
  <button id="cerrar-modal-persona" class="button cancel">Cerrar</button>
</div>
<div id="overlay" style="display:none; position:fixed; top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5); z-index:900;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/validaciones/usuarios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>

</body>
</html>