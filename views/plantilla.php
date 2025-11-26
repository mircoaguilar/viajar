<?php
session_start();
date_default_timezone_set('America/Argentina/Cordoba');

require_once('models/modulos.php');

function guard($id_perfiles) {
    if ($id_perfiles == 2) {
        return true;
    }

    $modulos = new Modulo();
    $resultados = $modulos->traer_modulos_por_perfil($id_perfiles);
    $pagina = $_GET['page'] ?? '';

    foreach ($resultados as $row) {
        $modulo = $row['modulos_nombre'];
        if ($pagina === $modulo) {
            return true;
        }

        if (strpos($pagina, $modulo) === 0) {
            return true;
        }
    }
    return false;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViajAR</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" href="assets/css/notificaciones.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <?php if (isset($_SESSION['id_perfiles']) && $_SESSION['id_perfiles'] == 2): ?>
        <link rel="stylesheet" href="assets/css/cabecera_admin.css">
        <script src="assets/js/cabecera_admin.js"></script>
    <?php elseif (isset($_SESSION['id_perfiles']) && $_SESSION['id_perfiles'] == 1): ?>
        <link rel="stylesheet" href="assets/css/cabecera_cliente.css">
        <script src="assets/js/cabecera_cliente.js"></script>
    <?php elseif (isset($_SESSION['id_perfiles']) && in_array($_SESSION['id_perfiles'], [3,5,13,14])): ?>
        <link rel="stylesheet" href="assets/css/cabecera_proveedores.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/slideout/1.0.1/slideout.min.js"></script>
        <script src="assets/js/cabecera_proveedores.js"></script>
    <?php endif; ?>
</head>


<?php
if (isset($_SESSION['id_perfiles'])) {
    $perfil = $_SESSION['id_perfiles'];

    if ($perfil == 2) {
        include('views/componentes/cabecera.admin.php');
    } elseif (in_array($perfil, [3, 5, 13, 14])) {
        include('views/componentes/cabecera.proveedores.php');
    } elseif ($perfil == 1) {
        include('views/componentes/cabecera.cliente.php');
    } else {
        include('views/componentes/cabecera.php');
    }
} else {
    include('views/componentes/cabecera.php');
}
?>

<div class="container">
<?php
if (isset($_GET['page'])) {
    $pagina = $_GET['page'];

    $paginas_publicas = [
        'login',
        'registro',
        'pantalla_hoteles',
        'pantalla_guias',
        'pantalla_transporte',
        'recuperar_contrasena',
        'cambiar_password',
        'detalle_hotel',
        'detalle_tour',
        'detalle_viaje',
        'pantalla_habitaciones',
        'ganancias',
        'tours_editar'
    ];
if (in_array($pagina, $paginas_publicas)) {

    if ($pagina === 'ganancias') {
        include('controllers/ganancias.php');  // ← ESTE ES EL CONTROLADOR
    } else {
        include('views/paginas/' . $pagina . '.php');
    }

}
 else {
        if (isset($_SESSION['usuarios_nombre_usuario'])) {
            if (isset($_SESSION['id_perfiles']) && guard($_SESSION['id_perfiles'])) {
                if (file_exists('views/paginas/' . $pagina . '.php')) {
                    include('views/paginas/' . $pagina . '.php');
                } else {
                    include('views/paginas/errores/404.php');
                }
            } else {
                include('views/paginas/errores/403.php');
            }
        } else {
            include('views/paginas/errores/403.php');
        }
    }
} else {
    include('views/paginas/pantalla_hoteles.php');
}
?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    $('#rela_perfiles').select2({ width: '100%' });
    $('#rela_personas').select2({ width: '100%' });
    $('#id_modulos_select').select2({ width: '100%' });
    $('#origen').select2({ placeholder: "Seleccionar origen", allowClear: true, width: '100%' });
    $('#destino').select2({ placeholder: "Seleccionar destino", allowClear: true, width: '100%' });
});
</script>

<?php if (isset($_SESSION['id_perfiles']) && in_array($_SESSION['id_perfiles'], [3, 5, 13, 14])): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slideout/1.0.1/slideout.min.js"></script>
<script src="assets/js/cabecera_proveedores.js"></script>
<?php endif; ?>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="assets/js/notificaciones.js"></script>
</body>
</html>
