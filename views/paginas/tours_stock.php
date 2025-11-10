<?php

if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,14])) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/Tour.php');

$tourModel = new Tour();

$id_usuario = $_SESSION['id_usuarios'];
$tours = $tourModel->traer_tours_por_usuario($id_usuario);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cargar Stock de tours</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
  <link rel="stylesheet" href="assets/css/hotel_carga.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<main class="contenedor-principal">
  <div class="container">
    <div class="panel">
      <h2>Cargar Stock de Tours</h2>
      <p class="hint">Seleccioná el tour y luego indicá la cantidad disponible por fecha.</p>

      <form id="form-stock" class="grid grid-2" autocomplete="off">
        <input type="hidden" name="action" value="guardar_stock">

        <div>
          <label for="rela_tour">Tour</label>
          <select id="rela_tour" name="rela_tour">
            <option value="">Seleccionar tour...</option>
            <?php foreach ($tours as $t): ?>
              <option value="<?= $t['id_tour'] ?>"><?= htmlspecialchars($t['nombre_tour']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="fecha_inicio">Fecha inicio</label>
          <input type="text" id="fecha_inicio" name="fecha_inicio" placeholder="YYYY-MM-DD">
        </div>

        <div>
          <label for="fecha_fin">Fecha fin</label>
          <input type="text" id="fecha_fin" name="fecha_fin" placeholder="YYYY-MM-DD">
        </div>

        <div>
          <label for="cantidad">Cupos disponibles</label>
          <input type="number" id="cantidad" name="cantidad" min="1" value="1">
        </div>

        <div style="grid-column: 1 / -1;">
          <button type="button" id="previsualizar" class="btn">Previsualizar</button>
        </div>

        <table id="tabla-preview" class="table" style="display:none; margin-top: 12px;">
          <thead>
            <tr>
              <th>Tour</th>
              <th>Fecha</th>
              <th>Cupos</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        <div class="actions" style="grid-column: 1 / -1; margin-top: 12px;">
          <button type="submit" class="btn">Guardar Stock</button>
          <a href="index.php?page=proveedores_perfil" class="btn secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script src="assets/js/tours_stock.js"></script>

</body>
</html>
