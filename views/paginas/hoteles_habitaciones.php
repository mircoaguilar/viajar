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
  img.thumb { width: 80px; height: 60px; object-fit: cover; }
  .actions button, .actions a { margin-right: 5px; }
  .status-active { color: green; font-weight: bold; }
  .status-inactive { color: red; font-weight: bold; }
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
            $fotos = (is_string($h['fotos']) ? json_decode($h['fotos'], true) : []);
            $fotoPrincipal = $fotos[0] ?? '';
            $statusClass = ($h['activo'] == 1) ? 'status-active' : 'status-inactive';
          ?>
          <tr>
            <td>
              <?php if ($fotoPrincipal): ?>
                <img src="assets/images/<?= htmlspecialchars($fotoPrincipal) ?>" class="thumb">
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($h['tipo_nombre']) ?></td>
            <td><?= htmlspecialchars($h['capacidad_maxima']) ?></td>
            <td>$ <?= number_format($h['precio_base_noche'], 2) ?></td>
            <td class="<?= $statusClass ?>"><?= ($h['activo']==1)?'Activo':'Inactivo' ?></td>
            <td class="actions">
              <a href="index.php?page=editar_habitacion&id_habitacion=<?= $h['id_hotel_habitacion'] ?>" class="btn">Editar</a>
              <a href="index.php?page=toggle_habitacion&id_habitacion=<?= $h['id_hotel_habitacion'] ?>" class="btn">
                <?= ($h['activo']==1)?'Desactivar':'Activar' ?>
              </a>
              <a href="index.php?page=eliminar_habitacion&id_habitacion=<?= $h['id_hotel_habitacion'] ?>" class="btn btn-danger" onclick="return confirm('¿Seguro que querés eliminar esta habitación?')">Eliminar</a>
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
</body>
</html>
