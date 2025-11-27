<?php
require_once __DIR__ . '/../../models/hotel.php';
require_once __DIR__ . '/../../models/hotel_habitaciones.php';
require_once __DIR__ . '/../../models/hotelinfo.php';

$id_hotel = (int)($_GET['id'] ?? 0);
if (!$id_hotel) {
    header("Location: index.php?page=pantalla_hoteles");
    exit;
}

$hotelModel = new Hotel();
$hotelesArray = $hotelModel->traer_hotel($id_hotel);
if (empty($hotelesArray) || !is_array($hotelesArray)) {
    header("Location: index.php?page=pantalla_hoteles&message=Hotel+no+encontrado");
    exit;
}
$hotel = $hotelesArray[0];

$infoModel = new HotelInfo();
$hotelInfoArray = $infoModel->traer_por_hotel($id_hotel);
$hotelInfo = [];
if (!empty($hotelInfoArray) && is_array($hotelInfoArray)) {
    $hotelInfo = $hotelInfoArray[0]; 
}

$habitacionModel = new Hotel_Habitaciones();
$habitaciones = $habitacionModel->traer_por_hotel($id_hotel);
if (!is_array($habitaciones)) $habitaciones = [];

$price_field = null;
foreach ($habitaciones as $h) {
    if (isset($h['precio_por_noche'])) { $price_field = 'precio_por_noche'; break; }
    if (isset($h['precio_base_noche'])) { $price_field = 'precio_base_noche'; break; }
    if (isset($h['precio'])) { $price_field = 'precio'; break; }
}

$min_price = 0;
if ($price_field && !empty($habitaciones)) {
    $prices = array_column($habitaciones, $price_field);
    if (!empty($prices)) $min_price = min($prices);
}

function get_room_field($room) {
    if (isset($room['id_hotel_habitacion'])) return 'id_hotel_habitacion';
    if (isset($room['id_habitacion'])) return 'id_habitacion';
    foreach ($room as $k => $v) {
        if (stripos($k, 'id_') === 0) return $k;
    }
    return null;
}

$fotos_adicionales = !empty($hotelInfo['fotos']) ? json_decode($hotelInfo['fotos'], true) : [];
if (!is_array($fotos_adicionales)) $fotos_adicionales = [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($hotel['hotel_nombre'] ?? 'Hotel') ?> | viajAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/detalle_servicio.css">
  <link rel="stylesheet" href="assets/css/footer.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<section class="hotel-hero">
  <img src="assets/images/<?= htmlspecialchars($hotel['imagen_principal'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($hotel['hotel_nombre'] ?? '') ?>">
  <div class="overlay">
    <h1><?= htmlspecialchars($hotel['hotel_nombre'] ?? '-') ?></h1>
    <?php if (!empty($hotelInfo['direccion'])): ?>
      <p><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($hotelInfo['direccion']) ?></p>
    <?php endif; ?>
  </div>
</section>

<section class="hotel-main container">
  <div class="info">
    <h2>Sobre el hotel</h2>
    <p><?= nl2br(htmlspecialchars($hotelInfo['descripcion'] ?? '-')) ?></p>

    <?php if (!empty($hotelInfo['servicios'])): ?>
      <h3>Servicios destacados</h3>
      <ul class="servicios">
        <?php foreach(explode(',', $hotelInfo['servicios']) as $servicio): ?>
          <li><i class="fa-solid fa-check"></i> <?= htmlspecialchars(trim($servicio)) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (!empty($hotelInfo['politicas_cancelacion'])): ?>
      <h3>Políticas de cancelación</h3>
      <p><?= nl2br(htmlspecialchars($hotelInfo['politicas_cancelacion'])) ?></p>
    <?php endif; ?>

    <?php if (!empty($hotelInfo['reglas'])): ?>
      <h3>Reglas del hotel</h3>
      <p><?= nl2br(htmlspecialchars($hotelInfo['reglas'])) ?></p>
    <?php endif; ?>
  </div>

  <aside class="reserva-card">
    <h3>Reservar ahora</h3>
    <p>
      Desde
      <strong>
        <?= $min_price > 0 ? '$' . number_format($min_price, 0, ',', '.') : 'Consultar' ?>
      </strong>
      por noche
    </p>
    <a href="index.php?page=pantalla_habitaciones&id=<?= $hotel['id_hotel'] ?>" class="btn">Ver habitaciones</a>
  </aside>
</section>

<?php if (!empty($fotos_adicionales)): ?>
<section class="hotel-fotos container">
  <h2>Fotos adicionales</h2>
  <div class="carrusel-fotos">
    <?php foreach($fotos_adicionales as $foto): ?>
      <div class="foto-item">
        <img src="assets/images/<?= htmlspecialchars($foto) ?>" alt="Foto adicional del hotel">
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<section class="hotel-ver-habitaciones container">
  <a href="index.php?page=pantalla_habitaciones&id=<?= $hotel['id_hotel'] ?>" class="btn btn-reservar">
    Reservar ahora
  </a>
</section>

<?php if (!empty($hotelInfo['direccion'])): ?>
<section class="hotel-mapa container">
  <h2>Ubicación</h2>
  <iframe src="https://www.google.com/maps?q=<?= urlencode($hotelInfo['direccion']) ?>&output=embed"
          width="100%" height="300" style="border:0;" allowfullscreen loading="lazy"></iframe>
</section>
<?php endif; ?>

<?php include_once(__DIR__ . '/../componentes/pie.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr(".fecha-ingreso", { dateFormat: "Y-m-d", minDate: "today", locale: "es" });
  flatpickr(".fecha-egreso", { dateFormat: "Y-m-d", minDate: "today", locale: "es" });
</script>
<script src="assets/js/detalle_servicio.js"></script>
</body>
</html>
