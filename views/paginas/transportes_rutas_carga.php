<?php
if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/ciudad.php');
require_once('models/transporte.php');

$ciudadModel = new Ciudad();
$ciudades = $ciudadModel->traer_ciudades();

$transporteModel = new Transporte();
$transportes = $transporteModel->traer_transportes_por_usuario($_SESSION['id_usuarios']);

$id_transporte_url = (int)($_GET['id_transporte'] ?? 0);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Cargar Ruta</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
  <link rel="stylesheet" href="assets/css/hotel_carga.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<main class="contenido-principal">
  <div class="container">
    <div class="panel">
      <h2>Crear Ruta</h2>
      <p class="hint">Ingresá la información de la ruta para tu transporte.</p>

      <form id="formRuta" class="grid grid-2" action="controllers/transportes/rutas.controlador.php?action=guardar" method="POST">
        <input type="hidden" name="action" value="guardar">

        <div>
          <label for="nombre">Nombre de la ruta</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ej: Formosa – Clorinda">
        </div>

        <div>
          <label for="trayecto">Trayecto</label>
          <input type="text" id="trayecto" name="trayecto" placeholder="Ej: Formosa - Herradura - Clorinda">
        </div>

        <div>
          <label for="rela_transporte">Transporte</label>
          <select id="rela_transporte" name="rela_transporte">
            <option value="">Seleccionar transporte...</option>

            <?php foreach ($transportes as $t): ?>
              <option value="<?= $t['id_transporte'] ?>"
                <?= ($t['id_transporte'] == $id_transporte_url) ? 'selected' : '' ?>
                data-nombre="<?= htmlspecialchars($t['nombre_servicio']) ?>"
                data-tipo="<?= htmlspecialchars($t['tipo_transporte']) ?>"
                data-matricula="<?= htmlspecialchars($t['transporte_matricula']) ?>"
                data-capacidad="<?= htmlspecialchars($t['transporte_capacidad']) ?>"
                data-img="<?= htmlspecialchars($t['imagen_principal'] ?? '') ?>">
                <?= htmlspecialchars($t['nombre_servicio']) ?> (<?= htmlspecialchars($t['transporte_matricula']) ?>)
              </option>
            <?php endforeach; ?>

          </select>

          <div id="previewTransporte" class="preview-transporte"></div>
        </div>

        <div>
          <label for="rela_ciudad_origen">Ciudad de origen</label>
          <select id="rela_ciudad_origen" name="rela_ciudad_origen">
            <option value="">Seleccionar origen...</option>
            <?php foreach ($ciudades as $c): ?>
              <option value="<?= $c['id_ciudad'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="rela_ciudad_destino">Ciudad de destino</label>
          <select id="rela_ciudad_destino" name="rela_ciudad_destino">
            <option value="">Seleccionar destino...</option>
            <?php foreach ($ciudades as $c): ?>
              <option value="<?= $c['id_ciudad'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="duracion">Duración (HH:MM)</label>
          <input type="text" id="duracion" name="duracion" placeholder="Ej: 02:40">
        </div>

        <div>
          <label for="precio_por_persona">Precio por persona</label>
          <input type="number" id="precio_por_persona" name="precio_por_persona" min="0" step="0.01">
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion" placeholder="Información adicional de la ruta..."></textarea>
        </div>

        <div class="actions" style="grid-column: 1 / -1;">
          <a href="index.php?page=mis_transportes" class="btn secondary">Cancelar</a>
          <button type="submit" class="btn">Guardar ruta</button>
        </div>

      </form>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="assets/js/rutas_carga.js"></script>
<script>
flatpickr("#duracion", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    defaultHour: 2,
    defaultMinute: 0
});
</script>

</body>
</html>
