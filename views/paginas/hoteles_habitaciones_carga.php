<?php

if (!isset($_SESSION['id_usuarios']) || ($_SESSION['id_perfiles'] ?? 0) != 3) {
    header('Location: index.php?page=login&message=Acceso no autorizado.&status=danger');
    exit;
}

require_once('models/tipo_habitacion.php');
require_once('models/hotel.php');

$tipoModel = new TipoHabitacion();
$hotelModel = new Hotel();

$tipos = $tipoModel->traer_tipos_habitaciones();

$id_hotel = $_GET['id_hotel'] ?? '';
if (!$id_hotel) {
    header('Location: index.php?page=proveedores_perfil&message=Hotel no especificado&status=danger');
    exit;
}

$id_usuario = $_SESSION['id_usuarios'];
if (!$hotelModel->verificar_propietario($id_hotel, $id_usuario)) {
    header('Location: index.php?page=proveedores_perfil&message=Acceso denegado al hotel seleccionado&status=danger');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cargar Habitaciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/habitaciones.css">
</head>
<body>

<main class="contenido-principal">
    <div class="container">
        <div class="panel">
            <h2>Cargar Habitaciones</h2>
            <p class="hint">Agregá las habitaciones del hotel. Podés cargar varias y previsualizarlas antes de guardar.</p>

            <div id="form-alert" style="display:none;" class="alert"></div>

            <form id="form-habitaciones" class="grid grid-2" method="POST" enctype="multipart/form-data" action="controllers/hotel_habitaciones/hotel_habitaciones.controlador.php">
                <input type="hidden" name="action" value="guardar">
                <input type="hidden" name="rela_hotel" value="<?= htmlspecialchars($id_hotel) ?>">

                <div>
                    <label for="nombre_tipo_habitacion">Tipo de Habitación</label>
                    <select id="nombre_tipo_habitacion" name="rela_tipo_habitacion" >
                        <option value="">Seleccionar tipo...</option>
                        <?php foreach ($tipos as $tipo): ?>
                            <option value="<?= $tipo['id_tipo_habitacion'] ?>"><?= htmlspecialchars($tipo['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="error"></div>
                </div>

                <div>
                    <label for="capacidad_maxima">Capacidad máxima</label>
                    <input type="number" id="capacidad_maxima" name="capacidad_maxima" min="1" >
                    <div class="error"></div>
                </div>

                <div>
                    <label for="precio_base_noche">Precio base por noche</label>
                    <input type="number" id="precio_base_noche" name="precio_base_noche" min="0" step="0.01" >
                    <div class="error"></div>
                </div>

                <div class="grid" style="grid-column: 1 / -1;">
                    <label for="descripcion_unidad">Descripción</label>
                    <textarea id="descripcion_unidad" name="descripcion" maxlength="200" ></textarea>
                    <span id="contador-descripcion">0/200 caracteres</span>
                    <div class="error"></div>
                </div>

                <div class="grid" style="grid-column: 1 / -1;">
                    <label for="fotos_habitacion">Fotos de la habitación</label>
                    <input type="file" id="fotos_habitacion" name="fotos[]" multiple accept="image/jpeg,image/png">
                    <p class="hint">Máx. 5 imágenes (JPEG/PNG), cada una hasta 5MB.</p>
                    <div class="error"></div>
                </div>

                <div class="grid" style="grid-column: 1 / -1;">
                    <button type="button" id="previsualizar" class="btn">Previsualizar</button>
                </div>

                <table id="tabla-preview" class="table" style="display:none;">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Capacidad</th>
                            <th>Precio</th>
                            <th>Descripción</th>
                            <th>Fotos</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>

                <div class="actions" style="grid-column: 1 / -1;">
                    <button type="submit" class="btn">Guardar Habitación</button>
                    <a href="index.php?page=proveedores_perfil" class="btn secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</main>

<div id="custom-alert" style="display:none;">
    <div>
        <p id="custom-alert-message"></p>
        <button id="custom-alert-btn" class="btn">Aceptar</button>
    </div>
</div>

<script src="assets/js/habitaciones_carga.js"></script>
</body>
</html>
