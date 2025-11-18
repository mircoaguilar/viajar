<?php
if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/hotel.php');
require_once('models/hotel_habitaciones.php');

$id_usuario = $_SESSION['id_usuarios'];
$id_hotel = (int)($_GET['id_hotel'] ?? 0);
if (!$id_hotel) {
    header('Location: index.php?page=proveedores_perfil');
    exit;
}

$hotelModel = new Hotel();
$habitacionModel = new Hotel_Habitaciones();

if (!$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header('Location: index.php?page=proveedores_perfil&message=Acceso denegado.');
    exit;
}

$hotelData = $hotelModel->traer_hotel($id_hotel)[0] ?? null;
$habitaciones = $habitacionModel->traer_por_hotel($id_hotel);

$BASE_URL = '/viajar/'; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Habitaciones de <?= htmlspecialchars($hotelData['hotel_nombre'] ?? '') ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
<link rel="stylesheet" href="assets/css/hotel_carga.css">
<style>
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ccc; }
    img.thumb { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; }
    .actions button, .actions a { margin-right: 5px; }
    .status-active { color: green; font-weight: bold; }
    .status-inactive { color: red; font-weight: bold; }

    .modal { position: fixed; top:0; left:0; width:100%; height:100%; 
             background: rgba(0,0,0,0.5); display:flex; justify-content:center; align-items:center; display:none;}
    .modal-content { background:#fff; padding:20px; border-radius:6px; max-height:80%; overflow-y:auto; width:400px; }
    .modal-content table { width:100%; border-collapse:collapse; }
    .modal-content th, td { padding:6px; border-bottom:1px solid #ccc; text-align:left; }
    .close { float:right; cursor:pointer; font-size:18px; font-weight:bold; }
</style>
</head>
<body>
<main class="contenido-principal">
    <div class="container">
        <div class="panel">
            <h2>Habitaciones del Hotel: <?= htmlspecialchars($hotelData['hotel_nombre'] ?? '') ?></h2>

            <a href="index.php?page=hoteles_habitaciones_carga&id_hotel=<?= $id_hotel ?>" class="btn">Agregar nueva habitación</a>

            <table>
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Tipo</th>
                        <th>Capacidad</th>
                        <th>Precio por noche</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($habitaciones)): ?>
                    <?php foreach ($habitaciones as $h): 
                        $fotos = $h['fotos'];
                        if (is_string($fotos)) $fotos = json_decode($fotos, true);
                        $fotos = is_array($fotos) ? $fotos : [];
                        $fotoPrincipal = $fotos[0] ?? ''; 
                        $statusClass = ($h['activo'] == 1) ? 'status-active' : 'status-inactive';
                    ?>
                    <tr>
                        <td>
                            <?php if ($fotoPrincipal): ?>
                                <img src="<?= $BASE_URL . htmlspecialchars($fotoPrincipal) ?>" 
                                     class="thumb"
                                     onerror="this.src='<?= $BASE_URL ?>assets/images/sin-foto.jpg'">
                            <?php else: ?>
                                <img src="<?= $BASE_URL ?>assets/images/sin-foto.jpg" class="thumb">
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($h['tipo_nombre']) ?></td>
                        <td><?= htmlspecialchars($h['capacidad_maxima']) ?></td>
                        <td>$ <?= number_format($h['precio_base_noche'], 2) ?></td>
                        <td class="<?= $statusClass ?>"><?= ($h['activo']==1)?'Activo':'Inactivo' ?></td>

                        <td class="actions">
                            <a href="index.php?page=hoteles_habitaciones_editar&id_habitacion=<?= $h['id_hotel_habitacion'] ?>" 
                            class="btn" 
                            title="Editar habitación">
                                Editar
                            </a>
                            <a href="controllers/habitaciones/toggle_habitacion.php?id_habitacion=<?= $h['id_hotel_habitacion'] ?>" 
                                class="btn" 
                                title="<?= ($h['activo']==1)?'Desactivar':'Activar' ?>">
                                    <?= ($h['activo']==1)?'Desactivar':'Activar' ?>
                            </a>
                            <a href="index.php?page=hoteles_stock&id_habitacion=<?= $h['id_hotel_habitacion'] ?>" 
                            class="btn secondary" 
                            title="Gestionar stock">
                                Cargar stock
                            </a>
                            <button class="btn secondary ver-stock-btn" 
                                    data-id-habitacion="<?= $h['id_hotel_habitacion'] ?>">
                                Ver stock
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No hay habitaciones cargadas para este hotel.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="modal-stock" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Stock de la habitación</h3>
        <table id="stock-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cantidad disponible</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script src="assets/js/mis_habitaciones.js"></script>
</body>
</html>
