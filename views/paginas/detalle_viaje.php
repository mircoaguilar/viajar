<?php
require_once('models/transporte.php');
require_once('models/transporte_piso.php');
require_once('models/transporte_rutas.php');
require_once('models/viaje.php');

if (!isset($_GET['id'])) {
    die("Viaje no especificado.");
}

$idViaje = intval($_GET['id']);

$viajesModel = new Viaje();
$viaje = $viajesModel->traer_viaje_por_id($idViaje);
if (!$viaje) die("Viaje no encontrado.");

$idTransporte = $viaje['rela_transporte'];
$transporteModel = new Transporte();
$transporte = $transporteModel->traer_transporte_por_id($idTransporte);
if (!$transporte) die("Transporte no encontrado.");

$transportePisoModel = new TransportePiso();
$pisos = $transportePisoModel->traer_pisos_por_transporte($idTransporte);

$rutaModel = new Transporte_Rutas();
$ruta = $rutaModel->traer_por_id($viaje['rela_transporte_rutas']);
if (!$ruta) die("Ruta no encontrada.");
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle del transporte - <?= htmlspecialchars($transporte['nombre_servicio']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 
  <link rel="stylesheet" href="assets/css/detalle_transporte.css">
</head>
<body 
  data-transporte-id="<?= $idTransporte ?>"
  data-viaje-id="<?= $idViaje ?>"
  data-precio="<?= $ruta['precio_por_persona'] ?>"
  data-fecha="<?= $viaje['viaje_fecha'] ?>"
  data-origen="<?= htmlspecialchars($ruta['rela_ciudad_origen']) ?>"
  data-destino="<?= htmlspecialchars($ruta['rela_ciudad_destino']) ?>"
  data-hora-salida="<?= $viaje['hora_salida'] ?>"
  data-hora-llegada="<?= $viaje['hora_llegada'] ?>"
>
  <h2><?= htmlspecialchars($transporte['nombre_servicio']) ?></h2>

  <div class="detalle-transporte">
    <img src="assets/images/<?= htmlspecialchars($transporte['imagen_principal']) ?>" alt="Imagen del transporte" width="400">
    <div class="info-transporte">
      <p><strong>Descripción:</strong> <?= htmlspecialchars($transporte['descripcion']) ?></p>
      <p><strong>Tipo:</strong> <?= htmlspecialchars($transporte['tipo_transporte']) ?></p>
      <p><strong>Proveedor:</strong> <?= htmlspecialchars($transporte['proveedor_nombre']) ?></p>
      <p><strong>Capacidad:</strong> <?= htmlspecialchars($transporte['transporte_capacidad']) ?> pasajeros</p>
      <p><strong>Ruta:</strong> <?= htmlspecialchars($ruta['trayecto']) ?> (<?= htmlspecialchars($ruta['ciudad_origen']) ?> → <?= htmlspecialchars($ruta['ciudad_destino']) ?>)</p>
      <p><strong>Fecha:</strong> <?= $viaje['viaje_fecha'] ?></p>
      <p><strong>Salida:</strong> <?= $viaje['hora_salida'] ?> | <strong>Llegada:</strong> <?= $viaje['hora_llegada'] ?></p>
      <p><strong>Precio por asiento:</strong> $<?= number_format($ruta['precio_por_persona'], 0, ',', '.') ?></p>
    </div>
  </div>

  <h3>Seleccioná tus asientos</h3>
  <div class="bus-wrapper">
    <?php foreach($pisos as $piso): ?>
      <div class="bus-container" 
           data-filas="<?= $piso['filas'] ?>" 
           data-asientos="<?= $piso['asientos_por_fila'] ?>" 
           data-numero="<?= $piso['numero_piso'] ?>">
        <h3>Piso <?= $piso['numero_piso'] ?></h3>
        <div id="asientos-piso<?= $piso['numero_piso'] ?>" class="asientos"></div>
      </div>
    <?php endforeach; ?>
  </div>

  <div id="loader-asientos" class="loader-oculto">Cargando...</div>

  <div class="info">
    <p>🟩 Disponible &nbsp; 🟦 Seleccionado &nbsp; 🟥 Ocupado</p>

    <div id="resumen">
      <p><strong>Seleccionados:</strong> <span id="contador">0</span></p>
      <p><strong>Total:</strong> $<span id="total">0</span></p>
    </div>

    <button id="btn-confirmar">Agregar al carrito</button>
  </div>

  <div id="modal-completo" class="modal" style="display:none;">
    <div class="modal-content" style="max-width:1000px;">
      <span id="modal-completo-cerrar" class="cerrar">&times;</span>
      <h3>Asientos seleccionados y datos de pasajeros</h3>

      <div style="display:flex;gap:16px;align-items:flex-start;">
        <div style="flex:0 0 320px;border-right:1px solid #eee;padding-right:12px;">
          <h4>Asientos</h4>
          <div id="lista-asientos" style="max-height:360px;overflow:auto;"></div>
          <p style="margin-top:8px;"><strong>Total:</strong> $<span id="modal-total-unique">0</span></p>
        </div>

        <div style="flex:1;padding-left:12px;">
          <h4>Datos de pasajeros</h4>
          <form id="form-pasajeros-completo">
            <div id="contenedor-formularios"></div>

            <div style="margin-top:12px;display:flex;gap:8px;">
              <button type="submit" id="btn-confirmar-pasajeros">Confirmar y agregar al carrito</button>
              <button type="button" id="btn-cancelar-pasajeros">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <?php include_once(__DIR__ . '/../componentes/pie.php'); ?>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="assets/js/asientos.js"></script>

</body>
</html>
