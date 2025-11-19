<?php
if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/hotel.php');
require_once('models/hotel_habitaciones.php');

$hotelModel = new Hotel();
$habitacionModel = new Hotel_Habitaciones();

$id_usuario = $_SESSION['id_usuarios'];
$hoteles = $hotelModel->traer_hoteles($id_usuario);


$id_habitacion_url = $_GET['id_habitacion'] ?? null;
$habitacion_info = null;
$hotel_preseleccionado = null;

if ($id_habitacion_url) {
    $habitacion_info = $habitacionModel->traer_por_id($id_habitacion_url);

    if ($habitacion_info) {
        $hotel_preseleccionado = $habitacion_info['rela_hotel'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cargar Stock de Habitaciones</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
  <link rel="stylesheet" href="assets/css/hotel_carga.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <style>
    .error { color: red; font-size: 0.85rem; margin-top: 3px; }
    .is-invalid { border-color: red !important; background: #ffeaea; }
    #form-alert { color: red; margin-bottom: 10px; display: none; font-weight: 600; }
  </style>
</head>
<body>

<main class="contenido-principal">
  <div class="container">
    <div class="panel">
      <h2>Cargar Stock de Habitaciones</h2>
      <p class="hint">Seleccioná el hotel y la habitación, luego indicá cantidad disponible por fecha.</p>

      <div id="form-alert"></div>

      <form id="form-stock" class="grid grid-2">
        <input type="hidden" name="action" value="guardar_stock">

        <div>
          <label for="rela_hotel">Hotel</label>
          <select id="rela_hotel" name="rela_hotel">
            <option value="">Seleccionar hotel...</option>
            <?php foreach ($hoteles as $h): ?>
              <option 
                value="<?= $h['id_hotel'] ?>" 
                <?= ($hotel_preseleccionado == $h['id_hotel']) ? 'selected' : '' ?>
              >
                <?= htmlspecialchars($h['hotel_nombre']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="error"></div>
        </div>

        <div>
          <label for="rela_habitacion">Habitación</label>
          <select id="rela_habitacion" name="rela_habitacion">
            <option value="">Seleccionar habitación...</option>
          </select>
          <div class="error"></div>
        </div>

        <div>
          <label for="fecha_inicio">Fecha de inicio</label>
          <input type="text" id="fecha_inicio" name="fecha_inicio">
          <div class="error"></div>
        </div>

        <div>
          <label for="fecha_fin">Fecha fin</label>
          <input type="text" id="fecha_fin" name="fecha_fin">
          <div class="error"></div>
        </div>

        <div>
          <label for="cantidad">Cantidad disponible</label>
          <input type="number" id="cantidad" name="cantidad" min="0" value="1">
          <div class="error"></div>
        </div>

        <div style="grid-column: 1 / -1;">
          <button type="button" id="previsualizar" class="btn">Previsualizar</button>
        </div>

        <table id="tabla-preview" class="table" style="display:none;">
          <thead>
            <tr>
              <th>Habitación</th>
              <th>Fecha</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        <div class="actions" style="grid-column: 1 / -1;">
          <button type="submit" class="btn">Guardar Stock</button>
          <a href="index.php?page=proveedores_perfil" class="btn secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </div>
</main>

<div id="custom-alert" style="display:none;">
  <div>
    <p id="custom-alert-msg"></p>
    <button id="custom-alert-btn" class="btn secondary">Cerrar</button>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



<script>
    const idHabitacionUrl = "<?= $id_habitacion_url ?>";
    const hotelPreseleccionado = "<?= $hotel_preseleccionado ?>";
</script>

<script src="assets/js/hoteles_stock.js"></script>

<script>
  flatpickr("#fecha_inicio", {
      dateFormat: "Y-m-d",
      minDate: "today",
      locale: "es",
      onChange: function(selectedDates, dateStr) {
          fechaFinPicker.set('minDate', dateStr);
      }
  });

  const fechaFinPicker = flatpickr("#fecha_fin", {
      dateFormat: "Y-m-d",
      minDate: "today",
      locale: "es"
  });

</script>

</body>
</html>
