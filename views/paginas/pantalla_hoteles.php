<?php
require_once('models/hotel.php');
require_once('models/ciudad.php');


$ciudadModel = new Ciudad();
$ciudades = $ciudadModel->traer_ciudades();


$destino = trim($_GET['destino'] ?? '');
$desde   = $_GET['desde'] ?? '';
$hasta   = $_GET['hasta'] ?? '';

$hotelModel = new Hotel();

if ($destino || $desde || $hasta) {
    $hoteles = $hotelModel->buscar($destino, $desde, $hasta);
} else {
    $hoteles = $hotelModel->traer_hoteles();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Hoteles | ViajAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/pantalla_hoteles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>


<section class="hero-section">
  <div class="hero-content">
    <h1>Encontrá tu alojamiento en Formosa</h1>
  </div>
</section>

<section class="search-section">
  <div class="search-container">
    <form class="search-form" method="GET">
      <input type="hidden" name="page" value="pantalla_hoteles">

      <div class="form-group">
          <label for="destino">Ubicación</label>
          <select id="destino" name="destino" style="width: 100%;">
              <option value=""></option>
              <?php
              foreach ($ciudades as $ciudad) {
                  $selected = ($destino == $ciudad['id_ciudad']) ? 'selected' : '';
                  echo '<option value="'.htmlspecialchars($ciudad['id_ciudad']).'" '.$selected.'>'
                      .htmlspecialchars($ciudad['nombre']).'</option>';
              }
              ?>
          </select>
      </div>


      <div class="form-group full-width">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
    </form>
  </div>
</section>

<section class="hoteles">
  <h2>Hoteles disponibles</h2>
  <div class="tarjetas">
    <?php if (!empty($hoteles)): ?>
      <?php foreach ($hoteles as $hotel): ?>
        <div class="tarjeta">
          <img src="assets/images/<?= htmlspecialchars($hotel['imagen_principal']) ?>" alt="<?= htmlspecialchars($hotel['hotel_nombre']) ?>">
          <div class="contenido">
            <h3><?= htmlspecialchars($hotel['hotel_nombre']) ?></h3>
            <p><?= htmlspecialchars($hotel['descripcion'] ?? '-') ?></p>
            <a href="index.php?page=detalle_hotel&id=<?= $hotel['id_hotel'] ?>" class="boton-ver-mas">Ver más</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No se encontraron hoteles para tu búsqueda.</p>
    <?php endif; ?>
  </div>
</section>

<?php include_once("views/componentes/pie.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/toast.js"></script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
flatpickr("#desde", { dateFormat: "Y-m-d", minDate: "today", locale: "es" });
flatpickr("#hasta", { dateFormat: "Y-m-d", minDate: "today", locale: "es" });
</script>

<script src="assets/js/pantalla_hoteles.js"></script>
</body>
</html>
