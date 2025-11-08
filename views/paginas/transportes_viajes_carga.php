<?php
if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
  header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
  exit;
}

require_once('models/transporte.php');

$transporteModel = new Transporte();
$rutas = $transporteModel->traer_rutas_por_usuario($_SESSION['id_usuarios']); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Cargar Viaje</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
  <link rel="stylesheet" href="assets/css/hotel_carga.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<main class="contenido-principal">
  <div class="container">
    <div class="panel">
      <h2>Programar Viaje</h2>
      <p class="hint">Completá la información para programar un nuevo viaje en una de tus rutas.</p>

      <form id="formViaje" class="grid grid-2">
        <input type="hidden" name="action" value="guardar">

        <div>
          <label for="rela_transporte_rutas">Ruta</label>
          <select id="rela_transporte_rutas" name="rela_transporte_rutas" >
            <option value="">Seleccionar ruta...</option>
            <?php foreach ($rutas as $r): ?>
              <option value="<?= $r['id_ruta'] ?>">
                <?= htmlspecialchars($r['nombre']) ?> (<?= htmlspecialchars($r['trayecto']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="viaje_fecha">Fecha del viaje</label>
          <input type="text" id="viaje_fecha" name="viaje_fecha" >
        </div>

        <div>
          <label for="hora_salida">Hora de salida</label>
          <input type="text" id="hora_salida" name="hora_salida" >
        </div>

        <div>
          <label for="hora_llegada">Hora de llegada</label>
          <input type="text" id="hora_llegada" name="hora_llegada" >
        </div>

        <div class="actions" style="grid-column: 1 / -1;">
          <a href="index.php?page=transportes_mis_transportes" class="btn secondary">Cancelar</a>
          <button type="submit" class="btn">Guardar viaje</button>
        </div>
      </form>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/viajes_carga.js"></script>

<script>
  flatpickr("#viaje_fecha", {
    dateFormat: "Y-m-d",
    minDate: "today"
  });

  flatpickr("#hora_salida", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
  });

  flatpickr("#hora_llegada", {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
  });
</script>

</body>
</html>
