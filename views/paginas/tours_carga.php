<?php
if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 14) { // perfil guía
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/proveedor.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Cargar Tour Guiado</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
  <link rel="stylesheet" href="assets/css/hotel_carga.css"> 
</head>
<body>

<main class="contenido-principal">
  <div class="container">
    <div class="panel">
      <h2>Crear Tour Guiado</h2>
      <p class="hint">Cargá la información base de tu tour.</p>

      <form id="formTour" enctype="multipart/form-data" class="grid grid-2">
        <input type="hidden" name="action" value="guardar">

        <div>
          <label for="nombre_tour">Nombre del tour</label>
          <input type="text" id="nombre_tour" name="nombre_tour" required>
        </div>

        <div>
          <label for="duracion_horas">Duración (HH:MM)</label>
          <input type="text" id="duracion_horas" name="duracion_horas" placeholder="Ej: 02:40" required>
        </div>

        <div>
          <label for="precio_por_persona">Precio por persona</label>
          <input type="number" id="precio_por_persona" name="precio_por_persona" min="0" step="0.01" required>
        </div>

        <div>
          <label for="hora_encuentro">Hora de encuentro (HH:MM)</label>
          <input type="text" id="hora_encuentro" name="hora_encuentro" placeholder="Ej: 14:30" required>
        </div>

        <div>
          <label for="lugar_encuentro">Lugar de encuentro</label>
          <input type="text" id="lugar_encuentro" name="lugar_encuentro" required>
        </div>

        <div>
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion">
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion" maxlength="1000"></textarea>
          <small id="descripcion-count" class="char-count">0/1000</small>
        </div>

        <div>
          <label for="imagen_principal">Imagen principal</label>
          <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*" required>
          <div id="preview-principal" class="preview-container"></div>
        </div>

        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? ''); ?>">

        <div class="actions" style="grid-column: 1 / -1;">
          <a href="index.php?page=tours_mis_tours" class="btn secondary">Cancelar</a>
          <button type="submit" class="btn">Guardar tour</button>
        </div>
      </form>

    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/tours_carga.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
flatpickr("#duracion_horas", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});

flatpickr("#hora_encuentro", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
});
</script>

</body>
</html>
