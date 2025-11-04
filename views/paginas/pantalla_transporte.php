<?php
require_once('models/transporte.php');
require_once('models/viaje.php');
require_once('models/ciudad.php');

$ciudadModel = new Ciudad();
$ciudades = $ciudadModel->traer_ciudades();

$destino = trim($_GET['destino'] ?? '');
$desde   = $_GET['desde'] ?? '';
$hasta   = $_GET['hasta'] ?? '';

$viajes = [];

$transporteModel = new Transporte();
if ($destino || $desde || $hasta) {
    $viajes = $transporteModel->buscar($destino, $desde, $hasta);
} else {
    $viajeModel = new Viaje();
    $viajes = $viajeModel->traer_viajes_proximos(10);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Transporte | ViajAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/pantalla_transporte1.css">
  <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
</head>
<body>

<section class="hero-section">
  <div class="hero-content">
    <h1>Encontrá tu transporte en Formosa</h1>
    <p>Viajes seguros y confiables para moverte por nuestra provincia.</p>
  </div>
</section>


<section class="search-section">
  <div class="search-container">
    <h2>Buscar viaje</h2>
    <form class="search-form" method="GET">
      <input type="hidden" name="page" value="pantalla_transporte">

      <div class="form-group">
        <label>Origen</label>
        <select id="origen" name="origen" style="width: 100%;">
            <option value=""></option>
            <?php foreach ($ciudades as $ciudad): ?>
                <option value="<?= htmlspecialchars($ciudad['id_ciudad']) ?>" 
                    <?= ($destino == $ciudad['id_ciudad']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ciudad['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Destino</label>
        <select id="destino" name="destino" style="width: 100%;">
            <option value=""></option>
            <?php foreach ($ciudades as $ciudad): ?>
                <option value="<?= htmlspecialchars($ciudad['id_ciudad']) ?>" 
                    <?= ($destino == $ciudad['id_ciudad']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ciudad['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Desde</label>
        <input type="text" name="desde" id="desde" placeholder="DD/MM/AAAA" value="<?= htmlspecialchars($_GET['desde'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label>Hasta</label>
        <input type="text" name="hasta" id="hasta" placeholder="DD/MM/AAAA" value="<?= htmlspecialchars($_GET['hasta'] ?? '') ?>">
      </div>

      <div class="form-group full-width">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
    </form>
  </div>
</section>

<section class="viajes">
  <h2>Próximos viajes</h2>
  <div class="tarjetas">
    <?php if (!empty($viajes)): ?>
      <?php foreach ($viajes as $viaje): ?>
        <div class="tarjeta">
          <img src="assets/images/<?= htmlspecialchars($viaje['imagen_principal']) ?>" alt="<?= htmlspecialchars($viaje['nombre_servicio']) ?>">
          <div class="contenido">
            <h3><?= htmlspecialchars($viaje['nombre_servicio']) ?></h3>
            <p><strong>Ruta:</strong> <?= htmlspecialchars($viaje['origen']) ?> → <?= htmlspecialchars($viaje['destino']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($viaje['viaje_fecha']) ?></p>
            <p><strong>Hora salida:</strong> <?= htmlspecialchars($viaje['hora_salida']) ?></p>
            <p><strong>Precio:</strong> $<?= number_format($viaje['precio_por_persona'], 0, ',', '.') ?></p>
            <a href="index.php?page=detalle_viaje&id=<?= $viaje['id_viajes'] ?>" class="boton-ver-mas">Ver más</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No se encontraron viajes para tu búsqueda.</p>
    <?php endif; ?>
  </div>
</section>

<?php include_once("views/componentes/pie.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
flatpickr("#desde", { dateFormat: "Y-m-d", minDate: "today", locale: "es" });
flatpickr("#hasta", { dateFormat: "Y-m-d", minDate: "today", locale: "es" });
</script>

<script src="assets/js/pantalla_transporte.js"></script>

</body>
</html>
