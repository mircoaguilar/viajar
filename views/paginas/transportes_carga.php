<?php
if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 5) {
  header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
  exit;
}

require_once('models/tipo_transporte.php');
$tipoModel = new TipoTransporte();
$tipos = $tipoModel->traer_tipos_transportes();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Cargar Transporte</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/hotel_carga.css">
  <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
</head>
<body>

<main class="contenido-principal">
  <div class="container">
    <div class="panel">
      <h2>Crear Transporte</h2>
      <p class="hint">Cargá la información de tu vehículo para ofrecer viajes.</p>

      <form id="formTransporte" enctype="multipart/form-data" class="grid grid-2">
        <input type="hidden" name="action" value="guardar">

        <div>
          <label for="transporte_matricula">Matrícula / Patente</label>
          <input type="text" id="transporte_matricula" name="transporte_matricula" >
        </div>

        <div>
          <label for="transporte_capacidad">Capacidad (personas)</label>
          <input type="number" id="transporte_capacidad" name="transporte_capacidad" min="1">
        </div>

        <div>
          <label for="rela_tipo_transporte">Tipo de transporte</label>
          <select id="rela_tipo_transporte" name="rela_tipo_transporte">
            <option value="">Seleccionar...</option>
            <?php foreach ($tipos as $tipo): ?>
              <option value="<?= $tipo['id_tipo_transporte'] ?>">
                <?= htmlspecialchars($tipo['descripcion']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="nombre_servicio">Nombre del servicio</label>
          <input type="text" id="nombre_servicio" name="nombre_servicio">
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion"></textarea>
        </div>

        <div>
          <label for="imagen_principal">Imagen principal</label>
          <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <h3>Configuración de pisos</h3>
          <div id="contenedorPisos"></div>
          <button type="button" id="btnAgregarPiso" class="btn secondary">Agregar piso</button>
        </div>

        <div class="actions" style="grid-column: 1 / -1;">
          <a href="index.php?page=mis_transportes" class="btn secondary">Cancelar</a>
          <button type="submit" class="btn">Guardar transporte</button>
        </div>
      </form>
    </div>
  </div>
</main>

<script src="assets/js/transportes_carga.js"></script>
</body>
</html>
