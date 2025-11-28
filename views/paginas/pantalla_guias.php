<?php
require_once('models/tour.php');
require_once('models/ciudad.php');

$ciudadModel = new Ciudad();
$ciudades = $ciudadModel->traer_ciudades();

$fecha = $_GET['fecha'] ?? '';

$tourModel = new Tour();

if ($fecha) {
    $tours = $tourModel->buscar($fecha);
} else {
    $tours = $tourModel->traer_tours();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Tours guiados | viajAR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets/css/pantalla_guias1.css">
</head>
<body>

<section class="hero-section">
  <div class="hero-content">
    <h1>Descubrí los mejores tours guiados en Formosa</h1>
    <p>Explorá con expertos y disfrutá de experiencias únicas en nuestra provincia.</p>
  </div>
</section>

<section class="search-section">
  <div class="search-container">
    <h2>Buscar tour guiado</h2>
    <form class="search-form" method="GET">
      <input type="hidden" name="page" value="pantalla_guias">

      <div class="form-group">
          <label>Fecha</label>
          <input type="text" name="fecha" id="fecha" placeholder="DD/MM/AAAA" value="<?= htmlspecialchars($fecha ?? '') ?>">
      </div>

      <div class="form-group full-width">
        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
      </div>
    </form>
  </div>
</section>

<section class="tours">
  <h2>Tours disponibles</h2>
  <div class="tarjetas">
    <?php if (!empty($tours)): ?>
      <?php foreach ($tours as $tour): ?>
        <div class="tarjeta">
          <img src="assets/images/<?= !empty($tour['imagen_principal']) ? htmlspecialchars($tour['imagen_principal']) : 'placeholder.png' ?>" alt="<?= htmlspecialchars($tour['nombre_tour']) ?>">
          <div class="contenido">
            <h3><?= htmlspecialchars($tour['nombre_tour']) ?></h3>
            <p><?= htmlspecialchars($tour['descripcion'] ?? '-') ?></p>
            <p><strong>Duración:</strong> <?= htmlspecialchars($tour['duracion_horas']) ?> horas</p>
            <p><strong>Precio:</strong> $<?= number_format($tour['precio_por_persona'], 0, ',', '.') ?></p>
            <?php if (!empty($tour['fecha_inicio'])): ?>
              <p><strong>Inicio:</strong> <?= htmlspecialchars($tour['fecha_inicio']) ?></p>
            <?php endif; ?>
            <a href="index.php?page=detalle_tour&id=<?= $tour['id_tour'] ?>" class="boton-ver-mas">Ver más</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No se encontraron tours para tu búsqueda.</p>
    <?php endif; ?>
  </div>
</section>

<?php include_once("views/componentes/pie.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
flatpickr("#fecha", { dateFormat: "Y-m-d", minDate: "today", locale: "es" });
</script>

</body>
</html>
