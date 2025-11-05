<?php
if (!isset($_SESSION['id_usuarios']) || !in_array($_SESSION['id_perfiles'] ?? 0, [2,3])) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/provincia.php');
$provinciaModel = new Provincia();
$provincias = $provinciaModel->traer_provincias();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Cargar Hotel</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="assets/css/mi_perfil_proveedor.css">
  <link rel="stylesheet" href="assets/css/hotel_carga.css">
</head>
<body>

<main class="contenido-principal">
  <div class="container">
    <div class="panel">
      <h2>Crear Hotel</h2>
      <p class="hint">Cargá la información base del hotel.</p>

      <form id="formHotel" enctype="multipart/form-data" class="grid grid-2">
        <input type="hidden" name="action" value="guardar">

        <div>
          <label for="hotel_nombre">Nombre del Hotel</label>
          <input type="text" id="hotel_nombre" name="hotel_nombre">
        </div>

        <div>
          <label for="rela_provincia">Provincia</label>
          <select id="rela_provincia" name="rela_provincia">
            <option value="">Seleccionar provincia...</option>
            <?php foreach ($provincias as $prov): ?>
              <option value="<?= $prov['id_provincia'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label for="rela_ciudad">Ciudad</label>
          <select id="rela_ciudad" name="rela_ciudad">
            <option value="">Seleccionar ciudad...</option>
          </select>
        </div>

        <div>
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion">
        </div>

        <div>
          <label for="imagen_principal">Imagen principal</label>
          <input type="file" id="imagen_principal" name="imagen_principal" accept="image/*">
          <div id="preview-principal" class="preview-container"></div>
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="descripcion">Descripción</label>
          <textarea id="descripcion" name="descripcion" maxlength="1000"></textarea>
          <small id="descripcion-count" class="char-count">0/1000</small>
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="servicios">Servicios</label>
          <textarea id="servicios" name="servicios" maxlength="500" placeholder="Ej: WiFi, Desayuno incluido..."></textarea>
          <small id="servicios-count" class="char-count">0/500</small>
        </div>

        <div>
          <label for="politicas_cancelacion">Políticas de cancelación</label>
          <textarea id="politicas_cancelacion" name="politicas_cancelacion" maxlength="500"></textarea>
          <small id="politicas-count" class="char-count">0/500</small>
        </div>

        <div>
          <label for="reglas">Reglas</label>
          <textarea id="reglas" name="reglas" maxlength="500"></textarea>
          <small id="reglas-count" class="char-count">0/500</small>
        </div>

        <div class="grid" style="grid-column: 1 / -1;">
          <label for="fotos">Fotos adicionales</label>
          <input type="file" id="fotos" name="fotos[]" accept="image/*" multiple>
          <small>Podes seleccionar varias imágenes a la vez (máx. 10)</small>
          <div id="preview-fotos" class="preview-container"></div>
        </div>

        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? ''); ?>">

        <div class="actions" style="grid-column: 1 / -1;">
          <a href="index.php?page=proveedores_perfil" class="btn secondary">Cancelar</a>
          <button type="submit" class="btn">Guardar hotel</button>
        </div>
      </form>

    </div>
  </div>
</main>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/hotel_carga.js"></script>
</body>
</html>
