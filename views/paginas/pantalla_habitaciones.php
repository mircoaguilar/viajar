<?php
require_once __DIR__ . '/../../models/hotel.php';
require_once __DIR__ . '/../../models/hotel_habitaciones.php';

$id_hotel = (int)($_GET['id'] ?? 0);
if (!$id_hotel) {
    header("Location: /viajar/index.php?page=pantalla_hoteles&message=Falta+ID+de+hotel");
    exit;
}

$hotelModel = new Hotel();
$hotelesArray = $hotelModel->traer_hotel($id_hotel);
if (empty($hotelesArray) || !is_array($hotelesArray)) {
    header("Location: /viajar/index.php?page=pantalla_hoteles&message=Hotel+no+encontrado");
    exit;
}
$hotel = $hotelesArray[0];

$habitacionModel = new Hotel_Habitaciones();
$habitaciones = $habitacionModel->traer_por_hotel($id_hotel);
if (!is_array($habitaciones)) $habitaciones = [];

$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';
$personasFiltro = (int)($_GET['personas'] ?? 0);

if ($personasFiltro > 0) {
    $habitaciones = array_filter($habitaciones, function($hab) use ($personasFiltro) {
        return $hab['capacidad_maxima'] >= $personasFiltro;
    });
}

if (!empty($checkin) && !empty($checkout)) {
    $habitaciones = array_filter($habitaciones, function($hab) use ($habitacionModel, $checkin, $checkout) {
        return $habitacionModel->disponibleEnFechas($hab['id_hotel_habitacion'], $checkin, $checkout);
    });
}
?>

<link rel="stylesheet" href="/viajar/assets/css/pantalla_habitaciones.css">

<section class="habitaciones-hero">
  <h1>Habitaciones en <?= htmlspecialchars($hotel['hotel_nombre'] ?? '-') ?></h1>
</section>

<section class="habitaciones-buscador container">
  <form id="form-buscador-habitaciones" method="get" action="index.php">
      <input type="hidden" name="page" value="pantalla_habitaciones">
      <input type="hidden" name="id" value="<?= (int)$id_hotel ?>">

      <div class="buscador-group">
        <label for="checkin">Check-in</label>
        <input type="text" id="checkin" name="checkin" value="<?= htmlspecialchars($checkin) ?>" required>
      </div>
      <div class="buscador-group">
        <label for="checkout">Check-out</label>
        <input type="text" id="checkout" name="checkout" value="<?= htmlspecialchars($checkout) ?>" required>
      </div>
      <div class="buscador-group">
        <label for="personas">Personas</label>
        <input type="number" id="personas" name="personas" min="1" value="<?= htmlspecialchars($personasFiltro ?: 1) ?>" required>
      </div>
      <button type="submit" class="btn btn-buscar">Buscar</button>
  </form>
</section>

<section class="habitaciones-lista container">
  <?php if (empty($habitaciones)): ?>
    <p>No hay habitaciones disponibles en este hotel para los criterios seleccionados.</p>
  <?php else: ?>
    <?php foreach ($habitaciones as $hab): ?>
      <?php 
        $fotos = $hab['fotos'] ?? [];
        if (empty($fotos)) $fotos = ['assets/images/placeholder.jpg'];
      ?>
      <div class="habitacion-card">
        <div class="habitacion-info">
          <h3><?= htmlspecialchars($hab['tipo_nombre'] ?? 'Habitación') ?></h3>
          <p><?= nl2br(htmlspecialchars($hab['descripcion'] ?? '')) ?></p>
          <p><strong>Capacidad:</strong> <?= (int)($hab['capacidad_maxima'] ?? 0) ?> personas</p>
          <p class="precio">
            $<?= number_format($hab['precio_base_noche'] ?? 0, 0, ',', '.') ?> / noche
          </p>

          <script>
            console.log({
              id_hab: <?= (int)$hab['id_hotel_habitacion'] ?>,
              id_hotel: <?= (int)$id_hotel ?>,
              checkin: '<?= $checkin ?>',
              checkout: '<?= $checkout ?>',
              personas: <?= (int)$personasFiltro ?>,
              precio_unitario: <?= (float)$hab['precio_base_noche'] ?>
            });
          </script>

          <button class="btn btn-reservar"
            onclick="agregarAlCarrito(
                <?= (int)$hab['id_hotel_habitacion'] ?>,
                <?= (int)$id_hotel ?>,
                '<?= $checkin ?>',
                '<?= $checkout ?>',
                <?= (int)$personasFiltro ?>,
                <?= (float)$hab['precio_base_noche'] ?>
            )">Agregar al carrito</button>
        </div>

        <div class="habitacion-fotos">
          <img class="foto-principal" src="/viajar/<?= htmlspecialchars($fotos[0]) ?>" alt="Habitación" data-original="/viajar/<?= htmlspecialchars($fotos[0]) ?>">
          <?php if (count($fotos) > 1): ?>
            <div class="miniaturas">
              <?php foreach ($fotos as $foto): ?>
                  <img src="/viajar/<?= htmlspecialchars($foto) ?>" alt="Foto de habitación">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const USER_ID = <?= (int)($_SESSION['id_usuarios'] ?? 0) ?>;
</script>

<script src="/viajar/assets/js/pantalla_habitaciones.js"></script>
<script src="/viajar/assets/js/carrito.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

